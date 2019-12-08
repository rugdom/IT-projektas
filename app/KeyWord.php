<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeyWord extends Model
{
    protected $table = 'keywords';
    protected $fillable = [
        'word'
    ];

    public $timestamps = false;
}
