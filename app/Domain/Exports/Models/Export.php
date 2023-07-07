<?php

namespace App\Domain\Exports\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $module
 * @property string $path
 *
 * @method static Export create(array $attributes = [])
 */
class Export extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'path'
    ];

}
