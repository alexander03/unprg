<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Escuela;
use Illiminate\Database\Elocuent\softDeletes;

class Competencia extends Model
{
    protected $table = 'competencia';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    

    public function scopelistar($query, $nombre)
    {
        return $query->where(function($subquery) use($nombre)
                    {
                        if (!is_null($nombre)) {
                            $subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
                        }
                    })
                    ->orderBy('nombre', 'ASC');
    }

    public function escuela()
    {
        return $this->belongsTo('App\Escuela', 'escuela_id');
    }

}
