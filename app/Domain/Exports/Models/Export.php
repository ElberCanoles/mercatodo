<?php

namespace App\Domain\Exports\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $module
 * @property string $path
 * @property Carbon $created_at
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
