<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Enums\RelationshipEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseholdMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'household_id',
        'first_name',
        'middle_name',
        'last_name',
        'relationship_to_head',
        'sex',
        'is_lgbtqm',
        'birth_date',
        'place_of_birth',
        'is_pwd',
        'is_solo_parent',
    ];

    protected $casts = [
        'is_lgbtqm' => 'boolean',
        'is_pwd' => 'boolean',
        'is_solo_parent' => 'boolean',
        'birth_date' => 'date',
        'sex' => GenderEnum::class,
        'relationship_to_head' => RelationshipEnum::class,
    ];

    protected $appends = [
        'is_senior',
        'household',
        'age',
    ];

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class, 'household_id');
    }

    public function getHouseholdAttribute()
    {
        return Household::find($this->attributes['household_id']);
    }

    public function age(): int
    {
        $b_date = Carbon::parse($this->attributes['birth_date']);
        $now = now();

        return $now->diffInYears($b_date);
    }

    public function getAgeAttribute(): int
    {
        return $this->age();
    }

    public function getIsSeniorAttribute(): bool
    {
        return $this->age() >= 60;
    }
}
