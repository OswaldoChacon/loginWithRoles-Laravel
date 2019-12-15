<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    //
    protected $fillable = [
        'nombre'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
