<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;

class OrderQueryBuilder extends Builder
{
    public function whereUser(User $user): self
    {
        return $this->where(column: 'user_id', operator: '=', value: $user->getKey());
    }
}
