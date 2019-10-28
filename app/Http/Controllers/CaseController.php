<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Cases;
use App\CaseType;
use App\CaseStatus;

use Validator;


class CaseController extends Controller
{

    public $user_group_id_register;
    public $user_group_id_guest;
    public $status_id_active;
    public $company_id_prefix;
    public $station_status_unregistered;
    public $station_status_registered;
    public $station_status_problematic;
    public $station_status_sent;
    public $station_status_edited_for_resend;
    public $station_status_sent_but_problematic;

    public function __construct()
    {

        $this->user_group_id_register = config('app.USER_GROUP_ID_REGISTER');
        $this->user_group_id_guest = config('app.USER_GROUP_ID_GUEST');
        $this->status_id_active = config('app.CASE_STATUS_ACTIVE');
        $this->company_id_prefix = config('app.COMPANY_ID_PREFIX');
        $this->station_status_unregistered = config('app.STATION_STATUS_UNREGISTERED');
        $this->station_status_registered = config('app.STATION_STATUS_REGISTERED');
        $this->station_status_problematic = config('app.STATION_STATUS_PROBLEMATIC');
        $this->station_status_sent = config('app.STATION_STATUS_SENT');
        $this->station_status_edited_for_resend = config('app.STATION_STATUS_EDITED_FOR_RESEND');
        $this->station_status_sent_but_problematic = config('app.STATION_STATUS_SENT_BUT_PROBLEMATIC');

        $this->middleware(function ($request, $next) {
            // if (Auth::user()->group_id != 1)
            //     exit;
            return $next($request);
        });

    }


    // private function getUserCaseTypes($user_id, $user_group_id)
    // {
    // 	 $data['case_types'] = Cache::rememberForever('case_type', function ($user_id, $user_group_id) {
    //         return CaseType::UserCaseTypes($user_id, $user_group_id)->get();
    //     });

    // 	return $data;
    // }
    public function index()
    {

        $user = User::find(Auth::id());
        // check if the user is responsible to handle 1 election. If redirect to station page


        //         if ($user->group_id !=config('app.USER_GROUP_ID_ADMIN')) {
        //             $query = db::table('cases_regions_users')
        //                 ->select('cases.cases_id')
        //                 ->join('cases', 'cases_regions_users.cases_id', '=', 'cases.cases_id')
        //                 ->where('user_id', '=', $user->id)
        //                 ->whereRaw('cases.status_id=' . config('app.CASE_STATUS_ACTIVE'))
        //                 ->distinct();
        //             $query_count  = $query->count();
        //        } else {
        //            $query=Case::select('cases_id');
        //            $query_count=$query->count();
        //        }
        $query = Cases::UserCases($user->id, $user->group_id);
        $query_count = $query->count();
        if ($query_count == 0) {
            echo trans('messages.ERROR_MESSAGE_CASES_NOT_AUTHORISED');
            exit;
        } else if ($query_count == 1) {
            //$cases =  $query->get()[0]->cases_id;
            //var_dump($cases);die();
            $cases_id = $query->get()->first()->case_id;
            $redirect = '/'. $cases_id . '/stations/';

            return redirect($redirect);
        }
        //--- 1. begin ------
        $data['cases_types'] = CaseType::UserCaseTypes($user->id, $user->group_id)->distinct()->get();
        // $query = DB::table('cases')
        // ->select('cases_types.case_type_id', 'cases_types.case_type_descr')
        // ->join('cases_types', 'cases.case_type_id', '=', 'cases_types.case_type_id')
        // ->where('cases.status_id','=', $this->status_id_active);
        // if ($user->group_id != $this->user_group_id_register) {
        //     $query->whereraw('cases.cases_id in (select distinct cases_id from `cases_regions_users` where user_id = ?)',[$user->id]);
        // }
        // $data['cases_types']= $query->get();
        //------1.  <end>  -------
        //
        //$data['cases_types'] = CaseType::select('case_type_id', 'case_type_descr')->OrderBy('case_type_descr','ASC')->get();


        return View::make('cases.index')->with('data', $data);
    }


    public function create()
    {
        exit;
        $data = array();
        $user = User::find(Auth::id());
        //check permissions
        // if useer is not the admin then exit
        if ($user->group_id != $this->user_group_id_admin) {
            exit;
        }

        $data['user'] = $user;
        return View::make('cases.create')->with('data', $data);

    }

