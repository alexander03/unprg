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
}
