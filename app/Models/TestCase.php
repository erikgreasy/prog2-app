<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestCase extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scenario(): BelongsTo
    {
        return $this->belongsTo(TestScenario::class, 'test_scenario_id');
    }
}
