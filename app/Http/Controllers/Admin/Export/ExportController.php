<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Export;

use App\Enums\General\SystemParams;
use App\Http\Controllers\Controller;
use App\Models\Export;
use App\Traits\Responses\MakeJsonResponse;
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
