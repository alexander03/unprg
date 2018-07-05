<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experiencias_Laborales extends Model
{
    //
    protected $table = 'experiencias_laborales';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $fechai, $fechaf)
    {
        return $query->where(function($subquery) use($fechai, $fechaf)
		            {
                        $subquery->whereBetween('fechainicio', array($fechai, $fechaf));
		            })
                    ->orderBy('fechainicio', 'ASC');
                    
    }

    public function alumno(){
        return $this->belongsTo('App\Alumno', 'alumno_id');
    } 


}
