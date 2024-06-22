<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Ponto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ponto>
 */
class PontoFactory extends Factory
{
    protected $model = Ponto::class;

    public function definition()
    {
       // Define a data_hora_inicial
       $dataHoraInicial = $this->faker->dateTimeThisYear();

       // Define a data_hora_final como 1 a 4 horas depois da data_hora_inicial
       $dataHoraFinal = (clone $dataHoraInicial)->modify('+'.rand(1, 8).' hours');

       return [
           'data_hora_inicial' => $dataHoraInicial,
           'data_hora_final' => $dataHoraFinal,
           'minutos_trabalhados_dia' => ($dataHoraFinal->getTimestamp() - $dataHoraInicial->getTimestamp()) / 60,
           'user_id' => User::factory(), // cria um usuário associado automaticamente
           'tipo' => $this->faker->randomElement(['entrada', 'saida']),
       ];
    }
}
