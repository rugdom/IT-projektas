<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderAgeGroup extends Model
{
    protected $fillable = [
        'fk_order_age_group', 'fk_ageGroup'
    ];

    public $timestamps = false;

    public function order(){
        return $this->belongsTo('App\Order', 'fk_order_age_group');
    }
}
