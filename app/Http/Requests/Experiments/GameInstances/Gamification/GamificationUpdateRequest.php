<?php

namespace App\Http\Requests\Experiments\GameInstances\Gamification;

use Illuminate\Foundation\Http\FormRequest;

class GamificationUpdateRequest extends FormRequest
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
            'enable_rewards' => ['required', 'boolean'],
            'enable_badges' => ['required', 'boolean'],
            'enable_performance_chart' => ['required', 'boolean'],
            'enable_leaderboard' => ['required', 'boolean']
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
            'enable_rewards' => 'recompensas',
            'enable_badges' => 'medallas',
            'enable_performance_chart' => 'grafico de rendimiento de usuario',
            'enable_leaderboard' => 'ranking de juego'
        ];
    }
}
