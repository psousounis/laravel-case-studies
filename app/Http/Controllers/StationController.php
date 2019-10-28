<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CaseRegion;
use App\User;
use App\Municipality;
use App\StationStatus;
use App\Station;
use App\StationVote;
use App\StationCaseItemVote;
use App\CaseParty;
use App\Cases;
use Validator;
use URL;

class StationController extends Controller
{
    public function index($case_id)
    {
        // $data['cases_regions'] = DB::select('case_region_id', 'case_region_name')->get();
        // if ($user->group_id != 1) {
        //     $query->whereraw('cases.case_id in (select distinct case_id from `cases_regions_users` where user_id = ?)',[$user->id]);
        // }

       // pass station_statuses to blade
        $data['station_status_unregistered']= config('app.STATION_STATUS_UNREGISTERED');
        $data['station_status_registered']= config('app.STATION_STATUS_REGISTERED');
        $data['station_status_problematic']= config('app.STATION_STATUS_PROBLEMATIC');
        $data['station_status_sent']= config('app.STATION_STATUS_SENT');
        $data['station_status_edited_for_resend']= config('app.STATION_STATUS_EDITED_FOR_RESEND');
        $data['station_status_sent_but_problematic']= config('app.STATION_STATUS_SENT_BUT_PROBLEMATIC');
 
        
        $user = User::find(Auth::id());
        // $return= intval(Station::IsUserStationValid($case_id,6,150,$user->id,$user->group_id));
        // echo $return;
        // exit;
        //  to do : check if user has right to add for this case_id
        // check if the election param is valid
        $queryrows=Station::ValidCaseParam($case_id,$user->id,$user->group_id)->count();
        if ($queryrows==0) {

            $msg = 'You have input wrong parameters!!';
            //abort(403, $msg, ['returnUrl'=>url('/')]); //URL::current()
            return view('errors.custom',['message'=>$msg, 'error_code'=>403,'return_url'=>url('/')]);


            //$queryrows==0

            //exit;
         } 
        // echo '<pre>'. print_r($query) .'</pre>';
        // exit;
          
        
        // ---1 <begin>  ----
        $data['case_regions']=Station::CaseRegions($case_id,$user->id,$user->group_id)->get();
        // if ($user->group_id != 1) {
        //     $data['cases_regions'] = DB::table('cases_regions')
        //     ->select('cases_regions.case_region_id', 'cases_regions.case_region_name')
        //     ->join('cases_regions_users', 'cases_regions.case_region_id', '=', 'cases_regions_users.case_region_id')
        //     ->where('case_id','=', $case_id)
        //     ->where('user_id','=', $user->id)->distinct()->get();
        // } else {
        //     $data['cases_regions']= CaseRegions::select('case_region_id', 'case_region_name')->get();
        // }
        //--- 1 end ------
        
        // if ($user->group_id != 1) {
        //     $query->whereraw('cases.case_id in (select distinct case_id from `cases_regions_users` where ]);
        // }
        // --- 2   begin -----
        $data['municipalities']= Station::Municipalities($case_id,$user->id,$user->group_id)->get();
        // if ($user->group_id != 1) {
        // $data['municipalities'] = DB::table('municipalities')->select('municipalities.municipality_id', 'municipalities.municipality_name')
        // ->join('stations','municipalities.municipality_id','=','stations.municipality_id')
        // ->crossjoin('cases_regions_users', 'stations.case_region_id','=','cases_regions_users.case_region_id')
        // ->whereraw('cases_regions_users.case_id=? and cases_regions_users.user_id=?
        // and stations.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to',
        // [$case_id,$user->id])->distinct()->get();
        // } else {
        //     $data['municipalities'] = Municipality::select('municipality_id', 'municipality_name')->get();
        // }
        //--- 2  end ---    

//        $data['stations_statuses'] =Cache::rememberForever('status_id', function () {
//            return  StationStatus::select('status_id', 'status_descr')->get();
//        });
        $data['stations_statuses'] = StationStatus::select('status_id', 'status_descr')->get();


        $user = User::find(Auth::id());
        $data['user'] = $user;
        $data['case_id'] = $case_id;
        $data['user_cases_count']=Cases::UserCases($user->id,$user->group_id)->count();
        $data['case'] = Cases::select('case_id', 'case_descr')->where('case_id','=',$case_id )->first(); //->toArray();
        //DB::enableQueryLog();
        $data['user_total_stations']=Station::TotalStations($case_id,$user->id,$user->group_id)->get();
        $data['stations_not_registered']= Station::NotRegistered($case_id,$user->id,$user->group_id)->get();
        // ----  3   begin:  get existing  stations---
        //DB::enableQueryLog();
        //  $data=$query->get()->all();
        // //      echo '<pre>';
        $query= Station::ExistingStationsPerStatus($case_id,$user->id,$user->group_id);
        //        $query->groupBy('stations_statuses.status_id');
        //        $query->orderBy('stations_statuses.status_id');
        $data['stations']=$query->get();


        //dd(DB::getQueryLog());
        // debugging data  (begin)

//        echo '<pre>';
//        foreach ($data['station'] as $d) {
//               print($d);
//             }
//         echo '</pre>';
//        die;
    // debugging data  (end)

        // if ($user->group_id != 1) {
        //     $data['stations']= DB::select('
        //     select stations_statuses.status_id, count(station_id) as total_rows 
        //     from cases
        //     cross join users
        //     cross join stations_statuses
        //     left join stations_view on stations_statuses.status_id=  stations_view.status_id
        //     and users.id = stations_view.user_id and cases.case_id  = stations_view.case_id
        //     where cases.case_id='. $case_id . ' and users.id='.$user->id . '
        //     group by stations_statuses.status_id
        //     order by stations_statuses.status_id ');
        // } else {
        //     $data['stations']= DB::select('
        //     select stations_statuses.status_id, count(station_id) as total_rows 
        //     from cases
        //     cross join users
        //     cross join stations_statuses
        //     left join stations_view on stations_statuses.status_id=  stations_view.status_id
        //     and users.id = stations_view.user_id and cases.case_id  = stations_view.case_id
        //     where cases.case_id='. $case_id . '
        //     group by stations_statuses.status_id
        //     order by stations_statuses.status_id ');
        // }
        //----- 3   end  --
        //$data['user_stations_analysis']=DB::raw('select cases_regions.case_region_id, cases_regions.case_region_name,
        // $data['user_stations']=DB::table('cases_regions_users')
        //       ->join('cases_regions','cases_regions.case_region_id','=','cases_regions_users.case_region_id')
        // ->where('cases_regions_users.case_id','=', $case_id)->where('cases_regions_users.user_id','=',$user->id)
        // ->select('cases_regions.case_region_id', 'cases_regions.case_region_name',
        // 'cases_regions_users.station_id_from', 'cases_regions_users.station_id_to',
        // '(station_id_to-station_id_from+1) as total_stations');
        //----4  begin ----
        //DB::enableQueryLog();
        $data['user_stations_analysis']=Station::UserStationsAnalysis($case_id,$user->id,$user->group_id)->distinct()->get();
        //dd(DB::getQueryLog());
        // if ($user->group_id != 1) {
        //     $data['user_stations_analysis']=DB::select('select `cases_regions`.`case_region_id`, `cases_regions`.`case_region_name`,
        //     `cases_regions_users`.`station_id_from`, `cases_regions_users`.`station_id_to`,
        //     (`cases_regions_users`.`station_id_to`-`cases_regions_users`.`station_id_from`+1) as total_stations
        //     from `cases_regions_users`
        //     inner join `cases_regions` on `cases_regions`.`case_region_id`=`cases_regions_users`.`case_region_id`
        //     where `cases_regions_users`.`case_id`='. $case_id. ' and `cases_regions_users`.`user_id`='.$user->id);
        // }else{
        //     $data['user_stations_analysis']=DB::select('select `cases_regions`.`case_region_id`, `cases_regions`.`case_region_name`,
        //     count( `stations`.`station_id`) as total_stations,
        //     min(`stations`.`station_id`) as station_id_from,
        //     max(`stations`.`station_id`) as station_id_to
        //     from `stations`
        //     inner join `cases_regions` on `cases_regions`.`case_region_id`=`stations`.`case_region_id`
        //     group by 1,2');
        // }
        // --- 4   end ------
        return View::make('stations.index')->with('data', $data);;
    }

