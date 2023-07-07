<?php

namespace App\Domain\Imports\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $module
 * @property string $path
 * @property array $summary
 * @property array $errors
 *
 * @method static Import create(array $attributes = [])
 */
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
