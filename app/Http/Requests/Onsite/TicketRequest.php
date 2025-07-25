<?php

namespace App\Http\Requests\Onsite;

use App\Http\Requests\Request;

use Illuminate\Foundation\Http\FormRequest;


class TicketRequest extends FormRequest
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
            'reason_ticket_id' => ['required']
        ];
    }
}
