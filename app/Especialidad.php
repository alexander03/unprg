<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidad';

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $name)
    {
        return $query->where(function($subquery) use($name)
		            {
		            	if (!is_null($name)) {
		            		$subquery->where('nombre', 'LIKE', '%'.$name.'%');
		            	}
		            })
        			->orderBy('nombre', 'ASC');
    }

    /**
     * Método que retorna los usuarios con el tipo de usuario indicado
     * @return sql sql
     */
    public function alumno()
	{
		return $this->hasMany('App\Alumno');
	}

	/**
	 * Método de que retorna todos los permisos para el tpo de usuario indicado
	 * @return sql sql
	 */
	public function permissions()
	{
		return $this->hasMany('App\Permission');
	}
}
