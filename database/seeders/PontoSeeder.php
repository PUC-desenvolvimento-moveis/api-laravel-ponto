<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ponto;
use Illuminate\Database\Seeder;

class PontoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Obter todos os usuários
       $users = User::all();

       // Para cada usuário, criar múltiplas apropriações
       foreach ($users as $user) {
           Ponto::factory()->count(60)->create(['user_id' => $user->id]);
       }
    }
}
