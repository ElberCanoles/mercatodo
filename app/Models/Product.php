<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int stock
 * @property string name
 * @property object $pivot
 * @property float $price
 *
 * @method static Model create(array $attributes = [])
 * @method static Model|null find($id, $columns = ['*'])
 * @method static Model findOrFail($id, $columns = ['*'])
 */
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $with = [
        'images',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'status',
    ];

    public function carts(): MorphToMany
    {
        return $this->morphedByMany(related: Cart::class, name: 'productable')->withPivot(columns: 'quantity');
    }

    public function orders(): MorphToMany
    {
        return $this->morphedByMany(related: Order::class, name: 'productable')->withPivot(columns: 'quantity');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(related: Image::class, name: 'imageable');
    }

    public function getTotalAttribute(): float|int
    {
        return $this->pivot->quantity * $this->price;
    }
}
