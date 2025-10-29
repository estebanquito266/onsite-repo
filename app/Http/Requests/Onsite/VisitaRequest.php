<?php

namespace App\Http\Requests\Onsite;

use App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class VisitaRequest extends FormRequest
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
            'fecha' => ['required', 'date'],
            'fecha_vencimiento' => ['required', 'date'],
            'fecha_nuevo_vencimiento' => ['required', 'date'],
            'motivo' => ['nullable', 'string']
        ];
    }

}
