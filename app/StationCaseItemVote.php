<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StationCaseItemVote extends Model
{
    protected $fillable = ['case_id','municipality_id','station_id','case_item_id','user_id'];
    protected $table = 'cases_items_votes';
    //protected $primaryKey = ('case_id','case_region_id','station_id','case_item_id');
 
    protected function getKeyForSaveQuery()
{

    $primaryKeyForSaveQuery = array(count($this->primaryKey));

    foreach ($this->primaryKey as $i => $pKey) {
        $primaryKeyForSaveQuery[$i] = isset($this->original[$this->getKeyName()[$i]])
            ? $this->original[$this->getKeyName()[$i]]
            : $this->getAttribute($this->getKeyName()[$i]);
    }

    return $primaryKeyForSaveQuery;

}
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('case_id', '=', $this->getAttribute('case_id'))
            ->where('municipality_id', '=', $this->getAttribute('municipality_id'))
            ->where('station_id', '=', $this->getAttribute('station_id'))
            ->where('case_item_id', '=', $this->getAttribute('case_item_id'));
        return $query;
    }
}

