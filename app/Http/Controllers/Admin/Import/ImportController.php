<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Import;

use App\Enums\General\SystemParams;
use App\Http\Controllers\Controller;
use App\Models\Import;
use App\Traits\Responses\MakeJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImportController extends Controller
{
    use MakeJsonResponse;

    public function index(Request $request): View|JsonResponse
    {
        if (!$request->wantsJson()) {
            return view(view: 'admin.imports.index');
        }

        $imports = Import::query()
            ->latest()
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE)->through(fn($import) => [
                'id' => $import->id,
                'module' => trans($import->module),
                'date' => $import->created_at->format('d-m-Y'),
                'hour' => $import->created_at->isoFormat('H:mm:ss A'),
                'path' => $import->path,
                'show_url' => route(name: 'admin.imports.show', parameters: ['import' => $import->id])
            ]);

        return $this->successResponse(data: $imports);
    }

    public function show(Import $import): View
    {
        return view(view: 'admin.imports.show', data: [
            'import' => $import
        ]);
    }
}
