<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificaciones extends Model
{
    //
    protected $fillable = [];
    public function user_emisor()
    {
        return $this->belongsTo('App\User','emisor');
    }
    public function user_receptor()
    {
        return $this->belongsTo('App\User','receptor');
    }
    public function proyecto()
    {
        return $this->belongsTo('App\Proyectos','proyecto_id');
    }
}
