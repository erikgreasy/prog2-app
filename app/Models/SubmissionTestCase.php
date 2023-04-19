<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionTestCase extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_success' => 'bool',
        'build_status' => 'bool',
        'gcc_errors' => 'array',
        'gcc_warnings' => 'array',
    ];

    public function resultScenario(): BelongsTo
    {
        return $this->belongsTo(SubmissionTestScenario::class);
    }
}
