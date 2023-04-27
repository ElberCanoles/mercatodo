<?php

namespace App\Providers;

use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Contracts\Repository\User\UserReadRepositoryInterface;
use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Repositories\Product\ProductReadEloquentRepository;
use App\Repositories\Product\ProductWriteEloquentRepository;
use App\Repositories\User\UserReadEloquentRepository;
use App\Repositories\User\UserWriteEloquentRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: UserWriteRepositoryInterface::class,
            concrete: UserWriteEloquentRepository::class
        );

        $this->app->bind(
            abstract: UserReadRepositoryInterface::class,
            concrete: UserReadEloquentRepository::class
        );

        $this->app->bind(
            abstract: ProductWriteRepositoryInterface::class,
            concrete: ProductWriteEloquentRepository::class
        );

        $this->app->bind(
            abstract: ProductReadRepositoryInterface::class,
            concrete: ProductReadEloquentRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
