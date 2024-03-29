<?php

namespace App\Http\Requests\Games\Parameters;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParameterUpdateRequest extends FormRequest
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
        $types = [
            'int',
            'float',
            'string',
            'boolean'
        ];

        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in($types)],
            'description' => ['required', 'max:500'],
        ];
    }

    public function messages(): array 
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'integer' => 'El campo :attribute debe tener un valor numerico',
            'max:255' => 'El campo :attribute no debe superar los 255 caracteres',
            'max:500' => 'El campo :attribute no debe superar los 500 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'type' => 'tipo',
            'description' => 'descripcion',
        ];
    }
}
