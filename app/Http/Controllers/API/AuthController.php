<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\SendOtp;
use App\Models\OauthAccessToken;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $u = User::where('email', $request->email)->first();
        if (! Hash::check($request->password, $u->password)) {
            return eRes('Invalid password!');
        }

        // Delete existing user
        $tokenName = 'Postman Token';
        $userAgent = request()->header('user-agent');
        if (! str_contains(strtolower($userAgent), 'postman')) {
            $tokenName = 'App Token';
        }
        PersonalAccessToken::where(['name' => $tokenName, 'tokenable_id' => $u->id])->delete();

        $token = $u->createToken($tokenName, ['admin'])->plainTextToken;
        $user = new UserResource($u);

        return res(compact('token', 'user'));
    }

    public function requestNewPassword(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $u = User::where('email', $request->email)->first();
        $u->otp = generateUserOTP();
        $u->save();

        Mail::to($u->email)->send(new SendOtp($u));

        return res();
    }

    public function setNewPassword(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'otp' => 'required|min:6|max:6|exists:users,otp',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $u = User::where('otp', $request->otp)->first();
        $u->otp = null;
        $u->password = $request->password;
        $u->save();

        return res();
    }
}
