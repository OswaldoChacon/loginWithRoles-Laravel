<?php

namespace App\Http\Requests\Horarios;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Crypt;

class EditarHorarioRequest extends FormRequest
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
        //dd(json_decode($this));
        return [
            //.$this->route->parameter('horario_foro').'
            'fecha'     => 'required|unique:fechas_foros,fecha,'.Crypt::decrypt($this->route->parameter('horario_foro')).'|date|after_or_equal:' . Carbon::now()->toDateString(), '|unique',
            'hora_inicio'  => 'required',
            'hora_termino'   => 'required|after:hora_inicio'
        ];
    }
}
