<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StationStatus extends Model
{
    protected $fillable = ['status_id'];
    protected $table = 'stations_statuses';
    protected $primaryKey = 'status_id';
    
   
}
