<?php

declare(strict_types=1);

namespace App\Domain\Products\Jobs;

use App\Contracts\Exports\ProductExporterInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductExportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(ProductExporterInterface $exporter): void
    {
        $exporter->export();
    }
}
