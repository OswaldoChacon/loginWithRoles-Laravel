<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HorarioJurado extends Model
{
    //
    public $table = 'horario_jurado';
    protected $fillable = [
        'hora','posicion'
    ];

    public function docente()
    {
        return $this->belongsTo('App\User');
    }
    public function fechas_foros()
    {
        return $this->belongsTo('App\FechasForo');
    }
}
