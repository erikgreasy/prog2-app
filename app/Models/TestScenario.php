<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestScenario extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['cases'];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function cases(): HasMany
    {
        return $this->hasMany(TestCase::class, 'test_scenario_id');
    }
}
