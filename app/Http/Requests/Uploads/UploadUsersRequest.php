<?php

namespace App\Http\Requests\Uploads;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UploadUsersRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_file' => 'required|file|mimes:csv,xlsx',
        ];
    }

    public function messages(): array 
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'file' => 'Tienes que cargar un archivo',
            'mimes' => 'El archivo tiene que ser .csv o xlsx',
        ];
    }
}