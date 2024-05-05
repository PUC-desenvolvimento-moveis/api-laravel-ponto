<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Service\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }


    public function  index()
    {
        try {
            if ($this->service->index())
                return response()->json($this->service->index(), 201);

            return response()->json(["message" => "User not found"], 404);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th
            ]);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->service->show($id);
            if ($user)
                return response()->json($user, 201);

            return response()->json(["message" => "User not found"], 404);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = $this->service->store($request);
            if ($user) {
                return response()->json([
                    "message" => "user record created"
                ], 201);
            }
            return response()->json([
                "message" => "not record"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if ($this->service->update($request, $id) == null)
                return response()->json(["message" => "User not found"], 404);

            return response()->json([
                "message" => "User updated successfully"
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            if (!$this->service->destroy($id))
                return response()->json(["message" => "User not found"], 404);

            return response()->json([
                "message" => "User deleted successfully"
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th
            ]);
        }
    }


    public function getPontos(int $id)
    {
        return response()->json($this->service->getPontos($id), 201);
    }
}
