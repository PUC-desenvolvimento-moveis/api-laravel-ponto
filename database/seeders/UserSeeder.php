<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Seed the application's UserSeeder.
     */
    public function run(): void
    {
        User::create([
            'name' => 'igor frederico',
            'password' => '123',
            'email' => 'igor@example.com',
        ],
    );
    }
}
