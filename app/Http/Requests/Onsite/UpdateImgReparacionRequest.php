<?php

namespace App\Http\Requests\Onsite;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImgReparacionRequest extends FormRequest
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
            'IMAGEN_ONSITE_1' => '',
            'IMAGEN_ONSITE_2' => '',
            'IMAGEN_ONSITE_3' => '',
            'IMAGEN_ONSITE_4' => '',
            'IMAGEN_ONSITE_5' => '',
            'IMAGEN_ONSITE_6' => '',
            'IMAGEN_ONSITE_7' => '',
            'IMAGEN_ONSITE_8' => '',
            'IMAGEN_ONSITE_9' => '',
            'IMAGEN_ONSITE_10' => '',
            'TIPO_IMAGEN_ONSITE_1' => '',
            'TIPO_IMAGEN_ONSITE_2' => '',
            'TIPO_IMAGEN_ONSITE_3' => '',
            'TIPO_IMAGEN_ONSITE_4' => '',
            'TIPO_IMAGEN_ONSITE_5' => '',
            'TIPO_IMAGEN_ONSITE_6' => '',
            'TIPO_IMAGEN_ONSITE_7' => '',
            'TIPO_IMAGEN_ONSITE_8' => '',
            'TIPO_IMAGEN_ONSITE_9' => '',
            'TIPO_IMAGEN_ONSITE_10' => ''
        ];
    }
}
