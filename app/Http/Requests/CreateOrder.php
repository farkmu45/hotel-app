<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id,deleted_at,NULL',
            'room_type_id' => 'required|exists:room_types,id,deleted_at,NULL',
            'check_in_date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'check_out_date' => 'required|date|date_format:Y-m-d|after:check_in_date',
        ];
    }
}
