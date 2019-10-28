<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseRegion extends Model
{
    protected $fillable = ['case_region_id'];
    protected $table = 'cases_regions';
    protected $primaryKey = 'case_region_id';
}
