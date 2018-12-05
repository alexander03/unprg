<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Escuela;
use Illiminate\Database\Elocuent\softDeletes;
use Illuminate\Support\Facades\DB;

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

    public static function listarCompetenciasAlumno($escuela_id, $alumno_id)
    {
        $sql = "SELECT COMPETENCIA.ID, COMPETENCIA.NOMBRE FROM COMPETENCIA 
        LEFT JOIN COMPETENCIA_ALUMNO ON COMPETENCIA_ALUMNO.COMPETENCIA_ID = COMPETENCIA.ID
        WHERE COMPETENCIA.ESCUELA_ID = ? AND COMPETENCIA.ID NOT IN (SELECT COMPETENCIA_ID FROM COMPETENCIA_ALUMNO WHERE ALUMNO_ID = ?)";
        $results = DB::select($sql,array($escuela_id,$alumno_id));
        //echo var_dump(json_encode($results));
        return $results;
    }

    public function escuela()
    {
        return $this->belongsTo('App\Escuela', 'escuela_id');
    }

}

