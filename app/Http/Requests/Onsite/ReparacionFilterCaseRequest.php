<?php

namespace App\Http\Requests\Onsite;

use App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'company_id' => ['required', 'numeric'],
            'case'       => ['required', 'numeric', 'in:1'], 
            'id_empresa' => ['required'],
            'page' => ['required', 'numeric', 'min:1'],
            'per_page' => ['sometimes', 'numeric', 'min:1', 'max:500'],
        ];
    }

 
    public function messages()
    {
        return [
            'company_id.numeric' => 'company_id must be numeric.',
            'case.numeric'       => 'case must be numeric.',
            'case.in'            => 'case not available.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'company_id' => $this->route('company_id'),
            'case' => $this->route('case'),
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
