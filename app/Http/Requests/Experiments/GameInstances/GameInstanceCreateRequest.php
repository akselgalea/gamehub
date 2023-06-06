<?php

namespace App\Http\Requests\Experiments\GameInstances;

use Illuminate\Foundation\Http\FormRequest;

class GameInstanceCreateRequest extends FormRequest
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
            'description' => ['nullable', 'max:500'],
            'game_id' => ['required', 'integer', 'exists:games,id'],
            'experiment_id' => ['required', 'integer', 'exists:experiments,id'],
        ];
    }

    public function messages(): array 
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'integer' => 'El campo :attribute debe tener un valor numerico',
            'max:255' => 'El campo :attribute no debe superar los 255 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripcion',
            'game_id' => 'juego'
        ];
    }
}
