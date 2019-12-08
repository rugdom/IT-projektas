<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderType extends Model
{
    protected $fillable = [
        'fk_order_type', 'fk_type_id'
    ];

    public $timestamps = false;

}
