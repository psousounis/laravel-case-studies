<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Station extends Model
{
    protected $fillable = ['station_id'];
    protected $primaryKey = 'station_id';
    protected $table = 'stations';

    public function municipality()
    {
        return $this->belongsTo('App\Municipality');
    }

    public function caseregion()
    {
        return $this->belongsTo('App\CaseRegion');
     }
    // public function pxxxxxxxx()
    // {
    //     return $this->hasmany('App\xxxxx');
    // }
    public function scopeDetail($query, $case_id, $case_region_id,$station_id) {
        //$user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        $query->from('cases')
                ->select(db::raw('cases.case_id,
                        cases_regions.case_region_id,
                        cases_regions.case_region_name,
                        municipalities.municipality_id,
                        municipalities.municipality_name,
                        stations.station_id, 
                        ifnull( stations_statuses.status_id,-1)  as status_id,
                        ifnull( stations_statuses.status_descr,(select status_descr from stations_statuses where status_id=-1) )  as status_descr,
                        ifnull(votes.registered,0) as registered,
                        ifnull(votes.voted,0) as voted,
                        ifnull(votes.votes_invalid,0) as votes_invalid ,
                        ifnull(votes.votes_valid,0) as votes_valid,
                        ifnull(votes.votes_blank,0) as votes_blank,
                        ifnull(votes.votes_invalid_blank,0) as votes_invalid_blank,
                        users.name as user,
                        stations_officers.station_officer_name, 
                        if(isNull(stations_officers.phone2),stations_officers.phone1,concat(stations_officers.phone1 ,\' | \', stations_officers.phone2)) as station_officer_phone'))
                ->join('cases_regions','cases.case_id','=','cases_regions.case_id')
                ->join('stations','stations.case_region_id','=','cases_regions.case_region_id')
               ->join('municipalities','stations.municipality_id','=','municipalities.municipality_id')
                ->leftjoin('stations_officers','stations.station_officer_id','=','stations_officers.station_officer_id')
                ->leftjoin('votes', function($join){
                            $join->on('stations.station_id' ,'=', 'votes.station_id');
                            $join->on('stations.municipality_id','=','votes.municipality_id');
                            $join->on('cases.case_id','=','votes.case_id');})
                ->leftjoin('users','votes.user_id','=','users.id')
                ->leftjoin('stations_statuses','votes.status_id','=','stations_statuses.status_id')
                ->where('cases.case_id','=',$case_id)
                ->where('cases_regions.case_region_id','=',$case_region_id)
                ->where('stations.station_id','=',$station_id);

                    
        return $query;
    }
    
    public function scopeValidElectionParam($query, $case_id, $user_id,$user_group_id) {
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        $status_id_active = config('app.CASE_STATUS_ACTIVE'); 
        if ($user_group_id != $user_group_id_admin) {              
            $query->from('cases_regions_users');
            $query->join('cases', 'cases.case_id','=','cases_regions_users.case_id');
            $query->select('cases_regions_users.case_id');
            $query->where('cases_regions_users.case_id','=',$case_id);
            $query->where('cases.status_id','=',$status_id_active);
            $query->where('cases_regions_users.user_id','=',$user_id);
        } else {
            $query->from('cases');
            $query->select('case_id');
            $query->where('case_id','=',$case_id);
            $query->where('status_id','=', $status_id_active);
        }    
        return $query;
    }
    public function scopeValidCaseParam($query, $case_id, $user_id, $user_group_id)
    {
        $user_group_id_admin = config('app.USER_GROUP_ID_ADMIN');
        $status_id_active = config('app.CASE_STATUS_ACTIVE');
        if ($user_group_id != $user_group_id_admin) {
            $query->from('cases_regions_users');
            $query->join('cases', 'cases.case_id', '=', 'cases_regions_users.case_id');
            $query->select('cases_regions_users.case_id');
            $query->where('cases_regions_users.case_id', '=', $case_id);
            $query->where('cases.status_id', '=', $status_id_active);
            $query->where('cases_regions_users.user_id', '=', $user_id);
        } else {
            $query->from('cases');
            $query->select('case_id');
            $query->where('case_id', '=', $case_id);
            $query->where('status_id', '=', $status_id_active);
        }
        return $query;
    }

    public function scopeValidCaseParameters($query, $case_id, $case_region_id,$station_id,$user_id,$user_group_id) {
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        $status_id_active = config('app.CASE_STATUS_ACTIVE'); 
        if ($user_group_id != $user_group_id_admin) {
            $query->from('cases_regions');
            $query->select("cases_regions_users.case_id");
            $query->join('cases', 'cases.case_id','=','cases_regions.case_id');
            $query->join('cases_regions_users', function($join) {
                $join->on('cases_regions.case_id','=','cases_regions_users.case_id')
                    ->on('cases_regions.case_region_id','=','cases_regions_users.case_region_id');});
            $query->where('cases_regions.case_id','=',$case_id);
            $query->where('cases_regions_users.user_id','=',$user_id);
            $query->where('cases.status_id','=',$status_id_active);
            if ($case_region_id >0 || $station_id>0) {
                $query= $query->where('cases_regions.case_region_id','=',$case_region_id);
            }
            if ($station_id>0) {    
                $query= $query->whereraw('( ( ( ? between cases_regions_users.station_id_from and cases_regions_users.station_id_to) 
                                                and (cases_regions_users.station_id_from is not null)
                                                and (cases_regions_users.station_id_to is not null)) 
                                         or ( (? between cases_regions.station_id_from and cases_regions.station_id_to) 
                                                and (cases_regions_users.station_id_from is null) 
                                                and (cases_regions_users.station_id_to is null)))',[$station_id,$station_id]);
            }
        } else {
            $status_id_active = config('app.CASE_STATUS_ACTIVE'); 
            $query->from('cases');
            $query->select('case_id');
            $query->where('status_id','=', $status_id_active);

        }  
        return $query;  
    }
    
    public function scopeCaseItemsByMunicipality($query, $case_id, $station_id,$municipality_id) {
        if ($station_id >0 && $case_region_id>0) {
            $query->from('cases_items');
            $query->select(db::raw('cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, ifnull(cases_items_votes.votes,0) as votes'));
            $query->leftjoin('cases_items_votes', function($join) {
                $join->on('cases_items.case_id','=','cases_items_votes.case_id')
                ->on('cases_items.case_item_id','=','cases_items_votes.case_item_id');});
            $query->leftjoin('stations', function($join) {
                $join->on('cases_items_votes.station_id', '=','stations.station_id')
                ->on('cases_items_votes.municipality_id','=','stations.municipality_id');});
            $query->where('cases_items.case_id','=',$case_id);
            $query->where('stations.municipality_id','=',$municipality_id);
            $query->where('stations.station_id','=',$station_id);  
        } else {
            $query->from('cases_items');
            $query->select('cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, 0 as votes');
            $query->where('cases_items.case_id','=',$case_id);
        }
        return $query;
    } 

    public function scopeCaseItemsByCaseRegion($query, $case_id, $case_region_id,$station_id) {
        if ($station_id >0 && $case_region_id>0) {
            $query->from('stations')
            ->select(db::raw('cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, 0 as votes'))
            ->join('cases_items','cases_items.ref_id','=','stations.municipality_id')
            ->where('cases_items.case_id','=',$case_id)
            ->where('stations.case_region_id','=',$case_region_id)
            ->where('stations.station_id','=',$station_id);
            
        } else {
            $query->from('cases_items');
            $query->select(db::raw('cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, 0 as votes'));
            $query->where('cases_items.case_id','=',$case_id);
        }
        return $query;
    }    
    public function scopeCaseItemsVotesByCaseRegion($query, $case_id, $case_region_id,$station_id,$action_id) {
        // $query->from('stations');
        // $query->select(db::raw('cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, ifnull(cases_items_votes.votes,0) as votes'));
        // $query->join('municipalities','stations.municipality_id','=','municipalities.municipality_id');
        // $query->join('cases_items','cases_items.ref_id','=','municipalities.municipality_id');
        // $query->leftjoin('cases_items_votes',  function($join) {
        //     $join->on('cases_items.case_id','=','cases_items_votes.case_id')
        //     ->on('cases_items.case_item_id','=','cases_items_votes.case_item_id')
        //     ->on('municipalities.municipality_id','=','cases_items_votes.municipality_id');});
        // $query->where('cases_items.case_id','=',$case_id);
        // $query->where('stations.case_region_id', '=',$case_region_id);
        // $query->where('stations.station_id','=',$station_id);
        if ($station_id >0 && $case_region_id>0 && $action_id!=1) {
            // $query->DB::select('
            // select cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, ifnull(cases_items_votes.votes,0) as votes
            // from cases_items
            // left join cases_items_votes on cases_items.case_id =cases_items_votes.case_id and cases_items.case_item_id = cases_items_votes.case_item_id
            // left join stations on  cases_items_votes.station_id = stations.station_id 
            // and cases_items_votes.municipality_id = stations.municipality_id
            // where cases_items.case_id='.$case_id.'
            // and stations.case_region_id ='.$case_region_id.'
            // and stations.station_id='.$station_id .'
            // order by cases_items.case_item_id');
            $query->from('cases_items');
            $query->select(db::raw('cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, ifnull(cases_items_votes.votes,0) as votes'));
            $query->leftjoin('cases_items_votes',  function($join) {
                $join->on('cases_items.case_id','=','cases_items_votes.case_id')
                ->on('cases_items.case_item_id','=','cases_items_votes.case_item_id');});
            $query->leftjoin('stations',  function($join) {
                $join->on('cases_items_votes.station_id','=','stations.station_id')
                ->on('cases_items_votes.municipality_id','=','stations.municipality_id');});           
            $query->where('cases_items.case_id','=',$case_id);
            $query->where('stations.case_region_id', '=',$case_region_id);
            $query->where('stations.station_id','=',$station_id);
        } else {
            // $query->DB::select('
            // select cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, 0 as votes
            // from cases_items
            // where cases_items.case_id='.$case_id.'
            // order by cases_items.case_item_code');
            $query->from('cases_items');
            $query->select(db::raw('cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, 0 as votes'));
            $query->where('cases_items.case_id','=',$case_id);
            $query->orderBy('cases_items.case_item_code');
        }
        return $query;
    }

    public function scopeMunicipalCaseItemsVotesByCaseRegion($query, $case_id, $case_region_id,$station_id) {
        $query->from('stations');
        $query->select(db::raw('cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, ifnull(cases_items_votes.votes,0) as votes'));
        $query->join('municipalities','stations.municipality_id','=','municipalities.municipality_id');
        $query->join('cases_items','cases_items.ref_id','=','municipalities.municipality_id');
        $query->leftjoin('cases_items_votes', function($join) {
            $join->on('cases_items.case_id','=','cases_items_votes.case_id')
            ->on('cases_items.case_item_id' ,'=','cases_items_votes.case_item_id')
            ->on('cases_items.ref_id','=','cases_items_votes.municipality_id');});
        $query->where('cases_items.case_id','=',$case_id);
        $query->where('stations.station_id','=',$station_id);
        $query->where('stations.case_region_id', '=',$case_region_id);
        return $query;
    }

    public function scopeMunicipalCaseItemsVotesByStationDetails($query, $case_id,$case_region_id,$station_id) {
        $query->from('stations');
        $query->select('cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, ifnull(cases_items_votes.votes,0) as votes');
        $query->join('municipalities','stations.municipality_id','=','municipalities.municipality_id');
        $query->join('cases_items','cases_items.ref_id','=','municipalities.municipality_id');
        $query->leftjoin('cases_items_votes', function($join) {
            $join->on('cases_items.case_id','=','cases_items_votes.case_id')
            ->on('cases_items.case_item_id' ,'=','cases_items_votes.case_item_id')
            ->on('cases_items.ref_id','=','cases_items_votes.municipality_id');});
        $query->where('cases_items.case_id','=',$case_id);
        $query->where('stations.station_id','=',$station_id);
        $query->where('stations.case_region_id', '=',$case_region_id);
        return $query;
    }
    public function scopeMunicipalCaseItemsVotesByMunicipality($query,$case_id, $municipality_id,$station_id) {
        $query->from('stations');
        $query->select('cases_items.case_item_id, cases_items.case_item_code, cases_items.case_item_name, ifnull(cases_items_votes.votes,0) as votes');
        $query->join('cases_items','cases_items.ref_id','=','stations.municipality_id');
        $query->leftjoin('cases_items_votes', function($join) {
            $join->on('cases_items.case_id','=','cases_items_votes.case_id')
            ->on('cases_items.case_item_id' ,'=','cases_items_votes.case_item_id')
            ->on('cases_items.ref_id','=','cases_items_votes.municipality_id');});
        $query->where('cases_items.case_id','=',$case_id);
        $query->where('stations.station_id','=',$station_id);
        $query->where('stations.municipality', '=',$municipality_id);
        return $query;
    }
    public function scopeCaseRegionUserStations($query, $case_id, $case_region_id,$user_id,$user_group_id) {
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        $query->from('stations');
        $query->select('stations.station_id');
        if ($user_group_id != $user_group_id_admin) {
            $query->join('cases_regions','stations.case_region_id','=','cases_regions.case_region_id');
            $query->join('cases_regions_users', function($join) {
               $join->on('cases_regions.case_id','=','cases_regions_users.case_id')
                ->on('cases_regions.case_region_id','=','cases_regions_users.case_region_id');});
            $query->where('cases_regions_users.case_id','=',$case_id);
            $query->where('cases_regions_users.user_id','=',$user_id);
            $query->whereRaw('stations.case_region_id='.$case_region_id.'
                            and (  ( (stations.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to) 
                                   and  (cases_regions_users.station_id_from is not null and cases_regions_users.station_id_to is not null) ) or 
                                ( (stations.station_id between cases_regions.station_id_from  and cases_regions.station_id_to)
                                and ( cases_regions_users.station_id_from is null and cases_regions_users.station_id_to is null) ) )');
        } else {
            $query->where('stations.case_region_id','=',$case_region_id);
        }
        return $query;
    }    

    public function scopeUserStationsAnalysis($query, $case_id, $user_id,$user_group_id) {
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        $user_group_id_supervisor=config('app.USER_GROUP_ID_SUPERVISOR');
        $user_group_id_register=config('app.USER_GROUP_ID_REGISTER');
        //  select stations_statuses.status_id, count(votes.station_id)
        //from cases
        //cross join stations_statuses
        //inner join cases_regions on cases.case_id=cases_regions.case_id
        //inner join cases_regions_users on cases_regions.case_id =cases_regions_users.case_id and cases_regions.case_region_id = cases_regions_users.case_region_id
        //        and cases_regions_users.user_id=2
        //left join votes on cases.case_id=votes.case_id and cases_regions.case_region_id=votes.case_region_id and votes.status_id = stations_statuses.status_id
        //        -- and votes.user_id = cases_regions_users.user_id
        //where cases.case_id=1
        //        and ( ( (cases_regions_users.station_id_from is not null and cases_regions_users.station_id_to is not null)
        //                           and (votes.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to))
        //                         or ( ( cases_regions_users.station_id_from is null and cases_regions_users.station_id_to is null)
        //                         and (votes.station_id between cases_regions.station_id_from and cases_regions.station_id_to)
        //								 or  ( votes.station_id  is null)
        // ))
        if ($user_group_id == $user_group_id_register or $user_group_id == $user_group_id_supervisor) {
            $query->from('cases_regions_users');
            $query->select(db::raw('cases_regions.case_region_id, cases_regions.case_region_name,
            if ( (cases_regions_users.station_id_from is null) and (cases_regions_users.station_id_to is null),
            (cases_regions.station_id_to-cases_regions.station_id_from+1),
            (cases_regions_users.station_id_to-cases_regions_users.station_id_from+1)) as total_stations,
            if ( (cases_regions_users.station_id_from is null) and (cases_regions_users.station_id_to is null), cases_regions.station_id_from, cases_regions_users.station_id_from) as station_id_from, 
            if ( (cases_regions_users.station_id_from is null) and (cases_regions_users.station_id_to is null), cases_regions.station_id_to, cases_regions_users.station_id_to) as station_id_to'));
            $query->join('cases_regions', function($join) {
                $join->on('cases_regions_users.case_id','=','cases_regions.case_id')
                  ->on('cases_regions_users.case_region_id','=','cases_regions.case_region_id');});

            if ($user_group_id == $user_group_id_register) {
                $query->leftjoin('votes', function($join) {
                    $join->on('cases_regions.case_id','=','votes.case_id')
                        ->on('cases_regions.case_region_id','=','votes.case_region_id')
                        ->on('cases_regions_users.user_id','=','votes.user_id');});
            } else {
                $query->leftjoin('votes', function($join) {
                    $join->on('cases_regions_users.case_id','=','votes.case_id')
                        ->on('cases_regions.case_region_id','=','votes.case_region_id');});
            }
            $query->whereraw('cases_regions_users.case_id=? and cases_regions_users.user_id=?
            and ( ( (cases_regions_users.station_id_from is not null and cases_regions_users.station_id_to is not null)
                   and (votes.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to))
                 or ( ( cases_regions_users.station_id_from is null and cases_regions_users.station_id_to is null) 
                 and (votes.station_id between cases_regions.station_id_from and cases_regions.station_id_to)) )',
                [$case_id,$user_id]);
        } else {
            $query->select(db::raw('cases_regions.case_region_id, cases_regions.case_region_name,
            count(stations.station_id) as total_stations,
            min(stations.station_id) as station_id_from,
            max(stations.station_id) as station_id_to'));
            $query->from('stations');
            $query->join('cases_regions', 'stations.case_region_id','=','cases_regions.case_region_id');
            $query->where('cases_regions.case_id','=', $case_id);
            $query->groupBy('cases_regions.case_region_id', 'cases_regions.case_region_name');
        }
        return $query;
    }

    public function scopeExistingStationsPerStatus($query, $case_id, $user_id,$user_group_id) {
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        $user_group_id_register = config('app.USER_GROUP_ID_REGISTER');
        $user_group_id_supervisor = config('app.USER_GROUP_ID_SUPERVISOR');

    //                from cases
    //        cross join stations_statuses
    //        left join votes
    //        left join stations on votes.station_id = stations.station_id and  votes.municipality_id=stations.municipality_id

//        $query=DB::raw(select stations_statuses.status_id, count(votes.station_id) as total_rows
//    from cases
//    cross join stations_statuses )

        //  select stations_statuses.status_id, count(votes.station_id)
        //from cases
        //cross join stations_statuses
        //inner join cases_regions on cases.case_id=cases_regions.case_id
        //inner join cases_regions_users on cases_regions.case_id =cases_regions_users.case_id and cases_regions.case_region_id = cases_regions_users.case_region_id
        //        and cases_regions_users.user_id=2
        //left join votes on cases.case_id=votes.case_id and cases_regions.case_region_id=votes.case_region_id and votes.status_id = stations_statuses.status_id
        //        -- and votes.user_id = cases_regions_users.user_id
        //where cases.case_id=1
        //        and ( ( (cases_regions_users.station_id_from is not null and cases_regions_users.station_id_to is not null)
        //                           and (votes.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to))
        //                         or ( ( cases_regions_users.station_id_from is null and cases_regions_users.station_id_to is null)
        //                         and (votes.station_id between cases_regions.station_id_from and cases_regions.station_id_to)
        //								 or  ( votes.station_id  is null)
        // ))
        //group by stations_statuses.status_id
        //order by stations_statuses.status_id
        $query->from('cases');
        $query->crossjoin('stations_statuses');
        $query->join('cases_regions','cases.case_id','=','cases_regions.case_id');
        if ($user_group_id == $user_group_id_register or $user_group_id == $user_group_id_supervisor) {
            $query->join('cases_regions_users', function ($join) {
                $join->on('cases_regions.case_id', '=', 'cases_regions_users.case_id')
                     ->on('cases_regions.case_region_id', '=', 'cases_regions_users.case_region_id');
            });
        }
        if ($user_group_id == $user_group_id_register) {
            $query->leftjoin('votes', function($join) {
                $join->on('cases.case_id','=','votes.case_id')
                    ->on('cases_regions.case_region_id','=','votes.case_region_id')
                    ->on('stations_statuses.status_id','=','votes.status_id')
                    ->on('cases_regions_users.user_id','=','votes.user_id');});
        } else {
            $query->leftjoin('votes', function($join) {
            $join->on('cases.case_id','=','votes.case_id')
                ->on('cases_regions.case_region_id','=','votes.case_region_id')
                ->on('stations_statuses.status_id','=','votes.status_id');});
        }

        $query->select(db::raw('stations_statuses.status_id, count(votes.status_id) as total_rows'));

        $query->where('cases.case_id','=', $case_id);
        if ($user_group_id == $user_group_id_register or $user_group_id == $user_group_id_supervisor) {
            $query->where('cases_regions_users.user_id','=', $user_id);
            $query->whereraw('( ( (cases_regions_users.station_id_from is not null and cases_regions_users.station_id_to is not null)
                                   and (votes.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to))
                                 or ( ( cases_regions_users.station_id_from is null and cases_regions_users.station_id_to is null)
                                 and (votes.station_id between cases_regions.station_id_from and cases_regions.station_id_to))
        						 or  ( votes.station_id  is null))');
        }


        $query->groupBy('stations_statuses.status_id');
        $query->orderBy('stations_statuses.status_id');
        return $query;
    }

    public function scopeNotRegistered($query, $case_id, $user_id,$user_group_id) {
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        if ($user_group_id != $user_group_id_admin) {
            $query->from('stations_not_registered_view');
            $query->select(db::raw('status_id, count(station_id) as total_rows')); 
            $query->where('case_id','=', $case_id);
            $query->where('user_id','=',$user_id);
            $query->groupBy('status_id');
        } else {
            $query->from('cases');
            $query->select(db::raw('-1 as status_id, count(stations.station_id) as total_rows'));
            $query->join('cases_regions','cases_regions.case_id','=','cases.case_id');
            $query->join('stations', 'cases_regions.case_region_id','=','stations.case_region_id');
            $query->where('cases.case_id','=', $case_id);
            $query->whereraw('not exists  (select 1 from votes where votes.case_id=cases.case_id 
                                and votes.municipality_id=stations.municipality_id
                                and votes.station_id=stations.station_id)');
        }
        return $query;
    }

    public function scopeTotalStations($query, $case_id, $user_id, $user_group_id) {
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        if ($user_group_id != $user_group_id_admin) {
            $query->from('cases_regions');
            $query->select(DB::raw(' sum( if(`cases_regions_users`.`station_id_from` is null,
                    (`cases_regions`.`station_id_to`-`cases_regions`.`station_id_from`+1), 
             (`cases_regions_users`.`station_id_to`-`cases_regions_users`.`station_id_from`+1) ) ) as total_rows '));
            $query->join('cases_regions_users',function($join) {
                $join->on('cases_regions.case_id','=','cases_regions_users.case_id')
                    ->on('cases_regions.case_region_id','=','cases_regions_users.case_region_id');});
            $query->where('cases_regions.case_id','=',$case_id);
            $query->where('user_id','=', $user_id);
        } else {
            $query->from('stations');
            $query->select(db::raw('count(station_id) as total_rows'));
        }
        return $query;
    }
    public function scopeMunicipalities($query, $case_id, $user_id, $user_group_id) {
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        $query->from('municipalities');
        $query->select('municipalities.municipality_id', 'municipalities.municipality_name');
        if ($user_group_id != $user_group_id_admin) {
            $query->join('cases_regions','municipalities.case_region_id','=','cases_regions.case_region_id');
            $query->join('cases_regions_users',function($join) {
                $join->on('cases_regions.case_id','=','cases_regions_users.case_id')
                    ->on('cases_regions.case_region_id','=','cases_regions_users.case_region_id');});
            $query->join('stations','municipalities.municipality_id','=','stations.municipality_id');
            $query->whereraw('cases_regions_users.case_id=? and cases_regions_users.user_id=?
                    and ( ( (cases_regions_users.station_id_from is not null and cases_regions_users.station_id_to is not null)
                           and (stations.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to))
                         or ( ( cases_regions_users.station_id_from is null and cases_regions_users.station_id_to is null) 
                         and (stations.station_id between cases_regions.station_id_from and cases_regions.station_id_to)) )',
                    [$case_id,$user_id]);
            $query->distinct();        
        }
        return $query;
    }    
    public function scopeCaseRegions($query, $case_id, $user_id, $user_group_id) {
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        $query->from('cases_regions');
        $query->select('cases_regions.case_region_id', 'cases_regions.case_region_name');
        if ($user_group_id != $user_group_id_admin) {
            $query->join('cases_regions_users', function($join) {
                $join->on('cases_regions.case_id','=','cases_regions_users.case_id')
                    ->on('cases_regions.case_region_id','=','cases_regions_users.case_region_id');});
            $query->where('cases_regions.case_id','=', $case_id);
            $query->where('cases_regions_users.user_id','=', $user_id);
            $query->distinct();
        } else {
            $query->where('cases_regions.case_id','=', $case_id);
        }
        return $query;
    }
    public function scopeCaseRegionStations($query,$case_id,$case_region_id,$user_id,$user_group_id) {
        $query->from('stations');
        $query->select('stations.station_id');
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        if ($user_group_id != $user_group_id_admin) {
                $query=$query->join('cases_regions','stations.case_region_id','=','cases_regions.case_region_id');
                $query = $query->join('cases_regions_users', function($join) {
                    $join->on('cases_regions.case_id','=','cases_regions_users.case_id')
                        ->on('cases_regions.case_region_id','=','cases_regions_users.case_region_id');})
                ->where('cases_regions_users.case_id','=',$case_id)
                ->where('cases_regions_users.user_id','=',$user_id)
                ->whereraw('((cases_regions_users.station_id_from is not null) 
                              and (cases_regions_users.station_id_to is not null)
                              and ( stations.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to)
                             or ( (cases_regions_users.station_id_from is null) 
                              and (cases_regions_users.station_id_to is null)
                              and ( stations.station_id between cases_regions.station_id_from and cases_regions.station_id_to)) )');
        } 
        $query->whereRaw('stations.case_region_id=?',$case_region_id);
        return $query;
    }
    public function scopeIsUserStationValid($query,$case_id,$case_region_id, $station_id,$user_id,$user_group_id) {
        $query->CaseRegionUserStations($case_id,$case_region_id,$user_id,$user_group_id);
        $query->where('stations.station_id','=',$station_id);
        $count=$query->count();
        $nReturn = 1;
        if ( $count==0) {
            $bReturn = 0;
        } else {
        }
        return $nReturn;
    }
    public function scopeStation($query,$station_id) {
        $query->where('stations.station_id','=',$station_id);
        return $query;
    }
    public function scopeStationByCaseRegion($query,$case_region_id, $station_id) {
        $query->where('stations.station_id','=',$station_id);
        $query->where('stations.case_region_id','=',$case_region_id);
        return $query;
    }

    public function scopeHasStationBeenRegistered($query,$case_id, $case_region_id, $station_id) {
        $query->from('cases');
        $query->join('cases_regions','cases.case_id','=','cases_regions.case_id');
        $query->select('stations.station_id');
        $query->join('stations', 'cases_regions.case_region_id','=','stations.case_region_id');
        $query->Join('votes', function($join) {
                $join->on('cases.case_id','=','votes.case_id')
                ->on('stations.station_id','=','votes.station_id')
                ->on('stations.case_region_id', '=', 'votes.case_region_id')
                ->on('stations.municipality_id', '=', 'votes.municipality_id');});
        $query->where('cases.case_id', '=', $case_id);
        $query->where('cases_regions.case_region_id','=' , $case_region_id);
        $query->where('stations.station_id', '=', $station_id);
        return $query;
    }
    public function scopeVotes($query,$case_id,$municipality_id,$station_id) {
        $query->from('votes');
        $query->select('station_id');
        $query->where('case_id','=',$case_id);
        $query->where('station_id','=',$station_id);
        $query->where('municipality_id','=',$municipality_id);
        return $query;
    }
    public function scopeUserStations($query, $case_id,$user_id, $user_group_id) {
        $status_id_active = config('app.CASE_STATUS_ACTIVE'); 
        $user_group_id_admin= config('app.USER_GROUP_ID_ADMIN');
        $user_group_id_register= config('app.USER_GROUP_ID_REGISTER');
        $user_group_id_supervisor=config('app.USER_GROUP_ID_SUPERVISOR');
        //$query=DB::table('cases')
        //  select stations_statuses.status_id, count(votes.station_id)
        //from cases
        //cross join stations_statuses
        //inner join cases_regions on cases.case_id=cases_regions.case_id
        //inner join cases_regions_users on cases_regions.case_id =cases_regions_users.case_id and cases_regions.case_region_id = cases_regions_users.case_region_id
        //        and cases_regions_users.user_id=2
        //left join votes on cases.case_id=votes.case_id and cases_regions.case_region_id=votes.case_region_id and votes.status_id = stations_statuses.status_id
        //        -- and votes.user_id = cases_regions_users.user_id
        //where cases.case_id=1
        //        and ( ( (cases_regions_users.station_id_from is not null and cases_regions_users.station_id_to is not null)
        //                           and (votes.station_id between cases_regions_users.station_id_from and cases_regions_users.station_id_to))
        //                         or ( ( cases_regions_users.station_id_from is null and cases_regions_users.station_id_to is null)
        //                         and (votes.station_id between cases_regions.station_id_from and cases_regions.station_id_to)
        //								 or  ( votes.station_id  is null)
        // ))
        $query->from('cases')
        ->selectraw("stations.station_id , 
        cases_regions.case_region_id, cases_regions.case_region_name, 
        municipalities.municipality_id, municipalities.municipality_name,users.name as user_name1,
        if (votes.status_id is null,  ( select group_name  from cases_regions_users_groups where group_id = cases_regions.case_region_user_group_id ), users.name) as user_name,
        ifnull(`stations_statuses`.`status_id`, -1) as status_id,ifnull(`stations_statuses`.`status_descr` ,(select status_descr from stations_statuses where status_id=-1)) as `status_descr`,
        if(`votes`.`status_id` is null,1,2) as `action_id`,".$user_group_id." as group_id")
        ->join('cases_regions','cases.case_id','=','cases_regions.case_id')
        ->Join('stations','cases_regions.case_region_id', '=', 'stations.case_region_id')
        ->join('municipalities', 'stations.municipality_id', '=', 'municipalities.municipality_id');
        if ($user_group_id == $user_group_id_register or $user_group_id == $user_group_id_supervisor) {
            $query->join('cases_regions_users', function($join) {
                $join->on('cases_regions.case_id','=','cases_regions_users.case_id')
                    ->on('cases_regions.case_region_id','=','cases_regions_users.case_region_id');});
        }

        if ($user_group_id == $user_group_id_register) {
            $query=$query->leftJoin('votes', function ($join) {
                $join->on('cases.case_id', '=', 'votes.case_id')
                    ->on('stations.case_region_id', '=', 'votes.case_region_id')
                    ->on('stations.station_id', '=', 'votes.station_id')
                    ->on('cases_regions_users.user_id', '=', 'votes.user_id')
                    ->on('stations.municipality_id', '=', 'votes.municipality_id');});

        } else{
            $query=$query->leftJoin('votes', function ($join) {
                $join->on('cases.case_id', '=', 'votes.case_id')
                    ->on('stations.case_region_id', '=', 'votes.case_region_id')
                    ->on('stations.station_id', '=', 'votes.station_id')
                    ->on('stations.municipality_id', '=', 'votes.municipality_id');});
        }
        $query=$query->leftJoin('stations_statuses', 'votes.status_id', '=', 'stations_statuses.status_id');
        $query=$query->leftJoin ('users','votes.user_id','=','users.id');
        if ($user_group_id == $user_group_id_register or $user_group_id == $user_group_id_supervisor ) {
            $query = $query->where('cases_regions_users.user_id', '=', $user_id);
            $query = $query->whereraw('
            ( ((`cases_regions_users`.`station_id_from` is not null) 
                and (`cases_regions_users`.`station_id_to` is not null)
                and (`stations`.`station_id` between `cases_regions_users`.`station_id_from` and `cases_regions_users`.`station_id_to`))
             or ((`cases_regions_users`.`station_id_from` is null) 
                and (`cases_regions_users`.`station_id_to` is null)
                and (`stations`.`station_id` between `cases_regions`.`station_id_from` and `cases_regions`.`station_id_to`)))');
        }
        $query=$query->where('cases.case_id','=', $case_id);
        return $query;
    } 
    public function scopeStationOfficerDetails($query,$case_region_id, $station_id) {
        $query->from('stations');
        $query->select(db::raw('cases_regions.case_region_id, 
        cases_regions.case_region_name, 
        municipalities.municipality_id,municipalities.municipality_name,
       stations_officers.station_officer_name, 
        if(isNull(stations_officers.phone2),stations_officers.phone1,concat(stations_officers.phone1 ,\' | \', stations_officers.phone2)) as station_officer_phone'));
        $query->join('cases_regions', 'stations.case_region_id','=','cases_regions.case_region_id');
        $query->join('municipalities', 'stations.municipality_id','=','municipalities.municipality_id');
        $query->leftjoin('stations_officers','stations.station_officer_id','stations_officers.station_officer_id');
        $query->where('station_id','=',$station_id);
        $query->where('cases_regions.case_region_id','=',$case_region_id);
        return $query;
    }
    public function scopeMunicipalityDetails ($query,$station_id) {
        $query->from('stations');
        $query->select('municipalities.municipality_id,municipalities.municipality_name');
        $query->join('municipalities','stations.municipality_id','=','municipalities.municipality_id');
        $query->where('stations.station_id','=',$station_id);
        return $query;
    }

    public function scopeCaseRegionDetails ($query,$station_id) {
        $query->from('stations');
        $query->select('cases_regions.case_region_id, cases_regions.case_region_name');
        $query->join('cases_regions', 'stations.case_region_id','=','cases_regions.case_region_id');
        $query->where('stations.station_id','=',$station_id);
        return $query;   
    }
}
