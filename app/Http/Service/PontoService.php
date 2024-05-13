<?php

namespace App\Http\Service;

use Carbon\Carbon;

use App\Models\User;
use App\Models\Ponto;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class PontoService
{
    public function index(): Collection
    {
        return Ponto::all();
    }

    public function show($id): ?Ponto
    {
        return Ponto::find($id);
    }

    public function store(Request $request): ?Ponto
    {
        $validate = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $verifica_ponto = Ponto::where('user_id', $validate['user_id'])
            ->orderBy('data_hora_inicial', 'desc')
            ->first();

        if ($verifica_ponto && now() < $verifica_ponto->data_hora_inicial)
            return null;


        $ponto = Ponto::create([
            'data_hora_inicial' => now(),
            'user_id' => $validate['user_id'],
        ]);

        return $ponto;
    }

    public function update(Request $request, $id): ?Ponto
    {
        $ponto = Ponto::find($id);
        if (!$ponto) {
            return null;
        }

        $validate = $request->validate([
            'data_hora_inicial' => 'required|date_format:Y-m-d H:i:s',
            'data_hora_final' => 'required|date_format:Y-m-d H:i:s',
            'user_id' => 'required|exists:users,id'
        ]);

        $dataHoraInicial = Carbon::parse($validate['data_hora_inicial']);
        $dataHoraFinal = Carbon::parse($validate['data_hora_final']);

        $horasTrabalhadas = $dataHoraInicial->diffInMinutes($dataHoraFinal);

        $ponto->update([
            'data_hora_inicial' => $dataHoraInicial,
            'data_hora_final' => $dataHoraFinal,
            'horas_trabalhadas_dia' => $horasTrabalhadas,
            'user_id' => $validate['user_id'],
        ]);

        return $ponto;
    }

    public function destroy($id): bool
    {
        $ponto = Ponto::find($id);
        if (!$ponto)
            return false;


        $ponto->delete();
        return true;
    }


    public function bater_ponto_final(Request $request, $id): ?Ponto
    {
        $ponto = Ponto::find($id);
        if (!$ponto) {
            return null;
        }

        $validate = $request->validate([

            'user_id' => 'required|exists:users,id'
        ]);

        $data_hora_inicial = Carbon::parse($ponto->data_hora_inicial);
        $data_hora_final = Carbon::parse(now());

        $horas_trabalhadas_em_minuto = $data_hora_final->diffInMinutes($data_hora_inicial);

        $ponto->update([
            'data_hora_final' => $data_hora_final,
            'horas_trabalhadas_dia' => $horas_trabalhadas_em_minuto,
            'user_id' => $validate['user_id'],
        ]);

        return $ponto;
    }
}
