<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use App\Enums\AssignmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;
    use Searchable;

    protected $guarded = ['id'];

    protected $with = ['materials'];

    protected $casts = [
        'content' => 'array',
        'deadline' => 'datetime:Y-m-d H:i:s',
        'published_at' => 'datetime',
    ];

    public function isPublished()
    {
        return $this->status === AssignmentStatus::PUBLISH->value;
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function testScenarios(): HasMany
    {
        return $this->hasMany(TestScenario::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
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
}