    public function store(Request $request)
    {
        exit;
        //if edit check if id is valid on db
        if (intval(Input::get('id')) > 0) {
            Cases::findOrFail((intval(Input::get('id'))));
        }

        /*
        if (intval(Input::get('id')) > 0) {
            $validator = Validator::make($request->all(), [
                'case_descr' => 'required|max:255',
                'case_date' => 'date',
                'case_type_id' => 'required|numeric'
            ]);

        } else {
            $validator = Validator::make($request->all(), [
                'case_descr' => 'required|max:255',
                'case_type_id' => 'required|numeric',
                'is_active' => 'numeric',
                'case_date' => 'date'
            ]);
        }
        */
        $validator = Validator::make($request->all(), [
            'case_descr' => 'required|max:255',
            'case_date' => 'required|date',
            'case_type_id' => 'required|numeric',
            'case_code' => 'required|numeric'
        ]);
        // to clarify    
        //  get('id')  or get('cases_id')
        $error_redirect = '/create';
        if (intval(Input::get('id')) > 0) {
            $error_redirect = '/' . intval(Input::get('id')) . '/edit';
        }

        if ($validator->fails()) {
            return redirect($error_redirect)
                ->withErrors($validator)
                ->withInput();
        } else {

            $case = Cases::firstOrNew(array('id' => Input::get('id')));
            if (isset($case->cases_id) && $case->cases_id > 0) {
                $old_cases_id = e(Input::get('id'));
                $case->case_descr = e(Input::get('case_descr'));
                $old_case_descr = $case->case_descr;
                $case->case_date = e(Input::get('case_date'));
                $old_case_date = $case->case_date;
                $case->case_code = e(Input::get('case_code'));
                $old_case_code = $case->case_code;
                $case->case_type_id = e(Input::get('case_type_id'));
                $old_case_type_id = $case->case_type_id;
                $cases->is_active = e(Input::get('is_active'));
                $old_is_active = $cases->is_active;


                if ((md5($case->case_descr) != md5($old_case_descr))
                    or (md5($case->case_date) != md5($old_case_date))
                    or (md5($case->case_code) != md5($old_case_code))
                    or (md5($case->case_type_id) != md5($old_case_type_id))
                    or (md5($case->is_active) != md5($old_is_active))) {

                    Cases::where('cases_id', $old_cases_id)
                        ->update(['case_descr' => $case->case_descr])
                        ->update(['case_type_id' => $case->case_type_id])
                        ->update(['case_date' => $case->case_date])
                        ->update(['case_code' => $case->case_code])
                        ->update(['is_active' => $case->is_active]);

                }

            } else {
                $case->case_descr = intval(Input::get('case_descr'));
                $case->case_date = e(Input::get('case_date'));
                $case->case_type_id = intval(Input::get('case_type_id'));
                $case->case_code = intval(Input::get('case_code'));
                $case->is_active = intval(Input::get('is_active'));
            }
            $case->save();
            if (intval(Input::get('id')) > 0) {
                return redirect('cases')->with('success', trans('messages.case_successfully_updated'));
            } else {
                return redirect('cases')->with('success', trans('messages.case_successfully_created'));
            }
            return View::make('cases.index');
        }

    }

    public function edit($id)
    {
        exit;
    }

    public function destroy()
    {

        exit;
    }
    public function send($id)
    {
        $data['send_msg_id']= config('app.CASE_STATION_SEND_ID_UNDER_CONSTRUCTION');
        $data['updated']= 0;

        return View::make('cases.send')->with('data', $data);

    }

    public function data()
    {

        $data = new \stdClass();
        $data->rows = array();

        $user = User::find(Auth::id());

        //$query = new Case();
        //$query =  Case::with('type')->get();
        //$query->with('type')->get();
        //$query->with('status')->get();
        //---  1.   begin ----
        // $query=DB::table('cases')->select('cases.cases_id' , 'cases.case_descr', 'cases.case_date','cases_types.case_type_descr')
        // ->join('cases_types', 'cases.case_type_id', '=', 'cases_types.case_type_id')
        // ->join('cases_statuses', 'cases.status_id', '=', 'cases_statuses.status_id')
        // ->where('cases.status_id','=', $this->status_id_active);

        // if ($user->group_id != $this->user_group_id_admin) {
        //     $query->whereraw('cases.cases_id in (select distinct cases_id from `cases_regions_users` where user_id = ?)',[$user->id]);
        // }
        $query = Cases::UserCases($user->id, $user->group_id);
        // -- 1 end ----
        if (Input::has('search_case_type') && intval(Input::get('search_case_type')) > 0) {
            $query = $query->where("cases.case_type_id", '=', intval(Input::get('search_case_type')));
        }
        //        if (Input::has('search_case_year') && Input::get('search_case_year') != null) {

        //            $query = $query->where("date('Y', strtotime($case_date))", intval(Input::get('search_case_year')));

        //       }

        if (Input::get('sort') && Input::get('order')) {
            $query = $query->orderBy('case_date' . e(Input::get('sort')), e(Input::get('order')));
        } else {
            $query = $query->orderBy('case_type_descr', 'ASC')->orderBy('case_date', 'ASC');
        }

        $data->total = $query->count();
        $query = $query->offset(intval(Input::get('offset')))->limit(intval(Input::get('limit')));
        //$query = $query->limit(20)->offset(0);
        $cases = $query->get()->all();


        if ($cases) {
           foreach ($cases as $case) {
                $row = new \stdClass();
                $row->id = $case->case_id;
                $row->case_descr = $case->case_descr;
                $row->case_type_descr = $case->case_type_descr;
                $row->case_year = date('Y', strtotime($case->case_date));
                $row->case_date = $case->case_date;

                $data->rows[] = $row;

            }
        }

        return json_encode($data);

    }

}    