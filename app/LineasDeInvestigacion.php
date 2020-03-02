<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LineasDeInvestigacion extends Model
{
    //
    public $table = "lineasdeinvestigacion";
    protected $fillable = [
        'clave','nombre'
    ];
    protected $hidden =[];    

    public function proyectos()
    {
        return $this->hasMany('App\Proyectos','lineadeinvestigacion_id');
    }
}
