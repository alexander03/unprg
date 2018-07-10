<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class EventoAlumno extends Model
{
    //use SoftDeletes;
    protected $table = 'evento_alumno';
    //protected $dates = ['deleted_at'];

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function empresa(){
        return $this->belongsTo('App\Alumno', 'alumno_id');
    }
     
	public function evento(){
        return $this->belongsTo('App\Evento', 'evento_id');
	}
	 
    public static function getIdAlumno()
    {
        $alumno_id = null;
        // Obtiene el objeto del Usuario Autenticado
        $user = Auth::user();
        // Obtiene el ID del Usuario Autenticado
        $id = Auth::id();
        $result = DB::select('SELECT alumno_id FROM usuario WHERE id = ?', ['' . $id . '']);
        foreach ($result as $r) {
            //echo var_dump($r).' -> ';
            //echo var_dump(json_encode($r));
            $alumno_id = $r->alumno_id;
        }
        //echo $alumno_id;
        return $alumno_id;
    }

    public function scopelistar($query, $evento_id)
    {
        
        return $query->where(function($subquery) use($evento_id)
		            {
		            	if (!is_null($evento_id)) {
		            		$subquery->where('evento_id', '=', $evento_id);
		            	}
		            });
        			
    }
   
}
