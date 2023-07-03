<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\Export;

use App\Domain\Exports\Models\Export;
use App\Domain\Shared\Enums\SystemParams;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExportController extends Controller
{
    use MakeJsonResponse;

    public function index(Request $request): View|JsonResponse
    {
        if (!$request->wantsJson()) {
            return view(view: 'admin.exports.index');
        }

        $exports = Export::query()
            ->latest()
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE)->through(fn($export) => [
                'id' => $export->id,
                'module' => trans($export->module),
                'date' => $export->created_at->format('d-m-Y'),
                'hour' => $export->created_at->isoFormat('H:mm:ss A'),
                'path' => $export->path
            ]);

        return $this->successResponse(data: $exports);
    }
}
