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

  /*   public function index()
    {
        try {
            $this->service->index();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function show($id)
    {
        try {
            $this->service->show($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            $this->service->store($request);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    
    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy($id)
    {
    } */


    public function  index()
    {
        $users = User::all();
        return response()->json($users); 
    }

    public function getPontos(int $id){
        return response()->json($this->service->getPontos($id),201);        
    }

    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->cpf = $request->cpf;
        $user->telefene = $request->telefene;
        $token = $request->session()->token();
        $user->remember_token= $token = csrf_token();
        $user->save();

        return response()->json([
            "message" => "user record created"
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }
        $user->name = $request->name ?? $user->name;
        $user->password = $request->password ?? $user->password;
        $user->email = $request->email ?? $user->email;
        $user->cpf = $request->cpf ?? $user->cpf;
        $user->telefone = $request->telefone ?? $user->telefone;
        $user->save();
        return response()->json([
            "message" => "User updated successfully"
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }
        $user->delete();
        return response()->json([
            "message" => "User deleted successfully"
        ], 200);
    }
}
