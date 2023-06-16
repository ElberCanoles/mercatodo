<?php

namespace App\Models;

use App\Enums\Order\OrderStatus;
use App\QueryBuilders\OrderQueryBuilder;
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
 *
 * @method static Model create(array $attributes = [])
 * @method static Model|null find($id, $columns = ['*'])
 * @method static Model findOrFail($id, $columns = ['*'])
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
