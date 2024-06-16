<?php

namespace App\Http\Service;

use Log;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Ponto;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function store(Request $request)
    {
        $validate = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $verifica_ponto = Ponto::where('user_id', $validate['user_id'])
            ->orderBy('data_hora_inicial', 'desc')
            ->first();


        if ($verifica_ponto) {
            $data_hora_inicial = Carbon::parse($verifica_ponto->data_hora_inicial);
            // Verifica se a data e hora atual é anterior à data e hora inicial do ponto mais recente
            if (now()->lessThan($data_hora_inicial)) {
                return response()->json(['error' => 'Data e hora atual é anterior à data e hora inicial do ponto mais recente.'], 400);
            }

            // Verifica se a data atual é a mesma do ponto mais recente
            if ($data_hora_inicial->isSameDay(now())) {
                return response()->json(['error' => 'Já existe um ponto registrado para esse dia.'], 400);
            }
        }

        $ponto = Ponto::create([
            'data_hora_inicial' => now(),
            'user_id' => $validate['user_id'],
        ]);

        return $ponto;
    }

     public function update_hora_final(Request $request, $id)
    {
        $ponto = Ponto::find($id);
        if (!$ponto) {
            return null;
        }

        if ($ponto->data_hora_final != null) {
            $validate = $request->validate([
                'data_hora_final' => 'required|date_format:Y-m-d H:i:s',
                'user_id' => 'required|exists:users,id'
            ]);

            $dataHoraInicial = Carbon::parse($validate['data_hora_final']);
            $dataHoraFinal = Carbon::parse($request->data_hora_final);
            $diferenca = $dataHoraFinal->diffInMinutes($dataHoraInicial);

            $ponto->update([
                'data_hora_final' => $dataHoraFinal,
                'minutos_trabalhados_dia' => $diferenca,
                'user_id' => $validate['user_id'],
            ]);

            return $ponto;
        }

        return null;
    } 


    public function update(Request $request, $id)
    {
        $flag_update_hora_inicial = false;
        $ponto = Ponto::find($id);
        if (!$ponto) {
            return response()->json(['error' => 'Ponto não encontrado.'], 404);
        }

        if ($ponto->data_hora_inicial != null) {
            // Validar os dados de entrada
            $validate = $request->validate([
                'data_hora_inicial' => 'required|date_format:Y-m-d H:i:s',
                'data_hora_final' => 'nullable|date_format:Y-m-d H:i:s',
                'user_id' => 'required|exists:users,id'
            ]);

            $data_escolhida = Carbon::parse($validate['data_hora_inicial']);
            $dataHoraFinal = $request->has('data_hora_final') ? Carbon::parse($request->data_hora_final) : null;
            $data_inicial_banco = Carbon::parse($ponto->data_hora_inicial);

            $data_escolhidaTimestamp = $data_escolhida->timestamp;
            $data_inicial_bancoTimestamp = $data_inicial_banco->timestamp;

            if ($data_escolhidaTimestamp < $data_inicial_bancoTimestamp) {
                $flag_update_hora_inicial = true;
            }

            $diferenca = $dataHoraFinal ? $dataHoraFinal->diffInMinutes($data_escolhida) : null;

            if ($flag_update_hora_inicial===false) {
                // Atualizar o registro de ponto
                $ponto->update([
                    'data_hora_inicial' => $data_escolhida,
                    'minutos_trabalhados_dia' => $diferenca,
                ]);
                return response()->json($ponto);
            }

            if ($flag_update_hora_inicial === true) {
                $flag_update_hora_inicial = true;
                return response()->json([
                'flag'=>$flag_update_hora_inicial ,
                    'data_escolhida' => $data_escolhidaTimestamp,
                    'data_do_banco' => $data_inicial_bancoTimestamp,
                    'error' => 'A nova data e hora inicial deve ser maior que a existente.'
                ], 400);
            }
        }
        return response()->json(['error' => 'Ponto não possui data_hora_inicial.'], 400);
    }


    public function destroy($id): bool
    {
        $ponto = Ponto::find($id);
        if (!$ponto)
            return false;


        $ponto->delete();
        return true;
    }


    public function bater_ponto_final(Request $request, $id)
    {
        $ponto = Ponto::find($id);
        if (!$ponto)
            return null;

        $validate = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);


        $flag_ponto_dia = false;
        $data_hora_inicial = Carbon::parse($ponto->data_hora_inicial);

        $ponto_data_hora_final = $ponto->data_hora_final;

        $data_hora_final = Carbon::now();
        $user = User::find($ponto->user_id);

        $user->pontos->each(function ($pontos) use (&$flag_ponto_dia) {
            if (!empty($pontos->data_hora_final) && !empty($pontos->data_hora_inicial)) {

                return $flag_ponto_dia = true;
            }
        });

        if ($flag_ponto_dia) {
            return response()->json(['error' => 'Você já bateu seu ponto neste dia.'], 400);
        }

        // Verifica se a data atual é a mesma do ponto mais recente
        if ($ponto_data_hora_final != null) {
            return response()->json(['error' => 'Voce ja bateu seu ponto final.'], 400);
        }

        $horas_trabalhadas_em_minuto = $data_hora_inicial->diffInMinutes($data_hora_final);
        $ponto->update([
            'data_hora_final' => $data_hora_final,
            'minutos_trabalhados_dia' => $horas_trabalhadas_em_minuto,
            'user_id' => $validate['user_id'],
            'tipo' => 'saida',
        ]);
        return $ponto;
    }
}
