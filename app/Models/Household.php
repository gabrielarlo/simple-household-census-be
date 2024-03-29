<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Household extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'conducted_by_id',
        'province',
        'city',
        'barangay',
        'respondent_name',
        'head',
        'member_count',
        'address',
    ];

    protected $appends = [
        'conducted_by',
        'hashid',
    ];

    public function conductedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conducted_by_id');
    }

    public function getConductedByAttribute()
    {
        return User::find($this->attributes['conducted_by_id']);
    }

    public function getHashidAttribute(): string
    {
        return Hashids::encode($this->attributes['id']);
    }
}
