<?php

namespace App\Http\Requests\Onsite;

use Illuminate\Foundation\Http\FormRequest;

class ReparacionOnsiteUpdateRequest extends FormRequest
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
			//'id_terminal' => 'required',
            'sucursal_onsite_id' => 'required',
			'id_empresa_onsite' => 'required',
			
			'id_tipo_servicio' => 'required',			
			'id_estado' => 'required',			
			//'cableado_cantidad_metros' => 'numeric',
			//'cableado_cantidad_fichas' => 'numeric',
			
			//'tipo_conexion_local' => 'required',
        ];
    }
}
