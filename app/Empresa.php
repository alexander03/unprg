<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
	use SoftDeletes;
    protected $table = 'empresa';
    protected $dates = ['deleted_at'];

    public function scopelistar($query, $ruc)
    {
        return $query->where(function($subquery) use($ruc)
        {
            if (!is_null($ruc)) {
                $subquery->where('ruc', 'LIKE', '%'.$ruc.'%');
            }
        })
        ->orderBy('ruc', 'ASC');
    }
}
