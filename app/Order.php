<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'participation', 'period', 'inform_before', 'fk_order_event', 'fk_order_user',
        'only_free', 'region', 'created_at', 'updated_at'
    ];

    public $timestamps = true;

    public function event(){
        return $this->belongsTo('App\Event', 'fk_order_event');
    }
}
