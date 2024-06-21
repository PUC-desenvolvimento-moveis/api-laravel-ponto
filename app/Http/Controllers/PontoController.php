<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;

use App\Models\Ponto;
use Illuminate\Http\Request;
use App\Http\Service\PontoService;
use Illuminate\Support\Facades\DB;

class PontoController extends Controller
{
    protected PontoService $service;

    public function __construct(PontoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $pontos = $this->service->index();
            return response()->json($pontos);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        try {
            $ponto = $this->service->show($id);
            if ($ponto) {
                return response()->json($ponto);
            }
            return response()->json(["message" => "Ponto not found"], 404);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ]);
        }
    }

    public function ponto_inicial(Request $request)
    {
        try {
            $ponto = $this->service->store($request);
            if ($ponto != null) {
                return response()->json([
                    'data' => $ponto,
                ], 201);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if ($this->service->update($request, $id) == null) {
                return response()->json(["message" => "Ponto not found"], 404);
            }

            return response()->json([
                "request" => $this->service->update($request, $id)
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            if (!$this->service->destroy($id)) {
                return response()->json(["message" => "Ponto not found"], 404);
            }

            return response()->json([
                "message" => "Ponto deleted successfully"
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ]);
        }
    }

    public function bater_ponto_final(Request $request, $id)
    {
        try {
            $ponto_final = $this->service->bater_ponto_final($request, $id);
            if ($ponto_final == null) {
                return response()->json(["message" => "Ponto não encontrado"], 404);
            }

            return response()->json([
                "message" => "successfully",
                "data" => $ponto_final
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ]);
        }
    }


    public function soma_minutos_trabalhados_por_data($id, $data)
    {
        $total_minutos_trabalhados = 0;
        $user = User::find($id);
        $pontos = $user->pontos;
        $pontos = $pontos->whereBetween('created_at', [
            Carbon::parse($data)->startOfDay(),
            Carbon::parse($data)->endOfDay()
        ]);
        $pontos->each(function ($item) use (&$total_minutos_trabalhados) {
            $total_minutos_trabalhados += $item->minutos_trabalhados_dia;
        });
    
        // Converter minutos para horas, minutos e segundos
        $horas = floor($total_minutos_trabalhados / 60);
        $minutos = $total_minutos_trabalhados % 60;
        $segundos = 0; // Sempre será 0, pois não temos os segundos
        $microssegundos = 0; // Sempre será 0, pois não temos os microssegundos
    
        // Formatar a string no formato 00:01:02.0000
        $formatted_time = sprintf('%02d:%02d:%02d.%04d', $horas, $minutos, $segundos, $microssegundos);
    
        if ($total_minutos_trabalhados != 0) {
            return response()->json([
                "message" => "successfully",
                "total_horas_trabalhadas" => $formatted_time
            ], 201);
        }
    
        return response()->json([
            "error" => 'erro ao somar minutos trabalhados'
        ], 201);
    }


    public function soma_minutos_trabalhados_por_periodo($id, $data_inicial, $data_final)
    {
        $total_minutos_trabalhados = 0;
        $user = User::find($id);
        $pontos = $user->pontos;
        $pontos = $pontos->whereBetween('created_at', [
            Carbon::parse($data_inicial)->startOfDay(),
            Carbon::parse($data_final)->endOfDay()
        ]);
        $pontos->each(function ($item) use (&$total_minutos_trabalhados) {
            $total_minutos_trabalhados += $item->minutos_trabalhados_dia;
        });

        // Converter minutos para horas, minutos e segundos
        $horas = floor($total_minutos_trabalhados / 60);
        $minutos = $total_minutos_trabalhados % 60;
        $segundos = 0; // Sempre será 0, pois não temos os segundos
        $microssegundos = 0; // Sempre será 0, pois não temos os microssegundos

        // Formatar a string no formato 00:01:02.0000
        $formatted_time = sprintf('%02d:%02d:%02d.%04d', $horas, $minutos, $segundos, $microssegundos);

        if ($total_minutos_trabalhados != 0) {
            return response()->json([
                "message" => "successfully",
                "total_horas_trabalhadas" => $formatted_time
            ], 201);
        }

        return response()->json([
            "error" => 'erro ao somar minutos trabalhados'
        ], 201);
    }



    public function soma_minutos_trabalhados($id)
    {

        $total_minutos_trabalhados = 0;
        $user = User::find($id);
        $user->pontos->each(function ($item) use (&$total_minutos_trabalhados) {
            $total_minutos_trabalhados += $item->minutos_trabalhados_dia;
        });
        // Converter minutos para horas, minutos e segundos
        $horas = floor($total_minutos_trabalhados / 60);
        $minutos = $total_minutos_trabalhados % 60;
        $segundos = 0; // Sempre será 0, pois não temos os segundos
        $microssegundos = 0; // Sempre será 0, pois não temos os microssegundos

        // Formatar a string no formato 00:01:02.0000
        $formatted_time = sprintf('%02d:%02d:%02d.%04d', $horas, $minutos, $segundos, $microssegundos);

        if ($total_minutos_trabalhados != 0) {
            return response()->json([
                "message" => "successfully",
                "total_horas_trabalhadas" => $formatted_time
            ], 201);
        }

        return response()->json([
            "error" => 'erro ao somar minutos trabalhados'
        ], 201);
    }
}
