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

    public function competencia(){
        return $this->belongsTo('App\Competencia', 'competencia_id');
    } 

    public function scopelistar($query, $alumno_id)
    {
        return $query->where(function($subquery) use($alumno_id)
		            {
		            	if (!is_null($alumno_id)) {
		            		$subquery->where('alumno_id', '=', $alumno_id);
		            	}
		            });
        			//->orderBy('nombre', 'ASC');
        			
    }

    public static function getIdAlumno(){
        $alumno_id = null;
         // Obtiene el objeto del Usuario Autenticado
         $user = Auth::user();
         // Obtiene el ID del Usuario Autenticado
         $id = Auth::id();
         $result = DB::select('SELECT alumno_id FROM usuario WHERE id = ?', [''.$id.'']);
         foreach ($result as $r) {
            //echo var_dump($r).' -> ';
            //echo var_dump(json_encode($r));
            $alumno_id = $r->alumno_id;
         }
         //echo $alumno_id;
         return $alumno_id;
    }

    public static function getIdEscuela(){
        $escuela_id = null;
         $user = Auth::user();
         $id = Auth::id();
         $result = DB::select('SELECT escuela_id FROM ALUMNO WHERE id = (SELECT alumno_id FROM usuario WHERE id = ?)', [''.$id.'']);
         foreach ($result as $r) {
            $escuela_id = $r->escuela_id;
         }
         return $escuela_id;
    }
}
