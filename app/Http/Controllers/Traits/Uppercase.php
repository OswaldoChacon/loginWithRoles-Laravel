<?php

namespace App\Http\Controllers\Traits;

trait Uppercase
{
    public function Uppercase($value)
    {
        return strtoupper($value);
    }
}
