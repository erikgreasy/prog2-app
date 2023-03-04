<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubmissionTestScenario extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = [
        'resultCases'
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function scenario(): BelongsTo
    {
        return $this->belongsTo(TestScenario::class, 'test_scenario_id');
    }

    public function resultCases(): HasMany
    {
        return $this->hasMany(SubmissionTestCase::class);
    }
}
