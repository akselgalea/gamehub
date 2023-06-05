<?php

namespace App\Http\Requests\Experiments\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserDisassociateRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'experiment_id' => ['required', 'exists:experiments,id'],
        ];
    }

    public function messages(): array 
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'integer' => 'El campo :attribute debe tener un valor numerico',
            'max:255' => 'El campo :attribute no debe superar los 255 caracteres',
            'user_id.exist' => 'El :attribute no existe',
            'experiment_id.exist' => 'Ocurrio un problema al buscar el :attribute, ya que no existe',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'usuario',
            'experiment_id' => 'experimento',
        ];
    }
}
