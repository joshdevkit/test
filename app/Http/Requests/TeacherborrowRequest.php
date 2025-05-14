<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherborrowRequest extends FormRequest
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
            'items.*.item' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'dateNeeded' => 'required|date|after:dateFiled',
            'subject' => 'required|integer',
            'courseYear' => 'required|string',
            'activityTitle' => 'required|string',
            'equipment' => 'required|string',
            'qty' => 'required|integer|min:1',

        ];
    }
}
