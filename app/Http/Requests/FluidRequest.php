<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FluidRequest extends FormRequest
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
            'equipment' => 'required|min:5|max:255',
            'brand' => 'required|string|max:255',  // Assuming 'brand' is a required string
            'description' => 'required',
            'quantity' => 'required|numeric|min:1',  // Validates that quantity is at least 1
            'condition' => 'required|string|max:255',  // Assuming 'condition' is a required string
            'date_acquired' => 'required|date',
            'unit' => 'required',  // Ensure disposal date is not before acquired date
  
        ];
    }
}
