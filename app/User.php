<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'num_control', 'prefijo','apellidoP', 'apellidoM', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Roles');
    }
    public function foros()
    {
        return $this->hasMany('App\Foros');
    }
    public function foros_user()
    {
        return $this->belongsToMany('App\Foros');
    }
    public function asesor()
    {
        return $this->hasMany('App\Proyectos','asesor');
    }
    public function proyectos()
    {
        return $this->belongsToMany('App\Proyectos');
    }
    public function notificaciones_emisor()
    {
        return $this->hasMany('App\Notificaciones', 'emisor');
    }
    public function notificaciones_receptor()
    {
        return $this->hasMany('App\Notificaciones', 'receptor');
    }
    public function jurado_proyecto()
    {
        return $this->belongsToMany('App\Proyectos', 'jurados', 'docente_id', 'proyecto_id');
    }
    public function horarios()
    {
        return $this->hasMany('App\HorarioJurado', 'docente_id');
    }

    public function getId()
    {
        return $this->id;
    }
    public function getFullName()
    {
        return strtoupper("{$this->prefijo} {$this->nombre} {$this->apellidoP} {$this->apellidoM}");
    }
    public function authorizeRoles($roles)
    {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'Esta acción no está autorizada.');
    }
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }
    public function hasRole($role)
    {
        if ($this->roles()->where('nombre', $role)->first()) {
            return true;
        }
        return false;
    }
    public function hasProject($user_id)
    {
        if (User::whereHas('proyectos.foro', function (Builder $query) {
            $query->where('promedio', '>', 69)->where('acceso', false);
        })->orWhereHas('proyectos.foro', function (Builder $query) {
            $query->where('acceso', true);
        })->whereHas('roles', function (Builder $query) {
            $query->where('roles.nombre', 'Alumno');
        })->where('id', $user_id)->count() > 0)
            return false;
        return true;
    }

  
}
