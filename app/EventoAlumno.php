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

    public function scopelistar($query, $nombre)
    {
        $alumno_id        = EventoALumno::getIdALumno();
        $escuela_id = DB::table('Alumno')->where('id', $alumno_id)->value('escuela_id');
        $especialidad_id = DB::table('Alumno')->where('id', $alumno_id)->value('especialidad_id');
        $facultad_id = DB::table('Escuela')->where('id', $escuela_id)->value('facultad_id');
        return Evento::leftjoin("DIRECCION_EVENTO","DIRECCION_EVENTO.EVENTO_ID","=","EVENTO.ID")
                        ->where('DIRECCION_EVENTO.facultad_id','=',$facultad_id)
                        ->where('Evento.nombre', 'LIKE', '%'.$nombre.'%')
                        ->orWhere('DIRECCION_EVENTO.escuela_id','=',$escuela_id)
                        ->where('Evento.nombre', 'LIKE', '%'.$nombre.'%')
                        ->orWhere('DIRECCION_EVENTO.especialidad_id','=',$especialidad_id)
                        ->where('Evento.nombre', 'LIKE', '%'.$nombre.'%')
                        ->orWhere('OPCIONEVENTO','=',0)
                        ->where('Evento.nombre', 'LIKE', '%'.$nombre.'%');   			
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
        $results = Evento::leftjoin('DIRECCION_EVENTO.EVENTO_ID','=','EVENTO.ID')
        ->leftjoin('FACULTAD.ID','=','DIRECCION_EVENTO.FACULTAD_ID')
        ->leftjoin('ESCUELA.ID','=','DIRECCION_EVENTO.ESCUELA_ID')
        ->leftjoin('ESPECIALIDAD.ID','=','DIRECCION_EVENTO.ESPECIALIDAD_ID')
        ->where('EVENTO.OPCIONEVENTO','=','0')
        ->orwhere('FACULTAD.ID','=',$facultad_id)
        ->orwhere('ESCUELA.ID','=',$escuela_id)
        ->orwhere('ESPECIALIDAD.ID',$SQLNULL,$SQLVAL)
        ->where('EVENTO.NOMBRE','LIKE','%'.$nombre+'%');
        return $results;
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