    public function create()
    {

        if (config('app.Station_lock'))
            exit;

        $data = array();
        $user = User::find(Auth::id());

        //check permissions
        switch ($user->group_id) {
            case 1:
            case 2:
                break;
            default:
                exit;
                break;
        }
        
        $data['cases_regions'] = CaseRegion::select('case_id', 'case_region_name')->get();
        $data['municipalities'] = Municipality::select('municipality_id', 'municipality_name')->get();
        $data['statuses'] = Status::select('status_id', 'status_descr')->get();
        $data['user'] = $user;
        $data['action_id']=1;
        //"{{ url('/') }}/{{$data['case_id']}}/stations/"+ row.case_region_id +'/' + row.id + '/detail?action_id=1';
        return View::make('stations.detail')->with('data', $data);
    }

    public function detail($case_id,$case_region_id,$station_id)
    {
        
        //http://cases:8080/1/stations/0/0/detail?action=1
        //{case_id}/stations/{case_region_id}/{station_id}/detail
        $action_id =  intval(Input::get('action_id'));

        $data = array();
        
        $user = User::find(Auth::id());
        
        $data['user'] = $user;
        
        //  to do : check if user has right to add for this case_id, also case_region? and station
        $queryrows=Station::ValidCaseParameters($case_id,$case_region_id,$station_id,$user->id,$user->group_id)->count();
        if ($queryrows==0) {
                echo config('app.ERROR_MESSAGE_WRONG_PARAMETERS');
                exit;
        } 
        // echo '<pre>'. print_r($query) .'</pre>';
        // exit;
        $data['case_id'] = $case_id;
        
        $case_type=DB::table('cases')->select('case_type_id')->where('case_id','=',$case_id)->get();
        
        $data['case_type_id'] = $case_type[0]->case_type_id;
        $data['case_region_id']= $case_region_id;
        $data['station_id'] = $station_id;
        // -- 1 begin ---
        $data['case_regions'] = Station::CaseRegions($case_id,$user->id,$user->group_id)->get();
        // if ($user->group_id !=1) {
        //     $data['cases_regions'] =DB::table('cases_regions_users')
        //     ->select('cases_regions.case_region_id' , 'cases_regions.case_region_name')
        //     ->join('cases_regions', 'cases_regions_users.case_region_id','=','cases_regions.case_region_id')
        //     ->where('case_id','=',$case_id)
        //     ->where('user_id','=',$user->id)->get();
        // } else {
        //     $data['cases_regions'] = CaseRegion::select('case_region_id','case_region_name')->get();
            
        // } 
        // --- 1 end -- 

        //    i have temporarily put it in comment because of the ajax call oe case_region in detail.blade.php
        //     $data['stations']= Station::CaseRegionUserStations($case_id,$case_region_id,$user->id,$user->group_id)->get();
        // if ($case_region_id >0) {        
        //     //----2 begin --
        //     // if ($user->group_id ==2 ) {
        //     // $data['stations']= DB::table('stations')->select('stations.station_id')
        //     //                         ->join('cases_regions_users', 'stations.case_region_id','=','cases_regions_users.case_region_id')
        //     //                         ->where('cases_regions_users.case_id','=',$case_id)
        //     //                         ->where('cases_regions_users.user_id','=',$user->id)
        //     //                         ->whereRaw('stations.case_region_id=?
        //     //                             and stations.station_id between cases_regions_users.station_id_from
        //     //                             and cases_regions_users.station_id_to',[$case_region_id])->get();
        //     // } else {
        //     //     $data['stations']= DB::table('stations')->select('stations.station_id')
        //     //                         ->where('stations.case_region_id','=',$case_region_id)->get();
        //     // }
        //     // ----- 2 end        
        // }
      
        
        //-- 3  begin ---
        /*
        $query = DB::select("
                select cases.case_id,
                        cases_regions.case_region_id,
                        cases_regions.case_region_name,
                        municipalities.municipality_id,
                        municipalities.municipality_name,
                        stations.station_id, 
                        ifnull( stations_statuses.status_id,-1)  as status_id,
                        ifnull( stations_statuses.status_descr,'ΜΗ ΚΑΤΑΧΩΡΗΜΕΝΟ')  as status_descr,
                        ifnull(votes.registered,0) as registered,
                        ifnull(votes.voted,0) as voted,
                        ifnull(votes.votes_invalid,0) as votes_invalid ,
                        ifnull(votes.votes_valid,0) as votes_valid,
                        ifnull(votes.votes_blank,0) as votes_blank,
                        ifnull(votes.votes_invalid_blank,0) as votes_invalid_blank,
                        users.name as user,
                        station_officers.station_officer_name, 
                        if(isNull(station_officers.phone2),station_officers.phone1,concat(station_officers.phone1 ,', ', station_officers.phone2)) as station_officer_phone
                from cases
                cross join stations 
                inner join cases_regions  on stations.case_region_id = cases_regions.case_region_id
                inner join municipalities on stations.municipality_id = municipalities.municipality_id
                left join station_officers on stations.station_officer_id=station_officers.station_officer_id
                left join votes on stations.station_id = votes.station_id
                            and  stations.municipality_id = votes.municipality_id
                            and  cases.case_id= votes.case_id
                left join users on votes.user_id = users.id          
                left join stations_statuses on votes.status_id = stations_statuses.status_id
                where cases.case_id=". $case_id. '
                and cases_regions.case_region_id ='.$case_region_id.'
                and stations.station_id='.$station_id);
        */
        //DB::enableQueryLog();  
        //printf ('%s %s %s', $case_id,$case_region_id,$station_id);
        $query = Station::Detail($case_id,$case_region_id,$station_id);

        // test cases
        //$data = DB::table('stations')->where('stations.station_id','=',1)->where('stations.case_region_id','=',6)->get();

        
        //  $data = DB::table('stations')->select (db::raw('station_id, municipality_id,case_region_id'))
        //  ->where('stations.station_id','=',$station_id)
        //  ->where('stations.case_region_id','=',$case_region_id);
        //  $data=$query;
        //DB::enableQueryLog();
    //  $data=$query->get()->all();  
    // //      echo '<pre>';
    //     //dd(DB::getQueryLog());
    // //      //var_dump($data);
    //       foreach ($data as $d) {
    //           dd($d);
    //       }
    //       echo '</pre>';
    //       die();
       
    //    $data=$query;
    //    echo '<pre>';
    //    foreach ($data as $d) {
    //      dd($d);
    //  }
    //    echo '</pre>';
    //    die();
        // important : if you use model Station then you must call get()->all() at the end to get the results
        // otherwise if tou use db::table the you nust call get() only.

        $query = $query->get()->all();
        
        //1res76
        
        //  print_r(DB::getQueryLog());
        //  exit;
        // -- 3  end ---
             //$query->count()==0   
        if (!$query) {  
            $station = new \stdClass(); 
            $station->row = array();               
            $station->case_id=$case_id;
            $station->case_region_id=0;
            $station->case_region_name='';
            $station->municipality_id=0;
            $station->municipality_name='';
            $station->station_id=0;
            $station_status_unregistered = config('app.STATION_STATUS_UNREGISTERED');
            $station_status_unregistered_label= config('app.STATION_STATUS_UNREGISTERED_LABEL');
            $station->status_id = $station_status_unregistered;
            $station->status_descr= $station_status_unregistered_label;
            $station->registered=null;
            $station->voted=null;
            $station->votes_invalid=0;
            $station->votes_blank=0;
            $station->votes_invalid_blank=0;
            $station->votes_valid=0;
            $station->station_officer_name=null;
            $station->station_officer_phone=null;
            $data['station'][0] = $station;

        }  else {
            $data['station']=$query;

        }                                             
         //echo '<pre>'.print_r($query).'<br/><pre>';
        // exit;
        //--- 4 begin --
        // case_type_id = 1 --> municipal
        //
        if ($case_type[0]->case_type_id != config('app.CASE_TYPE_MUNICIPAL')) {
          //      printf("case_region_id %s, station_id %s, actiuon_id %s", $case_region_id,$station_id,$action_id);
            $data['case_items']= Station::CaseItemsVotesByCaseRegion($case_id,$case_region_id,$station_id,$action_id)->orderBy('cases_items.case_item_code')->get()->all();

//            foreach ($data['case_items'] as $obj) {
//                $obj->votes = 5;
//                echo '<pre>';
//                var_dump($obj);
//                echo '</pre>';
//                echo '<hr/>';
//            }
//            die();

            // if ($station_id >0 && $case_region_id>0) {
            //     $data['case_items']=DB::select('
            //     select case_items.case_item_id, case_items.case_item_code, case_items.case_item_name, ifnull(case_items_votes.votes,0) as votes
            //     from case_items
            //     left join case_items_votes on case_items.case_id =case_items_votes.case_id and case_items.case_item_id = case_items_votes.case_item_id
            //     left join stations on  case_items_votes.station_id = stations.station_id 
            //     and case_items_votes.municipality_id = stations.municipality_id
            //     where case_items.case_id='.$case_id.'
            //     and stations.case_region_id ='.$case_region_id.'
            //     and stations.station_id='.$station_id .'
            //     order by case_items.case_item_id');
            // } else {
            //     $data['case_items']=DB::select('
            //     select case_items.case_item_id, case_items.case_item_code, case_items.case_item_name, 0 as votes
            //     from case_items
            //     where case_items.case_id='.$case_id.'
            //     order by case_items.case_item_code');
            // }
            // ---  4 end ---
        } else {
            
            $data['case_items'] = Station::MunicipalCaseItemsVotesByCaseRegion($case_id,$case_region_id,$station_id)->orderBy('cases_items.case_item_code')->get()->all();
            
            /*
            foreach ($data['case_items'] as $obj) {
                $obj->votes = 5;
                echo '<pre>';
                var_dump($obj);
                echo '</pre>';
                echo '<hr/>';
            }
            die();
            */
        }    
        
        
        if (session('extraParams')) {
            $extraParams = session('extraParams');
            if (isset($extraParams['caseitemsVotes'])) {
                $arr_votes = $extraParams['caseitemsVotes'];

                foreach ($data['case_items'] as $obj) {
                    if (isset($arr_votes[$obj->case_item_id])) {
                        $obj->votes = $arr_votes[$obj->case_item_id];
                    }
                }                
            }
        }


        //$data->case_items_total=count($query);
        //$data['case_items']->$query;

        //$caseItems['case_items'] = $query->get();
        $data['case_items_total']=count($data['case_items']);
        
        
        // if ($caseItems) {
        //     foreach ($caseItems as $party) {
        //         $row = new \stdClass();
        //         $row->id = $station->station_id;
        //         $row->case_region_id = $station->case_region_id;
        //         $row->case_region_name = $station->case_region_name;
        //         $row->municipality_id = $station->municipality_id;
        //         $row->municipality_name = $station->municipality_name;
        //         $row->status_descr = $station->status_descr;
        //         $row->action_id=$station->action_id;
                
        //         $data->rows[] = $row;
        //     }
        // }

        if  (Input::has('action_id')) {
                 $data['action_id']=intval(Input::get('action_id'));
        } else {
            $data['action_id']=0;
        }

        return View::make('stations.detail')->with('data', $data);
    }


