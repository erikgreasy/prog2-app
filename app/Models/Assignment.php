<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'deadline',
        'content',
    ];

    protected $casts = [
        'content' => 'array'
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
