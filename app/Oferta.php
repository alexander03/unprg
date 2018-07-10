<?php

namespace App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    protected $table = 'evento';
    protected $dates = ['deleted_at'];


    public function empresa(){
        return $this->belongsTo('App\Empresa', 'empresa_id');
    } 
    public function especialidad(){
        return $this->belongsTo('App\Especialidad', 'especialidad_id');
    } 
    // public function tipoevento(){
    //     return $this->belongsTo('App\Tipoevento', 'tipoevento_id');
    // } 
    public static function getIdEmpresa()
    {
        $empresa_id = null;
        // Obtiene el objeto del Usuario Autenticado
        $user = Auth::user();
        // Obtiene el ID del Usuario Autenticado
        $id = Auth::id();
        $result = DB::select('SELECT empresa_id FROM usuario WHERE id = ?', ['' . $id . '']);
        foreach ($result as $r) {
            //echo var_dump($r).' -> ';
            //echo var_dump(json_encode($r));
            $empresa_id = $r->empresa_id;
        }
        //echo $alumno_id;
        return $empresa_id;
    }

    public function scopelistar($query, $nombre, $empresa_id)
    {
        return $query->where(function($subquery) use($nombre)
		            {
		            	if (!is_null($nombre)) {
		            		$subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
		            	}
		            })->where(function($subquery) use($empresa_id){
                        if (!is_null($empresa_id)) {
		            		$subquery->where('empresa_id', '=', $empresa_id);
		            	}
                    })
        			->orderBy('nombre', 'ASC');
        			
    }

}
