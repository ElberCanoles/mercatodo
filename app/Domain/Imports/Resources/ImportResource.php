<?php

declare(strict_types=1);

namespace App\Domain\Imports\Resources;

use App\Domain\Imports\Models\Import;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Import
 */
class ImportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module' => trans(key: $this->module),
            'date' => $this->created_at->format(format: 'd-m-Y'),
            'hour' => $this->created_at->isoFormat(format: 'H:mm:ss A'),
            'path' => $this->path,
            'show_url' => route(name: 'admin.imports.show', parameters: ['import' => $this->id])
        ];
    }
}
