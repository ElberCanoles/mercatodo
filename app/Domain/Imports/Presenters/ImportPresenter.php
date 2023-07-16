<?php

declare(strict_types=1);

namespace App\Domain\Imports\Presenters;

use App\Domain\Imports\Models\Import;

class ImportPresenter
{
    private static ?ImportPresenter $instance = null;

    private Import $import;

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

    public function setImport(Import $import): void
    {
        $this->import = $import;
    }

    public function moduleTranslated(): string
    {
        return trans(key: $this->import->module);
    }

    public function date(): string
    {
        return $this->import->created_at->format(format: 'd-m-Y');
    }

    public function hour(): string
    {
        return $this->import->created_at->isoFormat(format: 'H:mm:ss A');
    }

    public function adminShowUrl(): string
    {
        return route(name: 'admin.imports.show', parameters: ['import' => $this->import->id]);
    }
}
