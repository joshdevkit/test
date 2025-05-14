<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionOfficeRequest extends FormRequest
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
            'user_name' => 'required',
            'item' => 'required',  // Assuming 'brand' is a required string
            'purpose' => 'required',  // Validates that quantity is at least 1
            'date_borrowed' => 'required',  // Assuming 'condition' is a required string
            'status' => 'required',

        ];
    }
}
