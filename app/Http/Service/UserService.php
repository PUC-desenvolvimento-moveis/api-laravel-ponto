<?php

namespace App\Http\Service;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function index(): ?Collection
    {
        if ($users = User::all())
            return $users;

        return null;
    }

    public function show($id): ?User
    {
        if ($user = User::find($id));
        return $user;

        return null;
    }


    public function login()
    {
    }


    public function getPontos($id): ?Collection
    {
        $user = User::find($id);
        if ($user->pontos)
            return $user->pontos;

        return null;
    }

    public function getAuth(Request $request) : ?User{
     return $request->user();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'cpf' => 'required|string|min:11|max:11',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'telefene' => $request->telefene ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        if (!empty($user))
            return $token;

        return null;
    }

    public function update(Request $request, $id): ?User
    {
        $user = User::find($id);
        if (!$user) {
            return null;
        }
        $user->name = $request->name ?? $user->name;
        $user->password = $request->password ?? $user->password;
        $user->email = $request->email ?? $user->email;
        $user->cpf = $request->cpf ?? $user->cpf;
        $user->telefene = $request->telefene ?? $user->telefene;
        $user->save();
        if ($user)
            return $user;
    }

    public function destroy($id): bool
    {
        $user = User::find($id);
        if (!$user)
            return false;

        $user->delete();
        return true;
    }
}
