<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Direccion;
use Illuminate\Support\Facades\DB;

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

    public function direcciones()
    {
        return $this->hasMany('App\Direccion');
    }

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $nombre  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $nombre, $tipoencuesta_id, $facultad_id, $escuela_id, $especialidad_id)
    {
        $c = false;
        if($facultad_id != '' || $escuela_id != '' || $especialidad_id != '') {
            $direccion = Direccion::select('encuesta_id')->where('facultad_id', '=', $facultad_id)->where('escuela_id', '=', $escuela_id)->where('especialidad_id', '=', $especialidad_id)->get();

            if (count($direccion) != 0) {
                $c = true;
            } 
        } else {
            $c = true;
        }

        return $query->where(function($subquery) use($c, $direccion, $facultad_id, $escuela_id, $especialidad_id)
        {
            if ($c) {
                foreach ($direccion as $row) {
                    $subquery->orWhere('id', '=', $row['encuesta_id']);
                }
            } else {
                $subquery->where('nombre', '=', '----------');
            }
        })
        ->where(function($subquery) use($c, $nombre, $tipoencuesta_id){
            if($c){
                $subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
                if (!is_null($tipoencuesta_id)) {
                    $subquery->where('tipoencuesta_id', '=', $tipoencuesta_id);
                } 
            } else {
                $subquery->where('nombre', '=', '----------');
            }
        })
		->orderBy('tipoencuesta_id', 'ASC')
		->orderBy('nombre', 'ASC');
    }
}
