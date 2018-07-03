<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tipoencuesta extends Model
{
    use SoftDeletes;
    protected $table = 'tipoencuesta';
    protected $dates = ['deleted_at'];

    public function encuestas()
	{
		return $this->hasMany('App\Encuesta');
	}

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $nombre  nombre
     * @return sql        sql
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
}
