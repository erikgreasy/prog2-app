<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use App\Enums\AssignmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;
    use Searchable;

    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array',
        'submission_instructions' => 'array',
        'materials' => 'array',
        'deadline' => 'datetime:Y-m-d H:i',
        'published_at' => 'datetime:Y-m-d H:i',
    ];

    public function isPublished()
    {
        return $this->published_at && $this->published_at <= now();
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function testScenarios(): HasMany
    {
        return $this->hasMany(TestScenario::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->title,
            'excerpt' => $this->title,
            'slug' => $this->title,
        ];
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('published_at', '<=', now());
    }
}
