<?php

namespace App\Http\Requests\Games;

use Illuminate\Foundation\Http\FormRequest;

class GameCreateRequest extends FormRequest
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
            'slug' => ['unique:game_instances'],
            'description' => ['nullable', 'max:500'],
            'file' => ['required', 'mimes:zip'],
            'gm2game' => ['required', 'boolean'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    public function messages(): array 
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'integer' => 'El campo :attribute debe tener un valor numerico',
            'max:255' => 'El campo :attribute no debe superar los 255 caracteres',
            'file.mimes' => 'Solo se aceptan archivos .zip'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripcion',
            'file' => 'archivo',
            'category_id' => 'categoria'
        ];
    }
}
