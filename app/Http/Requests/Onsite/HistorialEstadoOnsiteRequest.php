<?php

namespace App\Http\Requests\Onsite;

use Illuminate\Foundation\Http\FormRequest;

class HistorialEstadoOnsiteRequest extends FormRequest
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
            'id_reparacion' => 'required',
			'id_estado' => 'required',
			'id_usuario' => 'required',
			'observacion' => 'required',			
        ];
    }
}
