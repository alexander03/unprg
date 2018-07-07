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
        $direccion = null;
        if($facultad_id != '' || $escuela_id != '' || $especialidad_id != '') {
            $direccion = Direccion::select('encuesta_id')->where('facultad_id', '=', $facultad_id)->where('escuela_id', '=', $escuela_id)->where('especialidad_id', '=', $especialidad_id)->get();

            $c = true;
            if (count($direccion) == 0) {
                $direccion = null;
            } 
        } 

        return $query->orwhere(function($subquery) use($c, $direccion, $facultad_id, $escuela_id, $especialidad_id)
        {
            if ($c == true && $direccion != null) {
                foreach ($direccion as $row) {
                    $subquery->orWhere('id', '=', $row['encuesta_id']);
                }
            } else {
                if ($c == true && $direccion == null) {
                    $subquery->where('nombre', '=', '%%%%%%%');
                } else {
                    $subquery->where('nombre', 'LIKE', '%%');
                }
            }
        })
        ->where(function($subquery) use($c, $nombre, $tipoencuesta_id){
            if (!is_null($nombre)) {
                $subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
            }
            if (!is_null($tipoencuesta_id)) {
                $subquery->where('tipoencuesta_id', '=', $tipoencuesta_id);
            }
        })
        ->orderBy('tipoencuesta_id', 'ASC')
        ->orderBy('nombre', 'ASC');
    }
}
