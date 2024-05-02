<?php

namespace App\Http\Service;

use Illuminate\Http\Request;

use App\Models\User;

class UserService
{
    public function  index()
    {
        $users = User::all();
        return response()->json($users);
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
        $user->telefone = $request->telefone;
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
