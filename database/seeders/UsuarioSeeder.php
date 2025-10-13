<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'     => 'User Admin',
            'email'    => 'admin@email.com',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name'            => 'User Recepção',
            'email'           => 'recepcao@email.com'
        ]);

        User::factory()->create([
            'name'            => 'User Marcação',
            'email'           => 'marcacao@email.com'
        ]);
    }
}