    public function store(Request $request)
    {

        $user = User::find(Auth::id());

        //check permissions
         switch ($user->group_id) {
             case 1:
             case 2:
             case 3:
                 break;
             default:
        //         exit;
        //         break;
         }
        
        
        
        
        // 'registered' => 'required|numeric|min:1',
        //'voted' => 'required|numeric|min:0|max:'.$registered,
        // 'votes_invalid' => 'required|numeric|min:0|max:'.$max_votes_invalid,
        // 'votes_blank' => 'required|numeric|min:0|max:'.$max_votes_blank,
        //'id' => 'required|numeric|min:1',
        // to do 
        // 1. get user case  and set it to validation of case_id as in value
        // 2. also case_region
        $action_add_id = config('app.ACTION_ADD_ID');
        $action_modify_id = config('app.ACTION_MODIFY_ID');
        $validator = Validator::make($request->all(), [
            'action_id' => 'required|numeric|in:'.$action_add_id.','.$action_modify_id,    
            'station_id' => 'required|numeric|min:1',
            'case_id' => 'required|numeric|min:1',
            'case_region_id' => 'required|numeric|min:1',
            'municipality_id' => 'required|numeric|min:1',
            'old_municipality_id' => 'required|numeric|min:0',
            'status_id' => 'required|numeric|in:-1,1,2,3,4,5',    
            'registered' => 'required|numeric|min:1',
            'voted' => 'required|numeric|min:0',
            'votes_invalid' => 'required|numeric|min:0',
            'votes_blank' => 'required|numeric|min:0',
            'votes_valid' => 'required|numeric|min:0',                        
        ]);

        $registered =  intval(Input::get('registered'));
        $voted = intval(Input::get('voted'));
        $votes_invalid =  intval(Input::get('votes_invalid'));
        $max_votes_invalid = $voted - $votes_invalid;
        $votes_blank = intval(Input::get('votes_blank'));
        $max_votes_blank = $voted - $votes_blank;
        $votes_valid =  intval(Input::get('votes_valid'));
        $votes_invalid_blank =  intval(Input::get('votes_invalid_blank'));

        // echo 'poll.id:'.Input::get('station_id');
        // echo 'case_reg_id:'.Input::get('case_region');
        // exit;
        //$error_redirect = '';
        //    echo '<pre>';
        //print_r($_SERVER);
        //echo '</pre>';
        //exit;

        //$error_redirect= $_SERVER['HTTP_HOST'];
        //$error_redirect = $error_redirect . '/cases/'. intval(Input::get('case_id')) . '/stations/';
        // echo $error_redirect;
        // exit;
        

        // -----   validations ---------
        // validation rule 1
        // check if the new station_id has been already registered.
        // If yes inform user and do not allow him to save it.
        // i remind you that the check if (station_id >0 and municipality_id>0) has been already performed  
        $station_id= Input::get('station_id');
        if (input::get('action_id')== $action_add_id ||(input::get('action_id')==2 && input::get('id')>0 and input::get('station_id')!= input::get('id') ) ) {
            //----  1  begin ---
            // $query=DB::table('votes')->select('station_id')
            // ->where('case_id','=',Input::get('case_id'))
            // ->where('station_id','=',Input::get('station_id'))
            // ->where('municipality_id','=',Input::get('municipality_id'))->exists();
            
            $query = Station::Votes(Input::get('case_id'),Input::get('municipality_id'),Input::get('station_id'))->exists();    
            // ---- 1   end -- 
            if ($query) {
            $validator->after(function ($validator) {
                    $validator->errors()->add('station_id',trans('messages.ERROR_MESSAGE_STATION_ALREADY_EXIST'));
                    });
            }
        }
        //---- validation rule 2 ---- 
        // additional : do not allow polling station to be registered if user is not autorized for it
        // check only for not admins
        //---
        
        if ((Input::get('id') != Input::get('station_id')) && ($user->group_id !=1) ) {
         //   DB::enableQueryLog();
         // check if the the user is authorized to register the inputed  station (search in cases_regions_users table)
           //--- 1    <begin> -----
        //     $query=DB::table('cases_regions_users')->select('case_id')
        //    ->where('case_id', '=', Input::get('case_id'))
        //    ->where('case_region_id','=' , Input::get('case_region'))     
        //    ->where('user_id','=', Auth::id()) 
        //    ->whereRaw('? between station_id_from and station_id_to',[$station_id])->count();
           $querycount= intval(Station::IsUserStationValid(Input::get('case_id'),Input::get('case_region'), $user->id, $user->group_id,$station_id));
           
           //---- 1  end ------
            //     $query = DB::raw('select 1 as value from cases_regions_users
            //                 where case_id = '. Input::get('case_id') . ' 
            //                 and case_region_id = '.  Input::get('case_region_id') .'
            //                 and user_id='. Auth::id() . '
            //                 and '. Input::get('station_id') . ' between station_id_from and station_id_to' );
                
                // $station= $query->get();    
                // dd(DB::getQueryLog());  
                //  dd($query);
                //  exit;
            if ($querycount==0) {
                $validator->after(function ($validator) {
                        $validator->errors()->add('station_id', trans('messages.ERROR_MESSAGE_STATION_NOT_AUTHORIZED'). ": ".input::get('station_id'));
                        });
           }
        }
        //---- validation rule 3 ---- 
        // if case_type=municipal then do not allow to modify municipality
        // if (input::get('case_type_id')==1) {
        //     $query = DB::table('stations')->select('municipality_id')
        //     ->whereraw('station_id= ? and municipality_id != ? 
        //     and exists (select 1 from cases_regions_users 
        //     where stations.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to)',[$station_id,input::get('municipality_id')])
        //     ->get();

        //     if ($query) {
        //         $validator->after(function ($validator) {
        //                 $validator->errors()->add('station_id', 'Το Εκλογικό Τμήμα που εισηγάγατε ανήκει σε άλλον δήμο.');                       
        //             });
        //     }
        // }


        $error_redirect= '/'. intval(Input::get('case_id')) . '/stations/';
        if (intval(Input::get('id')) > 0) {
            $error_redirect = $error_redirect . intval(Input::get('case_region')) . '/'. intval(Input::get('id')) .'/detail?action_id='.input::get('action_id');
        } else {
            //$error_redirect = 'create';
            $error_redirect = $error_redirect . intval(Input::get('case_region')) . '/'. intval(Input::get('id')) .'/detail?action_id='.input::get('action_id');
        }
        //  echo $error_redirect;
        //  exit;
        if ($validator->fails()) {

            $caseItems = isset($_POST['case_items']) ? $_POST['case_items'] : null;

            return redirect($error_redirect)
                ->withErrors($validator)
                ->withInput()->with('extraParams',['caseItemsVotes'=>$caseItems]);
        } else {
            
            // if (Input::get('station_id') != Input::get('id')) {
            //     $station_id = Input::get('id');
            // }else {
            //     $station_id = Input::get('station_id');
            // }
            // action_id=1(add), 2(edit))
            // check tha station_id>0 has been already performed
            if (Input::get('action_id')==$action_modify_id && Input::get('old_municipality_id')>0 && Input::get('id') > 0 && Input::get('station_id') != Input::get('id')) {
                   
                   // --- with municipality_id
                    DB::table('votes')
                    ->where('case_id',Input::get('case_id'))
                    ->where('municipality_id',Input::get('old_municipality_id'))
                    ->where('station_id', Input::get('id'))
                    ->delete();

                    
                    // --- with municipality_id
                    DB::table('case_items_votes')
                    ->where('case_id',Input::get('case_id'))
                    ->where('municipality_id',Input::get('old_municipality_id'))
                    ->where('station_id', Input::get('id'))
                    ->delete();
                    // dd(DB::getQueryLog());  
                    // dd($query);
                    
                 
            }    

             
            // $stationVote = stationVote::where('case_id', Input::get('case_id') )
            //     ->where('case_region_id',Input::get('case_id') )     
            //     ->where('station_id',Input::get('id') )->first();
            
                
            //     if (isset($stationVote->station_id) && $stationVote->station_id > 0) {
            //          $stationVote->user_id = Auth::id();
            //          $stationVote->station_id = Input::get('id');
            //          $stationVote->case_id = Input::get('case_id');
            //          $stationVote->case_region_id=  Input::get('case_region_id');
            //          $stationVote->status_id =  Input::get('status_id');
            //          $stationVote->registered =  Input::get('registered');
            //          $stationVote->voted = Input::get('voted');
            //          $stationVote->votes_invalid = Input::get('votes_invalid');
            //          $stationVote->votes_blank = Input::get('votes_blank');
            //          $stationVote->votes_valid = Input::get('voted') - Input::get('votes_invalid') - Input::get('votes_blank');
            //          $stationVote->update();

            //     } else {
            //         $stationVote = new stationVote;
            //         $stationVote->user_id = Auth::id();
            //         $stationVote->station_id = Input::get('id');
            //         $stationVote->case_id = Input::get('case_id');
            //         $stationVote->case_region_id=  Input::get('case_region_id');
            //         $stationVote->status_id =  Input::get('status_id');
            //         $stationVote->registered =  Input::get('registered');
            //         $stationVote->voted = Input::get('voted');
            //         $stationVote->votes_invalid = Input::get('votes_invalid');
            //         $stationVote->votes_blank = Input::get('votes_blank');
            //         $stationVote->votes_valid = Input::get('voted') - Input::get('votes_invalid') - Input::get('votes_blank');
            //         $stationVote->save();
            // } 
            //dd($stationVote);
            // with case_region_id
            //    $stationVote = stationVote::firstorNew([
            //     'case_id'=> Input::get('case_id'),
            //     'case_region_id' => Input::get('case_region_id'),     
            //     'station_id'=> Input::get('id')
            //     ]);
                // with case_region_id
            $stationVote = StationVote::firstorNew([
                'case_id'=> Input::get('case_id'),
                'municipality_id' => Input::get('municipality_id'),     
                'station_id'=> Input::get('station_id')
                ]);

            


           $stationVote->user_id = Auth::id();
           $stationVote->station_id = Input::get('station_id');
           $stationVote->case_id = Input::get('case_id');
           $stationVote->municipality_id=  Input::get('municipality_id');
           $votes_mismatch = false;
           $total_votes=0;
           if ($voted != ($votes_invalid + $votes_blank+ $votes_valid)) {
                $votes_mismatch=true;    
            } else {
                // check if total votes mismatch voted
                $total_votes =0;
                /*
                foreach($_POST as $key => $value){
                    //echo ' key:'.$key. ' id:'. substr($key,6,strlen($key)- 6);
                    
                    if (substr($key,0,6) == 'case_item_')  {
                       $total_votes = $total_votes + $value;
                    }                                                   
                 }*/
                 //////////////////////////////
                 $caseItems = isset($_POST['caseitems']) ? $_POST['caseitems'] : null;
                 foreach($caseItems as $value){
                    //echo ' key:'.$key. ' id:'. substr($key,6,strlen($key)- 6);
                       $total_votes = $total_votes + $value;
                 }
                 //////////////////////////////


                 if ($votes_valid != $total_votes ) {
                    $votes_mismatch=true;
                 }
            }

            
            if (input::get('action_id')==$action_add_id || input::get('id') != $station_id ) {
                if ($votes_mismatch) {        
                    $stationVote->status_id = config('app.STATION_STATUS_PROBLEMATIC');
                } else {
                    $stationVote->status_id = config('app.STATION_STATUS_REGISTERED');
                } 
            
            } else {
                if ($votes_mismatch) {        
                    if ($stationVote->status_id > config('app.STATION_STATUS_REGISTERED')) {
                        // idi apestalmeno + problimatiko
                        $stationVote->status_id = config('app.STATION_STATUS_SEND_BUT_PROBLEMATIC');
                    } else {
                        $stationVote->status_id = config('app.STATION_STATUS_PROBLEMATIC');
                    }
                } else {
                    if ($stationVote->status_id == config('app.STATION_STATUS_SENT_BUT_PROBLEMATIC')) {
                        $stationVote->status_id = config('app.STATION_STATUS_EDITED_FOR_RESENT');
                    } else if ($stationVote->status_id == config('app.STATION_STATUS_PROBLEMATIC')) {
                        $stationVote->status_id = config('app.STATION_STATUS_REGISTERED');
                    } else {
                        $stationVote->status_id =  Input::get('status_id');
                    }    
                }
            }

           $stationVote->case_region_id = intval(Input::get('case_region'));
           $stationVote->registered =  Input::get('registered');
           $stationVote->voted = Input::get('voted');
           $stationVote->votes_invalid = Input::get('votes_invalid');
           $stationVote->votes_blank = Input::get('votes_blank');
           $stationVote->votes_invalid_blank = Input::get('votes_invalid_blank');
           //$stationVote->votes_valid = Input::get('voted') - Input::get('votes_invalid') - Input::get('votes_blank');
           $stationVote->votes_valid = Input::get('votes_valid');
           $stationVote->save();
           
            // save case_items votes  
            // $data['case_items']=CaseParty::where('case_id',Input::get('case_id'))->get();
            // foreach ($data['case_items'] as $party) {
            //     $caseItemsVotes = StationCaseItemVote::firstOrNew([
            //         'case_id'=> Input::get('case_id'),
            //     'case_region_id'=>Input::get('case_region_id'),
            //     'station_id'=>Input::get('id'),'case_item_id'=> $party->case_item_id]
            //      );
            //     $caseItemsVotes->user_id = Auth::id();
            //     $caseItemsVotes->votes=Input::get('case_item_id_'.$party->case_item_id);
            //     $case_item_id = 'case_item_id_'.$party->case_item_id;
            //     echo "party " . $case_item_id;
            //     echo "'votes ". $_GET('$party->case_item_id');
            //     $caseItemsVotes->case_id = Input::get('case_id');
            //     $caseItemsVotes->case_region_id= Input::get('case_region_id');
            //    //  print_r($caseItemsVotes);
            //    // $caseItemsVotes->save();
            // }


            /*
            foreach($_POST as $key => $value){
                //echo ' key:'.$key. ' id:'. substr($key,6,strlen($key)- 6);
                if (substr($key,0,6) == 'case_item_')  {
                    $caseItemsVotes = StationCaseItemVote::firstOrNew([
                        'case_id'=> Input::get('case_id'),
                        'municipality_id'=>Input::get('municipality_id'),
                        'station_id'=>Input::get('id'),
                        'case_item_id'=> intval(substr($key,6,strlen($key)- 6))]
                    );
                    $caseItemsVotes->user_id = Auth::id();
                    $caseItemsVotes->case_item_id = intval(substr($key,6,strlen($key)- 6));
                    $caseItemsVotes->votes= $value;
                    $caseItemsVotes->station_id=$station_id;
                    $caseItemsVotes->case_id = Input::get('case_id');
                    $caseItemsVotes->municipality_id= Input::get('municipality_id');
                    $caseItemsVotes->save();
                }                                                   
             }*/

             $caseItems = isset($_POST['caseitems']) ? $_POST['caseitems'] : null;
             foreach($caseItems as $key=>$value) {
                //echo ' key:'.$key. ' id:'. substr($key,6,strlen($key)- 6);
                    $caseItemsVotes = StationCaseItemVote::firstOrNew([
                        'case_id'=> Input::get('case_id'),
                        'municipality_id'=>Input::get('municipality_id'),
                        'station_id'=>Input::get('id'),
                        'case_item_id'=> $key]
                    );
                    $caseItemsVotes->user_id = Auth::id();
                    $caseItemsVotes->case_item_id = $key;
                    $caseItemsVotes->votes= $value;
                    $caseItemsVotes->station_id=$station_id;
                    $caseItemsVotes->case_id = Input::get('case_id');
                    $caseItemsVotes->municipality_id= Input::get('municipality_id');
                    $caseItemsVotes->case_region_id= Input::get('case_region');
                    $caseItemsVotes->save();
                
             }
             
            
            //    
            
            if (intval(Input::get('action_id'))==$action_add_id) {
                //url('controler/action',['case_id'=>1,'action_id'=>5,'case_region_id'=>0,'station_id'=>0']);
                $redirect='/'.intval(Input::get('case_id')) . '/stations/0/0/detail?action_id='.$action_add_id;
                return redirect($redirect)->with('success', trans('messages.station_successfully_added'));        
            } else {
                $redirect= '/'. intval(Input::get('case_id')) . '/stations/';
            //    echo  $redirect;
            //     exit; 
                return redirect($redirect)->with('success', trans('messages.station_successfully_updated'));
            }

        }
    
    }

