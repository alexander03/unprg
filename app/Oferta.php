<?php

namespace App;
use App\OfertaAlumno;
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

    public static function listarDetalleOferta($evento_id){
        $results = Direccion_oferta::leftjoin('FACULTAD','FACULTAD.ID','DIRECCION_EVENTO.FACULTAD_ID')
        ->leftjoin('ESCUELA','ESCUELA.ID','DIRECCION_EVENTO.ESCUELA_ID')
        ->leftjoin('ESPECIALIDAD','ESPECIALIDAD.ID','DIRECCION_EVENTO.ESPECIALIDAD_ID')
        ->select(
            'DIRECCION_EVENTO.ID',
            'FACULTAD.NOMBRE AS NOMBRE_FACULTAD',
            'ESCUELA.NOMBRE AS NOMBRE_ESCUELA',
            'ESPECIALIDAD.NOMBRE AS NOMBRE_ESPECIALIDAD'
        )->where('DIRECCION_EVENTO.EVENTO_ID', '=', $evento_id);
        return $results;
    }
    public static function eliminarDetalle($evento_id){
        Direccion_oferta::where('EVENTO_ID','=',$evento_id)->delete();
    }
    
    public static function eliminarSuscriptores($evento_id){
        OfertaAlumno::where('EVENTO_ID','=',$evento_id)->delete();
    }

}
