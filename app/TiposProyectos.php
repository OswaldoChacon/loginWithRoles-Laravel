<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiposProyectos extends Model
{
    //
    public $table = 'tipos_de_proyectos';
    protected $fillable = [
        'clave','nombre'
    ];
    public function proyectos()
    {
        return $this->hasMany('App\Proyectos');
    }
}
