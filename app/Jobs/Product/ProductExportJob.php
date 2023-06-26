<?php

declare(strict_types=1);

namespace App\Jobs\Product;

use App\Contracts\Exports\ProductExporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ProductExporter $exporter): void
    {
        $exporter->export();
    }
}
