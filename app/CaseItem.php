<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseItem extends Model
{
    protected $fillable = ['case_item_id'];
    protected $table = 'cases_items';
    protected $primaryKey = 'case_item_';
    
    public function case()
    {
        return $this->belongsTo('App\Case');
    }
}

