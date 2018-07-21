<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    //
    protected $table = 'certificado';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $name)
    {
        return $query->where(function($subquery) use($name)
		            {
		            	if (!is_null($name)) {
		            		$subquery->where('nombre', 'LIKE', '%'.$name.'%');
		            	}
		            })
                    ->orderBy('nombre', 'ASC');
                    
    }
	
    public function scopelistarparacv($query, $alumno_id)
    {
        return $query->where(function($subquery) use($alumno_id)
                    {
                        if (!is_null($alumno_id)) {
                            $subquery->where('alumno_id', '=', $alumno_id);
                        }
                    })
                    ->orderBy('nombre', 'ASC');
                    
    }
}
