<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Admin\Export;

use App\Domain\Exports\Models\Export;
use App\Domain\Exports\Resources\ExportResource;
use App\Domain\Shared\Enums\SystemParams;
use App\Domain\Shared\Traits\Responses\MakeJsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\View\View;

class ExportController extends Controller
{
    use MakeJsonResponse;

    public function index(Request $request): View|AnonymousResourceCollection
    {
        if (!$request->wantsJson()) {
            return view(view: 'admin.exports.index');
        }

        $exports = Export::query()
            ->latest()
            ->paginate(perPage: SystemParams::LENGTH_PER_PAGE);

        return ExportResource::collection($exports);
    }
}
