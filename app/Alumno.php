<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Escuela;

class Alumno extends Model
{
    use SoftDeletes;
    protected $table = 'alumno';
    protected $dates = ['deleted_at'];

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $codigo, $nombre, $escuela_id, $facultad_id)
    {
        $like = '=';
        $escuelas = array();
        if(is_null($escuela_id)) {
            if (!is_null($facultad_id)) {
                $esc = Escuela::select('id')->where('facultad_id', '=', $facultad_id)->get();
                foreach ($esc as $es) {
                    $escuelas[] = $es->id;
                }
            } else {
                $escuelas[] = '%%';
                $like = 'LIKE';
            }               
        } else {
            $escuelas[] = $escuela_id;
        }

        return $query->where(function($subquery) use($codigo)
		            {
		            	if (!is_null($codigo)) {
		            		$subquery->where('codigo', 'LIKE', '%'.$codigo.'%');
		            	}
		            })->where(function($subquery) use($nombre)
		            {
		            	if (!is_null($nombre)) {
		            		$subquery->where('nombres', 'LIKE', '%'.$nombre.'%');
		            	}
		            })->where(function($subquery) use($escuelas, $like)
                    {
                        for ($i=0; $i < count($escuelas); $i++) { 
                            $subquery->orWhere('escuela_id', $like, $escuelas[$i]);
                        }
                    })
        			->orderBy('codigo', 'ASC')
        			->orderBy('nombres', 'ASC');
    }

    public function escuela()
	{
		return $this->belongsTo('App\Escuela', 'escuela_id');
    }
    
    public function especialidad()
	{
		return $this->belongsTo('App\Especialidad', 'especialidad_id');
	}
}
