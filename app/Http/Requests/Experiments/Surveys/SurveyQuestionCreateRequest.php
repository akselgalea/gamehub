<?php

namespace App\Http\Requests\Experiments\Surveys;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SurveyQuestionCreateRequest extends FormRequest
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
            'question' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['likert', 'open'])],
            'minText' => 'nullable|required_if:type,likert|string|max:255',
            'maxText' => 'nullable|required_if:type,likert|string|max:255'
        ];
    }

    public function messages(): array 
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'integer' => 'El campo :attribute debe tener un valor numerico',
            'max' => 'El campo :attribute no debe superar los :max caracteres',
            'type.in' => 'El tipo seleccionado es invalido. Los tipos validos son: likert, pregunta abierta'
        ];
    }

    public function attributes(): array
    {
        return [
            'question' => 'encabezado',
            'type' => 'tipo',
            'minText' => 'Valor mínimo',
            'maxText' => 'Valor máximo'
        ];
    }
}
