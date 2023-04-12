<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use App\Enums\AssignmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Assignment extends Model
{
    use HasFactory;
    use Searchable;

    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array',
        'is_current' => 'bool',
        'submission_instructions' => 'array',
        'materials' => 'array',
        'tries' => 'array',
        'deadline' => 'datetime:Y-m-d H:i',
        'published_at' => 'datetime:Y-m-d H:i',
        'points' => 'float',
    ];

    public function isPublished()
    {
        return $this->published_at && $this->published_at <= now();
    }
    
    public function maxTries(): int
    {
        if (!$this->tries) {
            return 0;
        }

        return count($this->tries);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function processingUserSubmission(): HasOne
    {
        return $this->hasOne(Submission::class)->ofMany()->where('user_id', auth()->id())->whereNull('points');
    }

    public function finalSubmissions(): HasMany
    {
        return $this->hasMany(Submission::class)
            ->groupBy('user_id')
            ->where(function ($query) {
                $query->orderByDesc('try')->take(1);
            });
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

    protected static function booted(): void
    {
        static::saving(function (Assignment $assignment) {
            if ($assignment->is_current && Assignment::where('is_current', true)->where('id', '!=', $assignment->id)->exists()) {
                Assignment::query()->update(['is_current' => false]);
            }
        });
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('published_at', '<=', now());
    }
}
