<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidad';
    /**
     * Método que retorna los usuarios con el tipo de usuario indicado
     * @return sql sql
     */
    public function escuela(){
        return $this->belongsTo('App\Escuela', 'escuela_id');
    }

    public function alumno()
	{
		return $this->hasMany('App\Alumno');
    }

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $name, $escuela_id)
    {
        return $query->where(function($subquery) use($name)
		            {
		            	if (!is_null($name)) {
		            		$subquery->where('nombre', 'LIKE', '%'.$name.'%');
		            	}
		            })
        			->where(function($subquery) use($escuela_id)
		            {
		            	if (!is_null($escuela_id)) {
		            		$subquery->where('escuela_id', '=', $escuela_id);
		            	}
		            })
        			->orderBy('nombre', 'ASC');
    }


}
