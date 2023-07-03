<?php

namespace App\Providers;

use App\Contracts\Exports\ProductExporterInterface;
use App\Contracts\Imports\ProductImporterInterface;
use App\Contracts\Payment\PaymentFactoryInterface;
use App\Contracts\Repository\Product\ProductReadRepositoryInterface;
use App\Contracts\Repository\Product\ProductWriteRepositoryInterface;
use App\Contracts\Repository\User\UserReadRepositoryInterface;
use App\Contracts\Repository\User\UserWriteRepositoryInterface;
use App\Domain\Exports\Services\ProductCsvExporter;
use App\Domain\Imports\Services\ProductCsvImporter;
use App\Domain\Payments\Factories\PaymentFactory;
use App\Domain\Products\Repositories\ProductReadEloquentRepository;
use App\Domain\Products\Repositories\ProductWriteEloquentRepository;
use App\Domain\Users\Repositories\UserReadEloquentRepository;
use App\Domain\Users\Repositories\UserWriteEloquentRepository;
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

        $this->app->bind(
            abstract: PaymentFactoryInterface::class,
            concrete: PaymentFactory::class
        );

        $this->app->bind(
            abstract: ProductExporterInterface::class,
            concrete: ProductCsvExporter::class
        );

        $this->app->bind(
            abstract: ProductImporterInterface::class,
            concrete: ProductCsvImporter::class
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
