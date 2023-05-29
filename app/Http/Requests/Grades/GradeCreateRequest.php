<?php

namespace App\Http\Requests\Grades;

use Illuminate\Foundation\Http\FormRequest;

class GradeCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'school_id' => ['required', 'numeric', 'exists:schools,id']
        ];
    }

    public function messages(): array 
    {
        return [
            'required' => 'El campo :attribute es obligatorio'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre'
        ];
    }
}
