<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experiencia_Competencia extends Model
{
    //
    protected $table = 'experiencia_competencia';
    protected $dates = ['deleted_at'];

    public static function listarCompetencias($nombre,$id){
        $results = Experiencia_Competencia::join("competencia_alumno","competencia_alumno.id","=","experiencia_competencia.competencia_alumno_id")
            ->join("competencia",'competencia.id','=','competencia_alumno.competencia_id')
            ->select(
                'experiencia_competencia.id as idexperiencia_competencia',
                'experiencia_competencia.competencia_alumno_id as idcompetencia_alumno',
                'competencia.nombre as nombre_competencia'
                )
            ->where('competencia.nombre','LIKE', '%'.$nombre.'%')
            ->where('experiencia_competencia.EXPERIENCIA_LABORAL_ID','=',$id);
        return $results; 
    }


}
