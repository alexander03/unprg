<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumnoEncuesta extends Model
{
    use SoftDeletes;
    protected $table = 'alumno_encuesta';
    protected $dates = ['deleted_at'];
}
