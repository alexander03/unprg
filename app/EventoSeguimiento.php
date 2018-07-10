<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventoSeg extends Model
{
    use SoftDeletes;
    protected $table = 'evento';
    protected $dates = ['deleted_at'];

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query,$nombre)
    {
        return $query->where(function($subquery) use($nombre)
		            {
		            	if (!is_null($nombre)) {
		            		$subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
		            	}
		            })
        			->orderBy('nombres', 'ASC');
    }

    public function tipoevento()
	{
		return $this->belongsTo('App\TipoEvento', 'tipoevento_id');
    }
    
}
