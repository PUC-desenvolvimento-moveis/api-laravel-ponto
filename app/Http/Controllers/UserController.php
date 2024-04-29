<?php

namespace App\Http\Controllers;
use App\Http\Service\UserService;

use Illuminate\Http\Request;

use App\Models\User;

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

    public function update(Request $request, $id)
    {
        
    }

    public function destroy($id)
    {
    }
}
