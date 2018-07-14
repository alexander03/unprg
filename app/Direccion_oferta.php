<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion_oferta extends Model
{
    use SoftDeletes;
    protected $table = 'direccion_evento';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $oferta_id)
    {
        return $query->where(function($subquery) use($oferta_id)
        {
            if (!is_null($oferta_id)) {
                $subquery->where('evento_id', 'LIKE', '%'.$oferta_id.'%');
            }
        })
        ->orderBy('id', 'DESC');
    }
}