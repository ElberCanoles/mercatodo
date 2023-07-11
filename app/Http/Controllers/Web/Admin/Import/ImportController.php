<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\Import;

use App\Domain\Imports\Models\Import;
use App\Domain\Imports\Resources\ImportResource;
use App\Domain\Shared\Enums\SystemParams;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Contracts\View\View;

class ImportController extends Controller
{
    use MakeJsonResponse;

    public function index(Request $request): View|AnonymousResourceCollection
    {
        if (!$request->wantsJson()) {
            return view(view: 'admin.imports.index');
        }

        $imports = Import::query()
            ->latest()
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE);

        return ImportResource::collection($imports);
    }

    public function show(Import $import): View
    {
        return view(view: 'admin.imports.show', data: [
            'import' => $import
        ]);
    }
}
