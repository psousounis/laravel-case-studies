<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    protected $fillable = ['case_id'];
    protected $table = 'cases';
    protected $primaryKey = 'case_id';
        
    public function type()
    {
        return $this->belongsTo('App\CaseType');
    }

    public function status()
    {
        return $this->belongsTo('App\CaseStatus');
    }
    public function caseitems()
    {
        return $this->hasmany('App\CaseItem');
    }
    public function scopeGetUserCases2($query, $user_id, $user_group_id) {
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        if ($user_group_id != $user_group_id_admin) {
            $status_id_active = config('app.CASE_STATUS_ACTIVE');
            $query ->from('cases_regions_users')
                ->select('cases.case_id')
                ->join('cases', 'cases_regions_users.case_id', '=', 'cases.case_id')
                ->where('user_id', '=', $user_id)
                ->whereRaw('cases.status_id=' . $status_id_active)
                ->distinct();
        } else {
            $query->Case::select('case_id');
        }
        return $query;
    }
    public function scopeUserCaseTypes($query, $user_id, $user_group_id) {
        $status_id_active = config('app.CASE_STATUS_ACTIVE');
		$query->from('cases_types');
        $query->join('cases', 'cases.case_type_id', '=', 'cases_types.case_type_id');
        $query->select('cases_types.case_type_id', 'cases_types.case_type_descr');
        $query->where('cases.status_id','=', $status_id_active);	             
        if ($user_group_id != $this->user_group_id_admin) {
            $query->whereraw('cases.case_id in (select distinct case_id from `cases_regions_users` where user_id = ?)',[$user_id]);
        }
        return $query;
    }
    
    public function scopeUserCases($query, $user_id,$user_group_id) {
        $status_id_active = config('app.CASE_STATUS_ACTIVE');
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        $query->from('cases');
        //$query->join('cases_statuses', 'cases.status_id', '=', 'cases_statuses.status_id');
        $query->join('cases_types', 'cases.case_type_id', '=', 'cases_types.case_type_id');
		$query ->select('cases.case_id' , 'cases.case_descr', 'cases.case_date','cases_types.case_type_descr');
        $query ->where('cases.status_id','=', $status_id_active);
        
        if ($user_group_id != $user_group_id_admin) {
            $query->whereraw('cases.case_id in (select distinct case_id from `cases_regions_users` where user_id = ?)',[$user_id]);
        }
        return $query;
    }
    
}

