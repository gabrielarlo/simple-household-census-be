<?php

namespace App\Http\Controllers\API;

use App\Enums\GenderEnum;
use App\Enums\RelationshipEnum;
use App\Http\Controllers\Controller;
use App\Models\HouseholdMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HouseholdMemberController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'household_id' => 'required|exists:households,id',
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $hm = HouseholdMember::where('household_id', $request->household_id)->get();

        return res($hm);
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
        ]);
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
        ]);
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
        ]);
        if ($v->fails()) {
            return vRes($v);
        }

        $hm = HouseholdMember::find($request->id);
        $hm->delete();

        return res();
    }
}
