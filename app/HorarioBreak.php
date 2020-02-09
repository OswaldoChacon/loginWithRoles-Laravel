<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HorarioBreak extends Model
{
    //
    public $table = "horariobreak";
    protected $fillable = [
        'hora','posicion'
    ];
    public function fechas_foros()
    {
        return $this->belongsTo('App\FechasForo','fechas_foros_id');
    }
}
