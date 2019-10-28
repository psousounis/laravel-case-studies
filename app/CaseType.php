<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseType extends Model
{
    protected $fillable = ['case_type_id'];
    protected $table = 'cases_types';
    protected $primaryKey = 'case_type_id';

    public function cases()
    {
        return $this->hasMany('App\Case');
    }

    public function scopeUserCaseTypes($query, $user_id, $user_group_id) {
        $status_id_active = config('app.CASE_STATUS_ACTIVE');
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
		$query->from('cases_types');
        $query->join('cases', 'cases.case_type_id', '=', 'cases_types.case_type_id');
        $query->select('cases_types.case_type_id', 'cases_types.case_type_descr');
        $query->where('cases.status_id','=', $status_id_active);
        if ($user_group_id != $user_group_id_admin) {
            $query->whereraw('cases.case_id in (select distinct case_id from `cases_regions_users` where user_id = ?)',[$user_id]);
        }
        return $query;
    }
}
