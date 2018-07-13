<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        echo $SQLNULL.' - '.$SQLVAL;
        
        $results = Evento::leftjoin('DIRECCION_EVENTO','DIRECCION_EVENTO.EVENTO_ID','=','EVENTO.ID')
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
<<<<<<< HEAD
        ->where('EVENTO.TIPOEVENTO_ID','is','NULL');
=======
        ->where('EVENTO.TIPOEVENTO_ID','IS','NULL');

>>>>>>> c0354a71fb55ed169f58eac3b008f6e7a36fa16c
        return $results;
    }

}

