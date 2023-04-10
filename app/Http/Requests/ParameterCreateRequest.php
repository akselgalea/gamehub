<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ParameterCreateRequest extends FormRequest
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
            'game_id' => ['required', 'exists:games,id'],
        ];
    }

    public function messages(): array 
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'integer' => 'El campo :attribute debe tener un valor numerico',
            'max:255' => 'El campo :attribute no debe superar los 255 caracteres',
            'max:500' => 'El campo :attribute no debe superar los 500 caracteres',
            'exists' => 'Este :attribute no existe'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripcion',
            'file' => 'archivo',
            'game_id' => 'juego'
        ];
    }
}
