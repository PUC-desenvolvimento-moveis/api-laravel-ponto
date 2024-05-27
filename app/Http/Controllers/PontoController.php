<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ponto;

use Illuminate\Http\Request;
use App\Http\Service\PontoService;

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
                "message" => "Ponto updated successfully"
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
            $ponto_final=$this->service->bater_ponto_final($request, $id);
            if ( $ponto_final== null) {
                return response()->json(["message" => "não encontrado"], 404);
            }

            return response()->json([
                "message" => "successfully",
                "data"=> $ponto_final
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage()
            ]);
        }
    }
}
