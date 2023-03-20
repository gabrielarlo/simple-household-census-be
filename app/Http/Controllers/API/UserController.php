<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function list(): JsonResponse
    {
        $u = User::get();

        return res($u);
    }

    public function updateMyAccount(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $u = User::find(auth()->id());
        $u->name = $request->name;
        $u->save();

        return res($u);
    }

    public function register(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $u = User::create($request->all() + [
            'email_verified_at' => now(),
        ]);

        return res($u);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        if ($request->user_id == auth()->id()) {
            return eRes('You cant reset your password!');
        }

        $new_password = generateUserOTP();
        $u = User::find($request->user_id);
        $u->password = $new_password;
        $u->save();

        return res(compact('new_password'));
    }

    public function changePassword(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $u = User::find(auth()->id());
        if (! Hash::check($request->current_password, $u->password)) {
            return eRes('Invalid current password!');
        }

        $u->password = $request->password;
        $u->save();

        return res();
    }
}
