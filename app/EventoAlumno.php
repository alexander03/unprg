<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Evento;

class EventoAlumno extends Model
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
     
	public function evento(){
        return $this->belongsTo('App\Evento', 'evento_id');
    }
    
    public function alumno(){
        return $this->belongsTo('App\Alumno', 'alumno_id');
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


    public static function listarEventos($nombre){
        $alumno_id        = EventoALumno::getIdALumno();
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
        ->where('EVENTO.TIPOEVENTO_ID','IS NOT','NULL');

        return $results;
    }
public static function suscribir($evento_id){
        $error = DB::transaction(function() use($request, $id){
            $eventoalumno = new EventoALumno();
            $eventoalumno->alumno_id = EventoALumno::getIdALumno();
            $eventoalumno->evento_id = $evento_id;
            $eventoalumno->save();
        });
        return is_null($error) ? "OK" : $error;
    }
     
    public static function dessuscribir($evento_id){
        EventoAlumno::where('EVENTO_ID','=',$evento_id)->where('EVENTO_ID','=',EventoALumno::getIdALumno())->delete();
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
