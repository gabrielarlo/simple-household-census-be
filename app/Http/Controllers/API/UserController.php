<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function list(): JsonResponse
    {
        $u = User::get();

        return res($u);
    }
}
