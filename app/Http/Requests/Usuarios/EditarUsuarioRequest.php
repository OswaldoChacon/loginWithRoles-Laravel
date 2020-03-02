<?php

namespace App\Http\Requests\Usuarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Crypt;

class EditarUsuarioRequest extends FormRequest
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
            //
            'nombre' => 'required',
            'apellidoP' => 'required',
            'apellidoM' => 'required',
            // 'prefijo' => 'required',
            'num_control' => 'required|unique:users,num_control,' . Crypt::decrypt($this->route->parameter('usuario')),
            'email' => 'required|email|unique:users,email,' . Crypt::decrypt($this->route->parameter('usuario')),
        ];
    }
}
