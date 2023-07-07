<?php

namespace App\Domain\Orders\Models;

use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\QueryBuilders\OrderQueryBuilder;
use App\Domain\Payments\Models\Payment;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property string $status
 * @property-read Collection|Builder|Product[]|null $products
 * @property-read Collection|Builder|Payment[]|null $payments
 *
 *
 * @method static OrderQueryBuilder query()
 */
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
        'status'
    ];

    public function newEloquentBuilder($query): OrderQueryBuilder
    {
        return new OrderQueryBuilder($query);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(related: Payment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(related: Product::class, name: 'productable')->withPivot(columns: 'quantity');
    }

    public function pending(): void
    {
        $this->update([
            'status' => OrderStatus::PENDING
        ]);
    }

    public function confirmed(): void
    {
        $this->update([
            'status' => OrderStatus::CONFIRMED
        ]);
    }

    public function cancelled(): void
    {
        $this->update([
            'status' => OrderStatus::CANCELLED
        ]);
    }
}
