<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    protected $fillable = [
        'fk_event_type', 'fk_type'
    ];

    public $timestamps = false;
}
