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
        $user->password=$request->password;
        $user->email=$request->email;
        $user->cpf=$request->cpf;
        $user->telefone=$request->telefone;
        //$user->course = $request->course;
        $user->save();

        return response()->json([
            "message" => "user record created"
        ], 201);
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
    }
}
