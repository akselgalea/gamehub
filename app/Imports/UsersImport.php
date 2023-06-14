<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Validator;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        $user = [
            'name' => trim(ucwords(strtolower($row['nombres']))),
            'first_surname'     => trim(ucwords(mb_strtolower($row['apellido_paterno']))),
            'second_surname'     => ucwords(mb_strtolower(trim($row['apellido_materno']))),
            'email_verified_at' => Carbon::now()->toDateTimeString()
        ];
        // Guarda el tipo de usuario segun los tipos existentes
        if (strtolower($row['tipo']) == 'administrador'){
            $user['type'] = 'admin';
        } else if (strtolower($row['tipo']) == 'estudiante'){
            $user['type'] = 'student';
        }

        // Genera email
        if (!isset($row['correo']) || $row['correo'] == null) {
            $names = explode(' ', trim($row['nombres']));
            $user['email'] = strtolower(str_replace(' ', '.', $names[0] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']) . '@gh.cl');
        } else {
            $user['email'] = strtolower($row['correo']);
        }

        // Genera password
        if (!isset($row['contrasena']) || $row['contrasena'] == null) {
            $user['password'] = Hash::make(mb_strtolower(trim($row['apellido_paterno'])));
        } else {
            $user['password'] = Hash::make(trim($row['contrasena']));
        }

        // Valida que el email sea unico
        $validator = Validator::make($user, [
            'email' => 'required|unique:users,email',
            'password' => ['required'],
        ],[
            'required' => 'El campo :attribute es obligatorio',
            'unique' => 'El campo :attribute en la posicion :position ya esta en uso',
        ],[
            'email' => 'correo',
            'password' => 'contraseña'
        ]);

        // Si falla manda el error
        if ($validator->fails()) {
            return;
        }
        
        return new User($user);
    }

    
    public function rules(): array
    {
        return [
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'tipo' => 'required|string|in:administrador,estudiante,Administrador,Estudiante',
            // 'grade_id' => ['nullable','required_if:type,student','integer', 'exists:grades,id'],
            'correo' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@(?:[a-zA-Z0-9-]+\.)+[com|cl|net]{2,}$/'],
        ];

    }

    public function messages(): array 
    {
        return [
            'required' => 'El campo :attribute es obligatorio',
            'file' => 'Tienes que cargar un archivo',
            'mimes' => 'El archivo tiene que ser .csv o xlsx',
            'unique' => 'El campo :attribute en la posicion :position ya esta en uso',
        ];
    }


    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
        echo 'fallas';
    }
}
