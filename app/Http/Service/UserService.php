<?php

namespace App\Http\Service;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
            return null;

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $token;
    }


    public function get_pontos($id): ?Collection
    {
        $user = User::find($id);
        if ($user->pontos)
            return $user->pontos;

        return null;
    }

    public function auth(Request $request): ?User
    {
        return $request->user();
    }

    public function store(Request $request): ?User
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'cpf' => 'required|string|min:11|max:11|unique:users',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telefene' => $request->telefene ?? null,
            'cpf' => $validated['cpf'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        if (!empty($user))
            return $user;

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
        if(!$user)
        return false;

        $user->delete();
        return true;
    }
}
