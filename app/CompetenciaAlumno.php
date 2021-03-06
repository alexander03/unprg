<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompetenciaAlumno extends Model
{
    //
    protected $table = 'competencia_alumno';
    protected $dates = ['deleted_at'];

    public function competencia()
    {
        return $this->belongsTo('App\Competencia', 'competencia_id');
    }

    public function scopelistar($query, $alumno_id, $nombre)
    {
        
        $results = CompetenciaAlumno::join("competencia","competencia_alumno.competencia_id","=","competencia.id")
            ->select(
                'competencia_alumno.id',
                'competencia_alumno.calificacion',
                'competencia.id as competencia_id',
                'competencia.nombre as competencia_nombre'
                )
            ->where('competencia.nombre','LIKE', '%'.$nombre.'%')
            ->where('alumno_id','=',$alumno_id);
        /*
        $nombre_concat = '%'.$nombre.'%';
        $results = DB::select('SELECT COMPETENCIA_ALUMNO.ID, COMPETENCIA_ALUMNO.CALIFICACION, COMPETENCIA.ID AS COMPETENCIA_ID, COMPETENCIA.NOMBRE FROM COMPETENCIA_ALUMNO INNER JOIN COMPETENCIA ON COMPETENCIA.ID = COMPETENCIA_ALUMNO.COMPETENCIA_ID WHERE COMPETENCIA.NOMBRE LIKE ? AND ALUMNO_ID = ?', [$nombre_concat,$alumno_id]);
        */
        //echo var_dump(json_encode($results));
        return $results;
    }

    public static function getIdAlumno()
    {
        $alumno_id = null;
        // Obtiene el objeto del Usuario Autenticado
        $user = Auth::user();
        // Obtiene el ID del Usuario Autenticado
        $id = Auth::id();
        $usuario = Usuario::find($id);
        $id = $usuario->alumno_id;
        return $id;
    }

    public static function getIdEscuela()
    {
        $escuela_id = null;
        $user = Auth::user();
        $id = Auth::id();
        $usuario = Usuario::find($id);
        $id = $usuario->alumno_id;
        $result = DB::select('SELECT escuela_id FROM ALUMNO WHERE id = ?', ['' . $id . '']);
        foreach ($result as $r) {
            $escuela_id = $r->escuela_id;
        }
        return $escuela_id;
    }

    /*
        $results = DB::table('competencia_alumno')
            ->join('competencia', 'competencia_alumno.competencia_id', '=', 'competencia.id')
            ->where('competencia.nombre','LIKE', '%'.$nombre.'%')
            ->where('alumno_id','=',$alumno_id);
        */
}
