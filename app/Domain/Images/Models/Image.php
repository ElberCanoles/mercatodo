<?php

namespace App\Domain\Images\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $path
 *
 * @method static Image create(array $attributes = [])
 */
class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
