<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseStatus extends Model
{
    protected $fillable = ['status_id'];
    protected $table = 'cases_statuses';
    protected $primaryKey = 'status_id';
    
   
}
