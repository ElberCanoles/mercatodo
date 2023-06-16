<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property MorphToMany $products
 *
 * @method static Model create(array $attributes = [])
 * @method static Model|null find($id, $columns = ['*'])
 * @method static Model findOrFail($id, $columns = ['*'])
 */
class Cart extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(related: Product::class, name: 'productable')->withPivot(columns: 'quantity');
    }

    public function getTotalAttribute(): float|int
    {
        return $this->products->pluck('total')->sum();
    }
}
