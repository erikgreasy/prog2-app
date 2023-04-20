<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Scout\Searchable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use Searchable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function hasVcsSetup(): bool
    {
        if (!$this->vcs_username) {
            return false;
        }

        if (!$this->github_access_token) {
            return false;
        }

        if (!$this->github_repo) {
            return false;
        }

        return true;
    }

    public function toSearchableArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
        ];
    }

    public function finalAssignmentSubmissions()
    {
        return $this
            ->hasMany(Submission::class)
            ->whereRaw('(assignment_id, try) in (
                select assignment_id, MAX(try)
                from submissions
                group by assignment_id
            )');
    }

    public function totalPoints(): float
    {
        return $this
            ->submissions()
            ->final()
            ->sum('points');
    }
}
