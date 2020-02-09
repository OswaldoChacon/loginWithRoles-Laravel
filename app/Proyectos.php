<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyectos extends Model
{
    //
    protected $fillable = [
        'titulo', 'empresa', 'objetivo', 'lineadeinvestigacion_id', 'asesor'
    ];
    public function user()
    {
        return $this->belongsToMany('App\User');
    }
    public function lineadeinvestigacion()
    {
        return $this->belongsTo('App\LineasDeInvestigacion');
    }
    public function foro()
    {
        return $this->belongsTo('App\Foros','foros_id');
    }
    public function proyecto_jurado()
    {       
        return $this->belongsToMany('App\User','jurados','proyecto_id','docente_id');    
    }
    public function foro_activo($id_foro)
    {
        $foro = Foros::find($id_foro);
        if($foro->acceso == true)
            return true;
        return false;
    }
}
