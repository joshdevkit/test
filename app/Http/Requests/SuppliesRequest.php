<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuppliesRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item' => 'required',
            'brand_description' => 'required',
            'location' => 'required',
            'unit' => 'required',
            'date_delivered' => 'required|date',
            'serial_no' =>  'required|array',
            'serial_no.*' => 'string',
        ];
    }
}
