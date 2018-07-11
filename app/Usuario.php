<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\UsuarioResetPasswordNotification;

class Usuario extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'password', 'state','email','usertype_id','empresa_id','alumno_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopelistar($query, $login)
    {
        return $query->where(function($subquery) use($login)
                    {
                        if (!is_null($login)) {
                            $subquery->where('login', 'LIKE', '%'.$login.'%');
                        }
                    })
                    ->orderBy('login', 'ASC');
    }

    public function usertype()
    {
        return $this->belongsTo('App\Usertype', 'usertype_id');
    }

    public function alumno(){
        return $this->belongsTo('App\Aumno', 'alumno_id');
    }

    public function sendPasswordResetNotification($token)
    {
      $this->notify(new UsuarioResetPasswordNotification($token));
    }
}
