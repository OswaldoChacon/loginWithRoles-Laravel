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
    protected $hidden =['id'];    

    public function proyecto()
    {
        return $this->hasOne('App\Proyectos');
    }
}
