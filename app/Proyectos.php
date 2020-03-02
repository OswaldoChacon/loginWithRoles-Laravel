<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyectos extends Model
{
    //
    protected $fillable = [
        'titulo', 'empresa', 'objetivo', 'lineadeinvestigacion_id', 'tipos_proyectos_id', 'asesor'
    ];
    public function user()
    {
        return $this->belongsToMany('App\User');
    }
    public function lineadeinvestigacion()
    {
        return $this->belongsTo('App\LineasDeInvestigacion');
    }
    public function tipos_proyectos()
    {
        return $this->belongsTo('App\TiposProyectos');
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

    public function folio(Foros $foro)
    {
        $folio = $foro->prefijo;
        $concat_folio = $foro->proyectos()->count() +1;
        if ($concat_folio < 10)
            $folio .= "0";
        // $folio .= $concat_folio + 1;
        $folio .= $concat_folio;
        return $folio;
    }
}
