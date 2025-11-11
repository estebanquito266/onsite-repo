<?php

namespace App\Http\Requests\Onsite;

use App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ReparacionFilterCaseRequest extends FormRequest
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
            'id_empresa' => ['required'],
            'page' => ['required', 'numeric', 'min:1'],
            'per_page' => ['sometimes', 'numeric', 'min:1', 'max:500'],
        ];
    }

}
