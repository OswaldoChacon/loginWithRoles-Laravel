<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FechasForo extends Model
{
    //
    public $timestamps = false;

    protected $fillable = [
        'fecha', 'hora_inicio', 'hora_termino'
    ];
    protected $casts = [
        'hora_inicio' => 'date_format: H:i'
    ];


    public function foro()
    {
        return $this->belongsTo('App\Foros','foros_id');
    }
    public function receso()
    {
        return $this->hasMany('App\HorarioBreak','fechas_foros_id');
    }
    public function horarioIntervalos($minutos)
    {
        $intervalo = array();
        while ($this->hora_inicio <= $this->hora_termino) {
            $newDate = strtotime('+0 hour', strtotime($this->hora_inicio));
            
            $newDate = strtotime('+' . $minutos . 'minute', $newDate);
            $newDate = date('H:i', $newDate);
            $temp = $this->hora_inicio . " - " . $newDate;
            $this->hora_inicio = $newDate;            
            if ($newDate <= $this->hora_termino) 
                array_push($intervalo, $temp);                                
        }
        return $intervalo;
        // dd($intervalo);
    }
}
