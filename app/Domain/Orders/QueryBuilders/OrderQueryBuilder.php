<?php

declare(strict_types=1);

namespace App\Domain\Orders\QueryBuilders;

use App\Domain\Orders\Models\Order;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method static Order create(array $attributes = [])
 * @method static Order|null find($id, $columns = ['*'])
 * @method static Builder|Builder[]|Collection|Order findOrFail($id, $columns = ['*'])
 */
class OrderQueryBuilder extends Builder
{
    public function whereUser(User $user): self
    {
        return $this->where(column: 'user_id', operator: '=', value: $user->getKey());
    }
}
