<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Dos usuarios con sus animales, para poder mirar el refugio desde dos
     * sesiones distintas.
     */
    public function run(): void
    {
        $ana = User::create([
            'name' => 'Ana', 'email' => 'ana@refugio.cl', 'password' => 'refugio123',
        ]);

        $beto = User::create([
            'name' => 'Beto', 'email' => 'beto@refugio.cl', 'password' => 'refugio123',
        ]);

        $ana->animales()->create(['nombre' => 'Firulais', 'edad' => 3, 'especie' => 'perro']);
        $ana->animales()->create(['nombre' => 'Michi', 'edad' => 2, 'especie' => 'gato']);
        $beto->animales()->create(['nombre' => 'Lola', 'edad' => 4, 'especie' => 'pajaro']);
    }
}
