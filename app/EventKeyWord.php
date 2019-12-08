<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventKeyWord extends Model
{
    protected $table = 'event_keywords';
    protected $fillable = [
        'fk_event_keyword', 'fk_keyword'
    ];

    public $timestamps = false;

    public function keyword(){
        return $this->belongsTo('App\KeyWord', 'fk_keyword');
    }
}
