<?php

namespace App\Domain\Imports\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'path',
        'summary',
        'errors'
    ];

    protected $casts = [
        'summary' => 'array',
        'errors' => 'array'
    ];

}
