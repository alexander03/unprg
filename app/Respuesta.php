<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Respuesta extends Model
{
    use SoftDeletes;
    protected $table = 'respuesta';
    protected $dates = ['deleted_at'];
}
