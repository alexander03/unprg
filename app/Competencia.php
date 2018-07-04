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
    

    public function scopelistar($query, $nombre, $escuela_id)
    {
        return $query->where(function($subquery) use($nombre)
                    {
                        if (!is_null($nombre)) {
                            $subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
                        }
                    })
                    ->where(function($subquery) use($escuela_id){
                        if (!is_null($escuela_id)) {
                            $subquery->where('escuela_id', '=', $escuela_id);
                        }
                    })
                    ->orderBy('nombre', 'ASC');
    }

    public function escuela()
    {
        return $this->belongsTo('App\Escuela', 'escuela_id');
    }

}
