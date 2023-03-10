<?php

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

function res($data = [], string $msg = 'Success', int $code = 200): JsonResponse
{
    return response()->json([
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ]);
}

function vRes(Validator $validator): JsonResponse
{
    return res($validator->errors(), 'Validation Failed', 412);
}

function eRes(string $msg = '', int $code = 400, $data = null): JsonResponse
{
    return res($data, $msg, $code);
}

function generateUserOTP(): string
{
    $found = true;
    $otp = mt_rand(000000, 999999);
    $otp = str_pad($otp, 6, '0', STR_PAD_LEFT);

    while ($found) {
        $u = User::where(['otp' => $otp])->first();
        if (! $u) {
            $found = false;
        } else {
            $otp = mt_rand(000000, 999999);
            $otp = str_pad($otp, 6, '0', STR_PAD_LEFT);
        }
    }

    return $otp;
}
