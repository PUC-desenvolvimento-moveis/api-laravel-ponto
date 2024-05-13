<?php

namespace App\Http\Controllers;

use App\Models\Ponto;
use Illuminate\Http\Request;

use Carbon\Carbon;

class PontoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pontos = Ponto::all();
        return response()->json($pontos);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'data_hora_inicial' => 'required|date_format:Y-m-d H:i:s',
            'user_id' => 'required|exists:users,id'
        ]);

        $ponto = Ponto::create([
            'data_hora_inicial' => $validatedData['data_hora_inicial'],
            'data_hora_final' => $request->data_hora_final,
            'user_id' => $validatedData['user_id'],
        ]);
        
            return response()->json(['message' => 'Ponto criado com sucesso', 'ponto' => $ponto], 201);         
    }

    
    public function show($id)
    {
        $ponto = Ponto::find($id);
        return response()->json($ponto);
    }

    public function update(Request $request, $id)
    {
        $ponto = Ponto::findOrFail($id);

        $validatedData = $request->validate([
            'data_hora_inicial' => 'required|date_format:Y-m-d H:i:s',
            'data_hora_final' => 'required|date_format:Y-m-d H:i:s',
            'user_id' => 'required|exists:users,id'
        ]);

        $dataHoraInicial = Carbon::parse($validatedData['data_hora_inicial']);
        $dataHoraFinal = Carbon::parse($validatedData['data_hora_final']);
        $horasTrabalhadas = $dataHoraFinal->diffInMinutes($dataHoraInicial);

        $ponto->update([
            'data_hora_inicial' => $dataHoraInicial,
            'data_hora_final' => $dataHoraFinal,
            'horas_trabalhadas_dia' => $horasTrabalhadas,
            'user_id' => $validatedData['user_id'],
        ]);

        return response()->json(['message' => 'Ponto atualizado com sucesso', 'ponto' => $ponto], 201);
    }


    public function destroy($id)
    {
        $ponto = Ponto::findOrFail($id);
        $ponto->delete();

        return response()->json(['message' => 'Ponto removido com sucesso']);
    }
}
