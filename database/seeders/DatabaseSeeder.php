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
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@pucv.cl',
        ]);
        
        \App\Models\User::factory()->create([
            'name' => 'tester',
            'email' => 'tester@pucv.cl',
        ]);

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
