<?php

namespace App\Models;

use App\Enums\Payment\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function paid(): void
    {
        $this->update([
            'status' => PaymentStatus::PAID
        ]);
    }

    public function rejected(): void
    {
        $this->update([
            'status' => PaymentStatus::REJECTED
        ]);
    }

}
