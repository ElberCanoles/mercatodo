<?php

namespace App\Domain\Carts\Models;

use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
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