<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ForoRequest extends FormRequest
{
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
            'no_foro'=>'required|numeric|unique:foros',
            'nombre'=>'required',
            'periodo'=>'required',
            'anio'=>'required|integer|min:'.date('Y').'|max:'.(date('Y')+2)
        ];
    }
}
