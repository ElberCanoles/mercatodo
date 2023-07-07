<?php

namespace App\Domain\Payments\Models;

use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Enums\PaymentStatus;
use App\Domain\Payments\QueryBuilders\PaymentQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $order_id
 * @property string $provider
 * @property array $data_provider
 * @property string $status
 * @property Carbon $payed_at
 * @property Carbon $created_at
 *
 * @method static PaymentQueryBuilder query()
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'provider',
        'data_provider',
        'status',
        'payed_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'payed_at',
    ];

    protected $casts = [
        'data_provider' => 'array'
    ];

    public function newEloquentBuilder($query): PaymentQueryBuilder
    {
        return new PaymentQueryBuilder($query);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(related: Order::class);
    }

    public function pending(): void
    {
        $this->update([
            'status' => PaymentStatus::PENDING->value
        ]);
    }

    public function paid(): void
    {
        $this->update([
            'status' => PaymentStatus::PAID->value,
            'payed_at' => Carbon::now()
        ]);
    }

    public function rejected(): void
    {
        $this->update([
            'status' => PaymentStatus::REJECTED->value
        ]);
    }
}
