<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $fillable = ['municipality_id'];
    protected $primaryKey = 'municipality_id';
    protected $table = 'municipalities';
}
