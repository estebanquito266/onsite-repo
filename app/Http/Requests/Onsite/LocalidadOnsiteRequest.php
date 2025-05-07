<?php

namespace App\Http\Requests\Onsite;

use Illuminate\Foundation\Http\FormRequest;

class LocalidadOnsiteRequest extends FormRequest
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
			'id_provincia' => 'required',
			'localidad' => 'required',
			
            'codigo' => 'numeric',
			
			'id_nivel' => 'required',
			
            'id_usuario_tecnico' => 'required',
            
            'atiende_desde' => 'required',
        ];
    }
}

?>