<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ {User};

class UserCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:admin,student',
            'grade_id' => ['required_if:type,student','integer', 'exists:grades,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class, 'regex:/^[a-zA-Z0-9._%+-]+@(?:[a-zA-Z0-9-]+\.)+[com|cl|net]{2,}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function messages(): array 
    {
        return [
            'grade_id.required_if' => 'El grado es obligatorio cuando el tipo de usuario es estudiante',
            'required' => 'El campo :attribute es obligatorio',
            'integer' => 'El campo :attribute debe tener un valor numerico',
            'type.in' => 'El tipo de usuario ingresado no es valido',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'email' => 'El :attribute debe ser un correo valido',
            'email.unique' => 'El :attribute ya esta registrado',
            'email.regex' => 'El :attribute debe tener el formato de correo',
            'max:255' => 'El campo :attribute no debe superar los 255 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'type' => 'tipo',
            'grade_id' => 'grado',
            'email' => 'correo',
            'password' => 'contraseña',
        ];
    }
}
