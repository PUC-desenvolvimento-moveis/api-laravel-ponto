<?php

namespace App\Http\Service;

use Illuminate\Http\Request;

use App\Models\User;
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


    public function getPontos($id)
    {
        $user = User::find($id);
        return $user->pontos;
    }

    public function store(Request $request): ?User
    {
        $user = new User;
        $user->name = $request->name;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->cpf = $request->cpf;
        $user->telefene = $request->telefene;
        $token = $request->session()->token();
        $user->remember_token = $token = csrf_token();
        $user->save();

        if ($user)
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
        if (!$user)
            return false;

        $user->delete();
        return true;
    }
}
