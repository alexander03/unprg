<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DireccionEventoSeguimiento extends Model
{
    protected $table = 'direccion_evento';
    protected $dates = ['deleted_at'];

    public function evento(){
        return $this->belongsTo('App\Evento', 'evento_id');
    }

    public function direccion_evento(){
        return $this->belongsTo('App\Direccion_Evento', 'direccionevento_id');
    }

    public function scopelistar($query, $nombre, $empresa_id)
    {
        return $query->where(function($subquery) use($nombre)
		            {
		            	if (!is_null($nombre)) {
		            		$subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
		            	}
		            })->where(function($subquery) use($empresa_id){
                        if (!is_null($empresa_id)) {
		            		$subquery->where('empresa_id', '=', $empresa_id);
		            	}
                    })
        			->orderBy('nombre', 'ASC');
        			
    }
}
