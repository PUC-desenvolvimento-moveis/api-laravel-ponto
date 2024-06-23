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
        User::factory()->count(30)->create();
    }
}
