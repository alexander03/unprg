<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion_evento extends Model
{
    use SoftDeletes;
    protected $table = 'direccion_evento';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $evento_id)
    {
        return $query->where(function($subquery) use($evento_id)
        {
            if (!is_null($evento_id)) {
                $subquery->where('evento_id', 'LIKE', '%'.$evento_id.'%');
            }
        })
        ->orderBy('id', 'DESC');
    }
}