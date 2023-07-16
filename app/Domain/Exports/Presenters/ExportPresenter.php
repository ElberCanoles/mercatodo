<?php

declare(strict_types=1);

namespace App\Domain\Exports\Presenters;

use App\Domain\Exports\Models\Export;

class ExportPresenter
{
    private static ?ExportPresenter $instance = null;

    private Export $export;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setExport(Export $export): void
    {
        $this->export = $export;
    }

    public function moduleTranslated(): string
    {
        return trans(key: $this->export->module);
    }

    public function date(): string
    {
        return $this->export->created_at->format(format: 'd-m-Y');
    }

    public function hour(): string
    {
        return $this->export->created_at->isoFormat(format: 'H:mm:ss A');
    }
}
