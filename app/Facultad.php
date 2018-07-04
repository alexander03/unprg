<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    protected $table = 'facultad';
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
}
