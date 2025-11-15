<?php

namespace App\Http\Requests\Onsite;

use App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTecLocalidadOnsiteRequest extends FormRequest
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
            'localidad_id' => ['required', 'integer','exists:localidades_onsite,id'],
            'id_usuario_tecnico' => ['required','integer','exists:users,id'],
        ];
    }

 
    public function messages()
    {
        return [
            'localidad_id.exists' => 'localidad_id no encontrado.',
            'id_usuario_tecnico.exists' => 'id_usuario_tecnico no encontrado.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'localidad_id' => $this->route('localidad_id'),
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422)
        );
    }


}
