<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foros extends Model
{
    //

    protected $fillable = [];
    protected $hidden = [];

    public function verificarForo()
    {
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    public function proyectos()
    {
        return $this->hasMany('App\Proyectos');
    }
    public function fechas()
    {
        return $this->hasMany('App\FechasForo');
    }

    public function prefijo()
    {
        
    }
}
