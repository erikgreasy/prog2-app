<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubmissionTestScenario extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = [
        'is_success'
    ];

    protected $with = [
        'scenario',
        'resultCases',
    ];

    public function isSuccess(): Attribute
    {
        return new Attribute(
            get: fn () => $this->failedResultCases->isEmpty()
        );
    }

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

    public function successResultCases(): HasMany
    {
        return $this->hasMany(SubmissionTestCase::class)->where('is_success', true);
    }

    public function failedResultCases(): HasMany
    {
        return $this->hasMany(SubmissionTestCase::class)->where('is_success', false);
    }
}
