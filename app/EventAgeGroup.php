<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventAgeGroup extends Model
{
    protected $fillable = [
        'fk_event_age_group', 'fk_age_group'
    ];

    public $timestamps = false;

    public function event(){
        return $this->belongsTo('App\Event', 'fk_event_age_group');
    }
}
