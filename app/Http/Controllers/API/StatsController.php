<?php

namespace App\Http\Controllers\API;

use App\Enums\CategoryEnum;
use App\Http\Controllers\Controller;
use App\Models\HouseholdMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatsController extends Controller
{
    public function counts(): JsonResponse
    {
        $senior_date = now()->subYears(60);
        $seniors = HouseholdMember::where('birth_date', '<=', $senior_date)->count();
        $pwds = HouseholdMember::where('is_pwd', true)->count();
        $solo_parents = HouseholdMember::where('is_solo_parent', true)->count();

        return res(compact('seniors', 'pwds', 'solo_parents'));
    }

    public function filter(Request $request)
    {
        $search = $request->search ?? '';

        $v = Validator::make($request->all(), [
            'category' => 'required|enum:'.CategoryEnum::class,
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $q = HouseholdMember::whereLike(['first_name', 'middle_name', 'last_name'], $search);
        if ($request->category->equals(CategoryEnum::SENIOR())) {
            $hm = $q->get()->filter(function ($value) {
                return $value->age() >= 60;
            });
        } elseif ($request->category->equals(CategoryEnum::PWD())) {
            $hm = $q->where('is_pwd', true)->get();
        } elseif ($request->category->equals(CategoryEnum::SOLO())) {
            $hm = $q->where('is_solo_parent', true)->get();
        } else {
            return eRes('Invalid category!');
        }

        return res($hm);
    }
}
