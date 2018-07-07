<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion extends Model
{
    use SoftDeletes;
    protected $table = 'direccion';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $encuesta_id)
    {
        return $query->where(function($subquery) use($encuesta_id)
        {
            if (!is_null($encuesta_id)) {
                $subquery->where('encuesta_id', 'LIKE', '%'.$encuesta_id.'%');
            }
        })
        ->orderBy('id', 'DESC');
    }
}
