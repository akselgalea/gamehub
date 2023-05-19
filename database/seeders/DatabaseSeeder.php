<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*---------- Poblado de roles --------- */

        DB::table('roles')->insert([
            'name' => 'usuario'
        ]);

        DB::table('roles')->insert([
            'name' => 'administrador'
        ]);

        /*---------- Poblado de mails --------- */

        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'rol_id' => '1',
            'email' => 'admin@pucv.cl',
        ]);
        
        \App\Models\User::factory()->create([
            'name' => 'tester',
            'rol_id' => '1',
            'email' => 'tester@pucv.cl',
        ]);

        /*---------- Poblado de categorias --------- */

        DB::table('categories')->insert([
            'name' => 'matematicas'
        ]);

        DB::table('categories')->insert([
            'name' => 'lenguaje'
        ]);

        DB::table('categories')->insert([
            'name' => 'ingles'
        ]);

        DB::table('categories')->insert([
            'name' => 'otros'
        ]);

    }
}
