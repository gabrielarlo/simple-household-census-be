<?php

namespace App\Http\Controllers\API;

use App\Enums\CategoryEnum;
use App\Enums\GenderEnum;
use App\Enums\RelationshipEnum;
use App\Http\Controllers\Controller;
use App\Models\Household;
use App\Models\HouseholdMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class HouseholdMemberController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'household_hashid' => 'required',
        ])->stopOnFirstFailure(true);
        if ($v->fails()) {
            return vRes($v);
        }

        $household_id = Hashids::decode($request->household_hashid);
        $household = Household::where('id', $household_id)->first();
        if (! $household) {
            return eRes('Household not found!');
        }

        $q = HouseholdMember::where('household_id', $household->id);
        if ($request->has('category')) {
            if ($request->category == CategoryEnum::SENIOR()->value) {
                $senior_date = now()->subYears(60);
                $q = $q->where('birth_date', '<=', $senior_date);
            } elseif ($request->category == CategoryEnum::PWD()->value) {
                $q = $q->where('is_pwd', true);
            } elseif ($request->category == CategoryEnum::SOLO()->value) {
                $q = $q->where('is_solo_parent', true);
            }
        }
        $members = $q->get();

        return res(compact('household', 'members'));
    }

    public function add(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'household_id' => 'required|exists:households,id',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'relationship_to_head' => 'required|enum:'.RelationshipEnum::class,
            'sex' => 'required|enum:'.GenderEnum::class,
            'is_lgbtqm' => 'required|boolean',
            'birth_date' => 'required|date|date_format:Y-m-d',
            'place_of_birth' => 'required',
            'is_pwd' => 'required|boolean',
            'is_solo_parent' => 'required|boolean',
        ])->stopOnFirstFailure(true);
        if ($v->fails()) {
            return vRes($v);
        }

        $hm = HouseholdMember::create($request->all());

        return res($hm);
    }

    public function update(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'id' => 'required|exists:household_members,id',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'relationship_to_head' => 'required|enum:'.RelationshipEnum::class,
            'sex' => 'required|enum:'.GenderEnum::class,
            'is_lgbtqm' => 'required|boolean',
            'birth_date' => 'required|date|date_format:Y-m-d',
            'place_of_birth' => 'required',
            'is_pwd' => 'required|boolean',
            'is_solo_parent' => 'required|boolean',
        ])->stopOnFirstFailure(true);
        if ($v->fails()) {
            return vRes($v);
        }

        $hm = HouseholdMember::find($request->id);
        $hm->update($request->except('id'));

        return res($hm);
    }

    public function delete(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'id' => 'required|exists:household_members,id',
        ])->stopOnFirstFailure(true);
        if ($v->fails()) {
            return vRes($v);
        }

        $hm = HouseholdMember::find($request->id);
        $hm->delete();

        return res();
    }
}
