<?php

namespace App\Models;

use App\Enums\AssignmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array',
        'deadline' => 'datetime:Y-m-d H:i:s',
    ];

    public function isPublished()
    {
        return $this->status === AssignmentStatus::PUBLISH->value;
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
