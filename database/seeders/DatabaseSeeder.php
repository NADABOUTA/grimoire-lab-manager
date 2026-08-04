<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(['name' => 'Responsable (Créateur)', 'email' => 'responsable@test.com', 'password' => bcrypt('password')]);
        User::factory()->create(['name' => 'Chercheur 1', 'email' => 'chercheur@test.com', 'password' => bcrypt('password')]);
        User::factory()->create(['name' => 'Etudiant 1', 'email' => 'etudiant@test.com', 'password' => bcrypt('password')]);
    }
}
