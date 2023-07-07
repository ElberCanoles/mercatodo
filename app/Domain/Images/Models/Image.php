<?php

namespace App\Domain\Images\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $path
 *
 * @method static Image create(array $attributes = [])
 */
class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
