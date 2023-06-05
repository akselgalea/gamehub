<?php

namespace App\Http\Requests\Experiments\EntryPoints;

use Illuminate\Foundation\Http\FormRequest;

class EntryPointDeleteRequest extends FormRequest
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
        $id = $this->request->get('id');

        return [
            'id' => 'required|exists:entry_points,id',
            'name' => "required|exists:entry_points,name,id,$id"
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'name.exists' => 'El nombre que ingresaste no coincide con el solicitado.'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre'
        ];
    }
}