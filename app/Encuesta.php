<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Encuesta extends Model
{
    use SoftDeletes;
    protected $table = 'encuesta';
    protected $dates = ['deleted_at'];

    public function tipoencuesta()
	{
		return $this->belongsTo('App\Tipoencuesta', 'tipoencuesta_id');
	}

    public function alumno_encuestas()
    {
        return $this->hasMany('App\AlumnoEncuesta');
    }

    public function preguntas()
    {
        return $this->hasMany('App\Pregunta');
    }

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $nombre  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $nombre, $tipoencuesta_id)
    {
        return $query->where(function($subquery) use($nombre)
		            {
		            	if (!is_null($nombre)) {
		            		$subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
		            	}
		            })
        			->where(function($subquery) use($tipoencuesta_id)
		            {
		            	if (!is_null($tipoencuesta_id)) {
		            		$subquery->where('tipoencuesta_id', '=', $tipoencuesta_id);
		            	}
		            })
        			->orderBy('tipoencuesta_id', 'ASC')
        			->orderBy('nombre', 'ASC');
    }
}
