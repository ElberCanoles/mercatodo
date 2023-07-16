<?php

declare(strict_types=1);

namespace App\Domain\Exports\Resources;

use App\Domain\Exports\Models\Export;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Export
 */
class ExportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'module' => $this->present()->moduleTranslated(),
            'date' => $this->present()->date(),
            'hour' => $this->present()->hour(),
            'path' => $this->path
        ];
    }
}
