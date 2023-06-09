<?php
declare(strict_types=1);

namespace App\QueryBuilders;

use App\Enums\Order\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class OrderQueryBuilder extends Builder
{
    public function whereUser(User $user): self
    {
        return $this->where(column: 'user_id', operator: '=', value: $user->getKey());
    }

    public function whereLatestPendingForUser(User $user): self
    {
        return $this->whereUser(user: $user)
            ->where(column: 'status', operator: '=', value: OrderStatus::PENDING)
            ->latest();
    }
}
