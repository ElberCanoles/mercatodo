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
            'module' => $this->present()->moduleTranslated(),
            'date' => $this->present()->date(),
            'hour' => $this->present()->hour(),
            'path' => $this->path,
            'show_url' => $this->present()->adminShowUrl()
        ];
    }
}
