<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escuela extends Model
{
    protected $table = 'escuela';
    protected $dates = ['deleted_at'];


    public function facultad(){
        return $this->belongsTo('App\Facultad', 'facultad_id');
    } 

    public function scopelistar($query, $descripcion)
    {
        return $query->where(function($subquery) use($descripcion)
		            {
		            	if (!is_null($descripcion)) {
		            		$subquery->where('nombre', 'LIKE', '%'.$descripcion.'%');
		            	}
		            })
        			->orderBy('nombre', 'ASC');
        			
    }
}
