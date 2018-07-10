<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experiencias_Laborales extends Model
{
    //
    protected $table = 'experiencias_laborales';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $fechai, $fechaf, $alumno_id)
    {
        return $query->where(function($subquery) use($fechai, $fechaf, $alumno_id)
		            {
                        $subquery->whereBetween('fechainicio', array($fechai, $fechaf))
                        ->where('alumno_id','=',$alumno_id);
		            })
                    ->orderBy('fechainicio', 'ASC');
                    
    }

    public function alumno(){
        return $this->belongsTo('App\Alumno', 'alumno_id');
    } 


}
