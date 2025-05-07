<?php

namespace App\Http\Requests\Onsite;

use Illuminate\Foundation\Http\FormRequest;

class SucursalOnsiteRequest extends FormRequest
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
          'codigo_sucursal' => 'required',
          'razon_social' => 'required',
          'empresa_onsite_id' => 'required',
          'localidad_onsite_id' => 'required',
          'telefono_contacto' => '|numeric',					
        ];
    }
}
