<?php

namespace App\Models;

use App\Enums\SubmissionSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'report' => 'object',
        'source' => SubmissionSource::class,
    ];

    protected $with = [
        'resultScenarios'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function resultScenarios(): HasMany
    {
        return $this->hasMany(SubmissionTestScenario::class);
    }
}
