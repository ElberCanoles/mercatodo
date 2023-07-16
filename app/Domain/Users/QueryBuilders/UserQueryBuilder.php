<?php

declare(strict_types=1);

namespace App\Domain\Users\QueryBuilders;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static User create(array $attributes = [])
 * @method static User|null find($id, $columns = ['*'])
 */
class UserQueryBuilder extends Builder
{
    public function whereColumContains(string $column, ?string $value): self
    {
        if ($value == null) return $this;

        return $this->where(column: $column, operator: 'like', value: '%' . $value . '%');
    }

}