    public function edit($case_id,$case_region_id,$station_id)
    {

        exit;
        $data = array();

        $user = User::find(Auth::id());
        
        $data['action_id']=2; //edit
        $data['station1'] =  Station::where('case_region_id', '=', $case_region_id)
                ->where('station_id', '=', $station_id)
                ->first();

        // //check permissions
        // switch ($user->group_id) {
        //     case 1:
        //     case 3:
        //         exit;
        //         break;
        //     case 2:
        //        // if ($user->id != $data['pupil']->school_id)
        //          exit;
        //         break;
        //     default:
        //         exit;
        //         break;
        // }

        //      $data['postCodes'] = Road::select('post_code')->distinct()->get();
        //     $data['roads'] = json_decode($this->getRoads($data['pupil']->post_code));
        //      $data['schools'] = Road::select('school_destination')->distinct()->get();
        $user = User::find(Auth::id());
        $data['user'] = $user;
        $data['case_id'] = $case_id;
        $data['case_type_id']=Cases::select('case_type_id')->where('case_id','=',$case_id)->get();
        $data['case_region_id']=$case_region_id;
        $data['station_id'] = $station_id;
        $data['station'] = DB::select("
                select cases.case_id,
                        cases_regions.case_region_id,
                        cases_regions.case_region_name,
                        municipalities.municipality_id,
                        municipalities.municipality_name,
                        stations.station_id, 
                        ifnull( stations_statuses.status_id,-1)  as status_id,
                        ifnull( stations_statuses.status_descr,(select status_descr from stations_statuses where status_id=-1))  as status_descr,
                        ifnull(votes.registered,0) as registered,
                        ifnull(votes.voted,0) as voted,
                        ifnull(votes.votes_invalid,0) as votes_invalid ,
                        ifnull(votes.votes_blank,0) as votes_blank,
                        station_officers.station_officer_name, 
                        if(isNull(station_officers.phone2),station_officers.phone1,concat(station_officers.phone1 ,', ', station_officers.phone2)) as station_officer_phone
                from cases
                cross join stations 
                inner join cases_regions  on stations.case_region_id = cases_regions.case_region_id
                inner join municipalities on stations.municipality_id = municipalities.municipality_id
                left join station_officers on stations.station_officer_id=station_officers.station_officer_id
                left join votes on stations.station_id = votes.station_id
                            and  stations.municipality_id = votes.municipality_id
                            and  cases.case_id= votes.case_id
                left join stations_statuses on votes.status_id = stations_statuses.status_id
                where cases.case_id=". $case_id. '
                and cases_regions.case_region_id ='.$case_region_id.'
                and stations.station_id='.$station_id);
                // echo '<pre>'; print_r($data); echo '</pre>';
                // exit;
        $data['case_items']=DB::select('
                select case_items.case_item_id,  case_items.case_item_name, ifnull(case_items_votes.votes,0) as votes
                from case_items
                left join case_items_votes on case_items.case_id =case_items_votes.case_id and case_items.case_item_id = case_items_votes.case_item_id
                where case_items.case_id='.$case_id.'
                order by case_items.case_item_code'); 
               
        return View::make('stations.edit')->with('data', $data);
    }

    public function destroy()
    {
       
        exit;
        $user = User::find(Auth::id());
        $station = Station::find(intval(Input::get('id')));

        //check permissions
        switch ($user->group_id) {
            case 1:
                exit;
                break;
            case 2:
              //  if ($user->id != $admin)
              //      exit;
              exit  ;
              break;
            case 3:
                exit;
                break;

        }

        $station->delete();
    }

    public function data($case_id)
    {
        $data = new \stdClass();
        $data->rows = array();

         $user = User::find(Auth::id());
        // --- 1 <begin>  ------
        /*
            if ($user->group_id !=1) {
                $query=DB::table('cases')->selectraw("stations.station_id , 
                cases_regions.case_region_id, cases_regions.case_region_name, 
                municipalities.municipality_id, municipalities.municipality_name,users.name as user_name,
                ifnull(`stations_statuses`.`status_id`, -1) as status_id,ifnull(`stations_statuses`.`status_descr` ,'ΜΗ ΚΑΤΑΧΩΡΗΜΕΝΟ') as `status_descr`,
                if(`votes`.`status_id` is null,1,2) as `action_id`,".$user->group_id." as group_id")
                ->crossJoin('stations')
                ->join('cases_regions', 'stations.case_region_id', '=', 'cases_regions.case_region_id')
                ->join('municipalities', 'stations.municipality_id', '=', 'municipalities.municipality_id')
                ->leftJoin('votes', function($join) {
                    $join->on('cases.case_id','=','votes.case_id')
                    ->on('stations.station_id','=','votes.station_id')
                    ->on('stations.municipality_id', '=', 'votes.municipality_id');})
                ->leftJoin('stations_statuses' , 'votes.status_id','=','stations_statuses.status_id')
                ->join('cases_regions_users',  function($join) {
                    $join->on('cases.case_id','=','cases_regions_users.case_id')
                    ->on('stations.case_region_id','=','cases_regions_users.case_region_id');})
                ->join ('users','cases_regions_users.user_id','=','users.id')    
                ->whereRaw('cases.case_id='. $case_id . ' and cases_regions_users.user_id='. $user->id . '
                and `stations`.`station_id` between `cases_regions_users`.`station_id_from` and `cases_regions_users`.`station_id_to`');
            } else {
                $query=DB::table('cases')->selectraw("stations.station_id , 
                cases_regions.case_region_id, cases_regions.case_region_name, 
                municipalities.municipality_id, municipalities.municipality_name,users.name as user_name,
                ifnull(`stations_statuses`.`status_id`, -1) as status_id,ifnull(`stations_statuses`.`status_descr` ,'ΜΗ ΚΑΤΑΧΩΡΗΜΕΝΟ') as `status_descr`,
                if(`votes`.`status_id` is null,1,2) as `action_id`,".$user->group_id." as group_id")
                ->crossJoin('stations')
                ->join('cases_regions', 'stations.case_region_id', '=', 'cases_regions.case_region_id')
                ->join('municipalities', 'stations.municipality_id', '=', 'municipalities.municipality_id')
                ->leftJoin('votes', function($join) {
                    $join->on('cases.case_id','=','votes.case_id')
                    ->on('stations.station_id','=','votes.station_id')
                    ->on('stations.municipality_id', '=', 'votes.municipality_id');})
                ->leftJoin('stations_statuses' , 'votes.status_id','=','stations_statuses.status_id')
                ->join ('users','votes.user_id','=','users.id')    
                ->whereRaw('cases.case_id='. $case_id);
            }
        */
        //DB::enableQueryLog();
        $query = Station::UserStations($case_id,$user->id,$user->group_id);
        //--- 1  end ----
        if (Input::has('search_status')) {
            $station_status_unregistered = config('app.STATION_STATUS_UNREGISTERED');
            if (intval(Input::get('search_status'))==$station_status_unregistered) {
                $query= $query->whereRaw(' not exists (select 1 from votes as votes2 where votes2.case_id=cases.case_id and votes2.case_region_id=stations.case_region_id
                   and votes2.station_id=stations.station_id)');
            }
            
            $query= $query->whereRaw("ifNull(votes.status_id,".$station_status_unregistered.")=".intval(Input::get('search_status')));
        }
        $query= $query->orderBy('cases_regions.case_region_id', 'ASC')->orderBy('stations.station_id', 'ASC');
        
        

        // if (Input::has('search_status') && (intval(Input::get('search_status'))==-1 || intval(Input::get('search_status')) >0 )) {
        //         if (Input::get('sort') && Input::get('order')) {
        //             $query = $query->orderBy('cases_regions.case_region_id' ,'ASC');
        //         //  $query = $query->orderBy('stations_view.' . e(Input::get('sort')), e(Input::get('order')));
        //         } else {
        //         $query = $query->orderBy('cases_regions.case_region_id', 'ASC')->orderBy('stations.station_id', 'ASC');
        //         }
        // }
        // if (Input::get('sort') && Input::get('order')) {
        //     $query = $query->orderBy('cases_regions.case_region_id' ,'ASC');
        // //  $query = $query->orderBy('stations_view.' . e(Input::get('sort')), e(Input::get('order')));
        // } else {
        // $query = $query->orderBy('cases_regions.case_region_id', 'ASC')->orderBy('stations.station_id', 'ASC');
        // }

        
        if (Input::has('search_case_region') && intval(Input::get('search_case_region')) >0 ) {
            $query = $query->whereRaw("cases_regions.case_region_id=?",[intval(Input::get('search_case_region'))]);
            //$query = $query->where("case_region_id_str","=",e(Input::get('search_case_region')));
            //$query = $query->whereRaw("case_region like '%", e(Input::get('search_case_region')). "%'" );
        }
        // echo 'has mun:'. Input::has('search_municipality');
        // echo  'mun_id:'.intval(Input::get('search_municipality'));
        // exit;
        if (Input::has('search_municipality') && intval(Input::get('search_municipality')) >0 ) {
            //$query = $query->whereRaw("municipality_name like '%", e(Input::get('search_municipality')). "%'" );
            //$query = $query->where("municipality_id_str","=",e(Input::get('search_municipality')));
            $query = $query->whereRaw("municipalities.municipality_id=?",[intval(Input::get('search_municipality'))]);
        }
        
        if (Input::has('search_station') && (intval(Input::get('search_station'))>0) ) {
            $query = $query->where('stations.station_id','=', intval(Input::get('search_station')) );
        }

        //if (Input::has('search_status') && intval(Input::get('search_status'))>0) {
            // if (intval(Input::get('search_status'))>0) {
            // $query = $query->whereraw("votes.status_id=" . intval(Input::get('search_status')));           
            // }
            // else if (intval(Input::get('search_status'))==-1)
            // {
            //$query=$query->whereraw("ifNull(votes.status_id,-1)=-1");
         //   }
       // }
       // 
       //if ( Input::has('search_status') && (intval(Input::get('search_status'))==-1 || intval(Input::get('search_status')) >0 )) {
        
        if ( ($case_id>0) && ($user->id >0) &&
        (  Input::has('search_status') || Input::has('search_case_region') || Input::has('search_municipality') || Input::has('search_station') ) ) {
            $data->total = $query->count();
        
            $query= $query->offset(intval(Input::get('offset')))->limit(intval(Input::get('limit')));   

            $stations = $query->get()->all();

            //g echo 'here i am';

             //print_r(DB::getQueryLog());
              //   exit;
            if ($stations) {
                foreach ($stations as $station) {
                    $row = new \stdClass();
                    $row->id = $station->station_id;
                    $row->case_region_id = $station->case_region_id;
                    $row->case_region_name = $station->case_region_name;
                    $row->municipality_id = $station->municipality_id;
                    $row->municipality_name = $station->municipality_name;
                    $row->status_id = $station->status_id;
                    $row->status_descr = $station->status_descr;
                    $row->action_id=$station->action_id;
                    $row->user_name=$station->user_name;
                    
                    $data->rows[] = $row;
                }
            }
        } else {
            $data->total=0;
            reset($data->rows);
        } 
       
        return json_encode($data);
    }

    public function send($case_id,$case_region_id,$id)
    {
        $data['case_id']=$case_id;
        $data['case_region_id']=$case_region_id;
        $data['send_msg_id']= config('app.CASE_STATION_SEND_ID_UNDER_CONSTRUCTION');
        $data['updated']= 0;

        return View::make('Stations.send')->with('data', $data);

    }
    public function isStationValid($case_id,$id)
    {
        $urlParts = explode('^^', $id);
        
        if (is_array($urlParts)) {    
          $user=User::find(Auth::id());
            $query= DB::table('cases_regions_users')->select('case_region_id')
            ->join('stations','cases_regions_users.case_region_id','stations.case_region_id')
            ->where('case_id', '=', $case_id)
            ->where('user_id','=', $user->id ) 
            ->whereRaw('stations.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to
            and  stations.station_id = ?',[intval($urlParts[1])]);
            
            if (intval($urlParts[2]) >0) {
                $query=$query->where('case_region_id','=' , intval($urlParts[2]));     
            }
            // if $urlParts[0]=action=1=add
            if (intval($urlParts[0])==1) {
                $query=$query->whereRaw('and not exists (select 1 from votes where votres.case_id = cases_regions_users.case_id
                                                        and votes.station_id = stations.station_id
                                                        and votes.municipality_id=stations.municipality_id)');
            }
        
        }
        return $query->get->toJson();
        
    }    

    public function isStationValid2($id)
    {
        // offset: 0 (action) 1 (case_id),2(case_region_id),3(station_id)
        $urlParts = explode('^^', $id);
        
        if (is_array($urlParts)) {    
           $user=User::find(Auth::id());
        // -- 1 begin --
        //     $query= DB::table('cases_regions_users')->select('cases_regions_users.case_region_id')
        //     ->join('stations','cases_regions_users.case_region_id','stations.case_region_id')
        //     ->where('case_id', '=', $urlParts[1])
        //     ->whereRaw('stations.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to
        //     and  stations.station_id = ?',[intval($urlParts[3])]);
            
        //     if ($user->group_id !=1) {
        //         $query=$query->where('user_id','=', $user->id ); 
        //     }
        //     // exo thema me ton admin kai an dinei valid or not station_id
        //     if (intval($urlParts[2])>0) {
        //         $query=$query->where('cases_regions_users.case_region_id','=' , intval($urlParts[2]));     
        //     }
            
            $query= Station::CaseRegionUserStations(intval($urlParts[1]),intval($urlParts[2]),$user->id,$user->group_id)
                    ->Station(intval($urlParts[3]));
        // --- 1 end ---

            // if $urlParts[0]=action=1=add
            // 30.10.2018 i put in comment the following if to see tha ajax requests works for all polling stations
            // and because after this check i have put another check if the polling station has been  already registered
            // if (intval($urlParts[0])==1) {
            //     $query=$query->whereRaw('not exists (select 1 from votes where votes.case_id = cases_regions_users.case_id
            //                                             and votes.station_id = stations.station_id
            //                                             and votes.municipality_id=stations.municipality_id)');
            // }
        
        }
        return $query->get()->toJson();       
    }    

    public function hasStationBeenRegistered($id)
    {
        $urlParts = explode('^^', $id);
        // offset 0 (case_id),1(case_region_id),2(station_id)
        if (is_array($urlParts)) {    
          $user=User::find(Auth::id());
            // $query= DB::table('cases')->select('stations.station_id')
            // ->crossjoin('cases_regions')
            // ->join('stations','cases_regions.case_region_id','stations.case_region_id')
            // ->Join('votes', function($join) {
            //     $join->on('cases.case_id','=','votes.case_id')
            //     ->on('stations.station_id','=','votes.station_id')
            //     ->on('stations.municipality_id', '=', 'votes.municipality_id');})
            // ->where('cases.case_id', '=', $urlParts[0])
            // ->where('cases_regions.case_region_id','=' , intval($urlParts[1]))
            // ->where('stations.station_id', '=', $urlParts[2]);
            $query=Station::HasStationBeenRegistered( intval($urlParts[0]),intval($urlParts[1]), intval($urlParts[2]));
        }
        
        return $query->get()->toJson();       
    }    

    public function getStationDetails($case_id,$case_region_id,$station_id)
    {
       $query->Station::StationOfficerDetails($case_id,$case_region_id,$station_id);
    //    $query= DB::table('stations')->selectraw("cases_regions.case_region_id, 
    //     cases_regions.case_region_name, 
    //     municipalities.municipality_id,municipalities.municipality_name,
    //    station_officers.station_officer_name, 
    //     if(isNull(station_officers.phone2),station_officers.phone1,concat(station_officers.phone1 ,', ', station_officers.phone2)) as station_officer_phone")
    //              ->join('cases_regions', 'stations.case_region_id','=','cases_regions.case_region_id')
    //              ->join('municipalities', 'stations.municipality_id','=','municipalities.municipality_id')
    //              ->join('station_officers','stations.station_officer_id','station_officers.station_officer_id')
    //              ->where('station_id','=',$station_id);
    //    if ($case_region_id > 0) {
    //        $query=$query->where('cases_regions.case_region_id','=',$case_region_id);
    //   }

     return $query->get()->toJson();
    }

    public function getStationDetails2($id)
    {
        // offset 0 (case_id),1(case_region_id),2(station_id)
        $urlParts = explode('^^', $id);
        if (is_array($urlParts)) {
                // $query= DB::table('stations')->selectraw("cases_regions.case_region_id, 
                // cases_regions.case_region_name, 
                // municipalities.municipality_id,municipalities.municipality_name,
                // station_officers.station_officer_name, 
                // if(isNull(station_officers.phone2),station_officers.phone1,concat(station_officers.phone1 ,', ', station_officers.phone2)) as station_officer_phone")
                //             ->join('cases_regions', 'stations.case_region_id','=','cases_regions.case_region_id')
                //             ->join('municipalities', 'stations.municipality_id','=','municipalities.municipality_id')
                //             ->leftjoin('station_officers','stations.station_officer_id','station_officers.station_officer_id')
                //             ->where('station_id','=',$urlParts[2])
                //             ->where('cases_regions.case_region_id','=',$urlParts[1]);             
                $query=Station::StationOfficerDetails(intval($urlParts[1]),intval($urlParts[2]));
                return $query->get()->toJson();
        }
        
    }

    public function getMunicipalityDetails($station_id)
    {   
        return Station::MunicipalityDetails($station_id)->get()->toJson();
    }

    public function getCaseRegionDetails($station_id)
    {
        return Station::CaseRegionDetails($station_id)->get()->toJson();
    }


    public function getStations($case_id,$case_region_id)
    {
         $urlParts = explode('^^', $id);
         if (is_array($urlParts)) {
            $user=User::find(Auth::id());
            return DB::table('stations')->select('stations.station_id')
                    ->join('cases_regions_users', 'stations.case_region_id','=','cases_regions_users.case_region_id')
                    ->where('cases_regions_users.case_id','=',$urlParts[0])
                    ->where('cases_regions_users.user_id','=',$user->id)
                    ->whereRaw(' stations.case_region_id=?
                    and stations.station_id between cases_regions_users.station_id_from
                    and cases_regions_users.station_id_to',[$urlParts[1]])->get()->toJson();
         }        
    }

    public function getStations2($id)
    {
        // offset 0 (case_id),1(case_region_id)
        $urlParts = explode('^^', $id);
         if (is_array($urlParts)) {
            $user=User::find(Auth::id());
            $query=Station::CaseRegionStations(intval($urlParts[0]),intval($urlParts[1]),$user->id,$user->group_id);
            // $query= DB::table('stations')->select('stations.station_id');
            // if ($user->group_id !=1) {
            //     $query = $query->join('cases_regions_users', 'stations.case_region_id','=','cases_regions_users.case_region_id')
            //     ->where('cases_regions_users.case_id','=',$urlParts[0])
            //     ->where('cases_regions_users.user_id','=',$user->id)
            //     ->whereraw('stations.station_id between cases_regions_users.station_id_from
            //     and cases_regions_users.station_id_to');
            //  } 
            //     $query=$query->whereRaw(' stations.case_region_id=?',[$urlParts[1]]);      
            return $query->get()->toJson();
         }        
    }


    public function getCaseItems($id)
    {
        // offset 0 (case_id),1(case_region_id),2(station_id)
        $urlParts = explode('^^', $id);
         if (is_array($urlParts)) {
            // return DB::table('stations')
            
            // $query=DB::table('stations')
            // ->selectraw("case_items.case_item_id, case_items.case_item_code, case_items.case_item_name, ifnull(case_items_votes.votes,0) as votes")
            // ->join('municipalities','stations.municipality_id','=','municipalities.municipality_id')
            // ->join('case_items','case_items.ref_id','=','municipalities.municipality_id')
            // ->leftjoin('case_items_votes',  function($join) {
            //     $join->on('case_items.case_id','=','case_items_votes.case_id')
            //     ->on('case_items.case_item_id','=','case_items_votes.case_item_id');})
            // ->where('case_items.case_id','=',$urlParts[0])
            // ->where('stations.case_region_id', '=',$urlParts[1])
            // ->where('stations.station_id','=', $urlParts[2]);
            $case_id = intval($urlParts[0]);
            $case_region_id=intval($urlParts[1]);
            $station_id=   intval($urlParts[2]);
            //DB::enableQueryLog();  
            $query=Station::CaseItemsByCaseRegion($case_id,$case_region_id,$station_id);
            $query=$query->orderBy('case_items.case_item_code');
            //$data = 
            //$laQuery = DB::getQueryLog();
            //$data->toJson();
            //echo '<pre>';var_dump($laQuery);'</pre>';
            //dd($data);
            return json_encode($query->get()->all());
             
         }

    }
}
