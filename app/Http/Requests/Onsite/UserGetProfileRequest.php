<?php

namespace App\Http\Requests\Onsite;

use App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserGetProfileRequest extends FormRequest
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
            'user_id' => ['required','integer','exists:users,id'],
        ];
    }

 
    public function messages()
    {
        return [
            'user_id.exists' => 'user_id no encontrado.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->route('user_id'),
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
