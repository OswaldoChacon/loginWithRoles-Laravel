<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Crypt;

class EditLineaRequest extends FormRequest
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
            'clave' => 'required|unique:lineasdeinvestigacion,clave,'.Crypt::decrypt($this->route->parameter('linea')),
            'nombre' => 'required|unique:lineasdeinvestigacion,nombre,'.Crypt::decrypt($this->route->parameter('linea'))
            //
        ];        
    }
    public function messages()
    {

        return [
            'clave.required' => 'El campo clave es requerido',
            'clave.unique' => 'La clave ingresada ya existe',
            'nombre.required' => 'El campo nombre es requerido',
            'nombre.unique' => 'El nombre ingresado ya existe'
        ];
    }
}
