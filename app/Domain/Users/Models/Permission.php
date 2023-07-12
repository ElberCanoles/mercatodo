<?php

namespace App\Domain\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 *
 * @method static Permission create(array $attributes = [])
 */
class Permission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
