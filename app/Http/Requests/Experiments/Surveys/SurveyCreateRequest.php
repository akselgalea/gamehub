<?php

namespace App\Http\Requests\Experiments\Surveys;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SurveyCreateRequest extends FormRequest
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
            'type' => ['required', Rule::in(['test', 'survey'])],
            'init_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:init_date'],
            'experiment_id' => ['required', 'integer', 'exists:experiments,id']
        ];
    }

    public function messages(): array 
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'integer' => 'El campo :attribute debe tener un valor numerico',
            'max' => 'El campo :attribute no debe superar los :max caracteres',
            'date' => 'El campo :attribute debe ser de tipo fecha',
            'init_date.after_or_equal' => 'La fecha de inicio debe ser mayor o igual a la fecha actual',
            'end_date.after_or_equal' => 'La fecha de término debe ser mayor o igual a la fecha de inicio',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripcion',
            'type' => 'tipo',
            'init_date' => 'fecha de inicio',
            'end_date' => 'fecha de término',
        ];
    }
}