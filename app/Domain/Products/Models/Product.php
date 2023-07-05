<?php

namespace App\Domain\Products\Models;

use App\Domain\Carts\Models\Cart;
use App\Domain\Images\Models\Image;
use App\Domain\Orders\Models\Order;
use Carbon\Carbon;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float $price
 * @property int $stock
 * @property string $status
 * @property object $pivot
 * @property-read Collection|Builder|Image[]|null $images
 * @property Carbon $created_at
 * @property Carbon $updated_at
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

    protected static function newFactory(): Factory
    {
        return ProductFactory::new();
    }

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
