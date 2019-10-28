<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StationVote extends Model
{
    protected $fillable = ['case_id','municipality_id','station_id'];
    protected $table = 'votes';
    //protected $primaryKey = array('case_id','electoral_region_id','station_id');
   


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
            ->where('station_id', '=', $this->getAttribute('station_id'));
        return $query;
    }
}

