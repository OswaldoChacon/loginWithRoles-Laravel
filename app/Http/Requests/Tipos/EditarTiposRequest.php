<?php

namespace App\Http\Requests\Tipos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Crypt;

class EditarTiposRequest extends FormRequest
{
    protected $route;
    public function __construct(Route $route)
    {
        $this->route = $route;
       
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clave' => 'required|unique:tipos_de_proyectos,clave,'.Crypt::decrypt($this->route->parameter('tipo')),
            'nombre' => 'required|unique:tipos_de_proyectos,nombre,' .Crypt::decrypt($this->route->parameter('tipo'))
        ];
    }
}
