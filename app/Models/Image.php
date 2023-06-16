<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Model create(array $attributes = [])
 * @method static Model|null find($id, $columns = ['*'])
 * @method static Model findOrFail($id, $columns = ['*'])
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
