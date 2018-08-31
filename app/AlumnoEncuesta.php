<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumnoEncuesta extends Model
{
    use SoftDeletes;
    protected $table = 'alumno_encuesta';
    protected $dates = ['deleted_at'];

    public function alumno()
	{
		return $this->belongsTo('App\Alumno', 'alumno_id');
    }
    
    public function encuesta()
	{
		return $this->belongsTo('App\Encuesta', 'encuesta_id');
	}

	public function scopelistar($query, $nombre, $alumno_id)
    {
    	$encuesta = Encuesta::select('id')->where('nombre', 'LIKE', '%'.$nombre.'%')->get();
    	$like = '';
        return $query->orWhere(function($subquery) use($encuesta)
        {
            if(count($encuesta) > 0) {
            	foreach ($encuesta as $enc) {
            		$subquery->orWhere('encuesta_id', '=', $enc->id);
            	}
            } else {
            	$subquery->orWhere('encuesta_id', '=', 0);
            }
        })
        ->where(function($subquery) use($alumno_id) {
            if (!is_null($alumno_id)) {
                $subquery->Where('alumno_id', '=', $alumno_id);
            }
        })
        ->orderBy('encuesta_id', 'ASC');
    }
}
