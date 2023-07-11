<?php

namespace App\Providers;

use App\Contracts\Exports\ProductExporterInterface;
use App\Contracts\Imports\ProductImporterInterface;
use App\Contracts\Payment\PaymentFactoryInterface;
use App\Domain\Exports\Services\ProductCsvExporter;
use App\Domain\Imports\Services\ProductCsvImporter;
use App\Domain\Payments\Factories\PaymentFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

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
