<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'date', 'time', 'image', 'region', 'address', 'free', 'fk_link', 'link_to_buy'
    ];

    public $timestamps = false;

    public function keywords(){
        return $this->hasMany('App\EventKeyWord', 'fk_event_keyword');
    }
}
