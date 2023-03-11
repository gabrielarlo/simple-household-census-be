<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Household;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HouseholdController extends Controller
{
    public function list(): JsonResponse
    {
        $p = Household::simplePaginate(10);

        return res($p);
    }

    public function create(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
            'respondent_name' => 'required',
            'head' => 'required',
            'member_count' => 'required|min:1',
            'address' => 'required',
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $p = Household::create($request->all() + [
            'conducted_by_id' => auth()->id(),
        ]);

        return res($p);
    }

    public function update(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'id' => 'required|exists:profiles,id',
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
            'respondent_name' => 'required',
            'head' => 'required',
            'member_count' => 'required|min:1',
            'address' => 'required',
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $p = Household::find($request->id);
        $p->update($request->except('id'));

        return res($p);
    }

    public function delete(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'id' => 'required|exists:profiles,id',
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $p = Household::find($request->id);
        $p->delete();

        return res();
    }
}
