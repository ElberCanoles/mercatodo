<?php

namespace App\Domain\Users\Models;

use App\Domain\Carts\Models\Cart;
use App\Domain\Orders\Models\Order;
use App\Domain\Payments\Models\Payment;
use App\Domain\Users\Enums\Permissions;
use App\Domain\Users\Enums\Roles;
use App\Domain\Users\Presenters\UserPresenter;
use App\Domain\Users\QueryBuilders\UserQueryBuilder;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $status
 * @property ?Carbon $email_verified_at
 * @property Carbon $created_at
 * @property string $password
 * @property-read Collection|Builder|Role[]|null $roles
 * @property-read Collection|Builder|Permission[]|null $permissions
 * @property-read Collection|Builder|Order[]|null $orders
 * @property-read Collection|Builder|Cart $cart
 *
 * @method static UserQueryBuilder query()
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'roles:id,name',
        'permissions:id,name',
    ];

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    public function present(): UserPresenter
    {
        $presenter = UserPresenter::getInstance();
        $presenter->setUser($this);
        return $presenter;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(related: Role::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(related: Permission::class);
    }

    public function assignRole(Role|Roles $role): void
    {
        if (!$role instanceof Role) {
            $role = Role::query()->where(column: 'name', operator: '=', value: $role->value)->first(['id']);
        }

        $this->roles()->attach($role);
    }

    public function givePermissionTo(Permission|Permissions $permission): void
    {
        if (!$permission instanceof Permission) {
            $permission = Permission::query()->where(column: 'name', operator: '=', value: $permission->value)->first(['id']);
        }

        $this->permissions()->attach($permission);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(related: Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(related: Order::class);
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(related: Payment::class, through: Order::class);
    }
}
