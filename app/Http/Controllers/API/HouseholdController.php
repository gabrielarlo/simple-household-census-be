<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Household;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HouseholdController extends Controller
{
    public function getSuggestions(): JsonResponse
    {
        $provinces = Household::get()->pluck('province')->toArray();
        $cities = Household::get()->pluck('city')->toArray();
        $barangays = Household::get()->pluck('barangay')->toArray();

        return res(compact('provinces', 'cities', 'barangays'));
    }

    public function list(Request $request): JsonResponse
    {
        $search = $request->search ?? '';

        $q = Household::where('head', 'like', "%{$search}%")->orderBy('updated_at', 'DESC');
        if ($request->has('conducted_by_id')) {
            $q = $q->where('conducted_by_id', $request->conducted_by_id);
        }
        $h = $q->simplePaginate(10);

        return res($h);
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
        ])->stopOnFirstFailure(true);
        if ($v->fails()) {
            return vRes($v);
        }

        $h = Household::create($request->all() + [
            'conducted_by_id' => auth()->id(),
        ]);

        return res($h);
    }

    public function update(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'id' => 'required|exists:households,id',
            'province' => 'required',
            'city' => 'required',
            'barangay' => 'required',
            'respondent_name' => 'required',
            'head' => 'required',
            'member_count' => 'required|min:1',
            'address' => 'required',
        ])->stopOnFirstFailure(true);
        if ($v->fails()) {
            return vRes($v);
        }

        $h = Household::find($request->id);
        $h->update($request->except('id'));

        return res($h);
    }

    public function delete(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'id' => 'required|exists:households,id',
        ])->stopOnFirstFailure(true);
        if ($v->fails()) {
            return vRes($v);
        }

        $h = Household::find($request->id);
        $h->delete();

        return res();
    }
}
