<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'deadline',
        'content',
    ];

    protected $casts = [
        'content' => 'array'
    ];
}
