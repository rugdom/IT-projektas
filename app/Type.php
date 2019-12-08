<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name', 'fk_parent_type'
    ];

    public $timestamps = false;

    public function subtype(){
        return $this->hasMany('App\Type', 'fk_parent_type');
    }
}
