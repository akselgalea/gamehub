<?php

namespace App\Http\Requests\Experiment;

use Illuminate\Foundation\Http\FormRequest;

class ExperimentCreateRequest extends FormRequest
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
            'status' => ['required', Rule::in(['activo', 'detenido'])],
            'time_limit' => ['required', 'integer', 'nullable'],
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
            'status' => 'estado',
            'time_limit' => 'tiempo_limite'
        ];
    }
}
