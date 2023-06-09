<?php

namespace App\Http\Requests\Experiments\Surveys;

use Illuminate\Foundation\Http\FormRequest;

class SurveyDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth()->check() && Auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'surveyId' => 'required|exists:surveys,id',
            'password' => 'required|current_password'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'surveyId.exists' => 'La encuesta que intentas eliminar no existe',
            'current_password' => 'Contraseña incorrecta'
        ];
    }

    public function attributes(): array
    {
        return [
            'surveyId' => 'encuesta',
            'password' => 'contraseña'
        ];
    }
}
