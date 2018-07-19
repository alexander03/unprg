<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Oferta;
class OfertaAlumno extends Model
{
    use SoftDeletes;
    protected $table = 'evento_alumno';
    protected $dates = ['deleted_at'];

    /**
     * Método para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function empresa(){
        return $this->belongsTo('App\Alumno', 'alumno_id');
    }
     //----
	public function evento(){
        return $this->belongsTo('App\Evento', 'evento_id');
	}
	 
    public static function getIdAlumno()
    {
        $alumno_id = null;
        $user = Auth::user();
        $id = Auth::id();
        $result = DB::select('SELECT alumno_id FROM usuario WHERE id = ?', ['' . $id . '']);
        foreach ($result as $r) {
            $alumno_id = $r->alumno_id;
        }
        return $alumno_id;
    }

    // public static function suscrito($evento_id, $alumno_id)
    // {

    //     $id  =  DB::table('evento_alumno')->where('alumno_id','=', $alumno_id)->where('evento_id','=', $evento_id)->get();
    //     return $id;
    // }
    
    public static function listarOfertas($nombre){
        $alumno_id        = OfertaALumno::getIdALumno();
        $escuela_id = DB::table('Alumno')->where('id', $alumno_id)->value('escuela_id');
        $especialidad_id = DB::table('Alumno')->where('id', $alumno_id)->value('especialidad_id');
        $facultad_id = DB::table('Escuela')->where('id', $escuela_id)->value('facultad_id');
        $SQLNULL = '';
        $SQLVAL = '';
        if($especialidad_id != null){
            $SQLNULL = '=';
            $SQLVAL = $especialidad_id;
        }else{
            $SQLNULL = "IS";
            $SQLVAL = "NULL";
        }
        
        
        $results = Oferta::leftjoin('DIRECCION_EVENTO','DIRECCION_EVENTO.EVENTO_ID','=','EVENTO.ID')
        ->leftjoin('FACULTAD','FACULTAD.ID','=','DIRECCION_EVENTO.FACULTAD_ID')
        ->leftjoin('ESCUELA','ESCUELA.ID','=','DIRECCION_EVENTO.ESCUELA_ID')
        ->leftjoin('ESPECIALIDAD','ESPECIALIDAD.ID','=','DIRECCION_EVENTO.ESPECIALIDAD_ID')
        ->select(
            'EVENTO.ID AS IDEVENTO',
            'EVENTO.NOMBRE AS NOMBRE_EVENTO'
        )
        ->where('EVENTO.OPCIONEVENTO','=','0')
        ->orwhere('FACULTAD.ID','=',$facultad_id)
        ->orwhere('ESCUELA.ID','=',$escuela_id)
        ->orwhere('ESPECIALIDAD.ID','=',5)
        ->where('EVENTO.NOMBRE','LIKE','%'.$nombre.'%')
        ->where('EVENTO.TIPOEVENTO_ID','IS','NULL');
        return $results;
    }

    public static function listarSuscriptoresPDF($evento_id){

        $results = OfertaAlumno::join('ALUMNO','ALUMNO.ID','=','EVENTO_ALUMNO.ALUMNO_ID')
        ->join('EVENTO','EVENTO.ID','=','EVENTO_ALUMNO.EVENTO_ID')
        ->join('ESCUELA','ESCUELA.ID','=','ALUMNO.ESCUELA_ID')
        ->join('ESPECIALIDAD','ESPECIALIDAD.ID','=','ALUMNO.ESPECIALIDAD_ID')
        ->join('FACULTAD','FACULTAD.ID','=','ESCUELA.FACULTAD_ID')
        ->select(
            'FACULTAD.NOMBRE AS NOMBRE_FACULTAD',
            'ESCUELA.NOMBRE AS NOMBRE_ESCUELA',
            'ESPECIALIDAD.NOMBRE AS NOMBRE_ESPECIALIDAD',
            'ALUMNO.CODIGO AS ALUMNO_CODIGO',
            'ALUMNO.NOMBRES AS ALUMNO_NOMBRES',
            'ALUMNO.APELLIDOPATERNO AS ALUMNO_APELLIDOPATERNO',
            'ALUMNO.APELLIDOMATERNO AS ALUMNO_APELLIDOMATERNO',
            'ALUMNO.TELEFONO AS ALUMNO_TELEFONO',
            'ALUMNO.EMAIL AS ALUMNO_EMAIL',
            'EVENTO.NOMBRE AS EVENTO_NOMBRE'
        )
        ->where('EVENTO.ID','=',$evento_id);
        return $results;
    }

    public static function suscribir($oferta_id){
        $error = DB::transaction(function() use($request, $id){
            $ofertaalumno = new OfertaALumno();
            $ofertaalumno->alumno_id = OfertaALumno::getIdALumno();
            $ofertaalumno->evento_id = $oferta_id;
            $ofertaalumno->save();
        });
        return is_null($error) ? "OK" : $error;
    }
     
    public static function dessuscribir($oferta_id){
        OfertaAlumno::where('EVENTO_ID','=',$oferta_id)->where('EVENTO_ID','=',OfertaALumno::getIdALumno())->delete();
    }

    public function scopelistarSuscriptores($query, $id)
    {
        return $query->where(function($subquery) use($id)
		            {
		            	if (!is_null($id)) {
		            		$subquery->where('evento_id', '=', $id);
		            	}
		            });
    }

}

