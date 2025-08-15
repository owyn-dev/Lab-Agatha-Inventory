<?php

declare(strict_types=1);

namespace App\Http\Controllers\ManageProduction;

use App\Enums\StatusProduction;
use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class ProductionRequestController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),

            new Middleware('permission:view_production_request', only: ['index']),
            new Middleware('permission:edit_production_request', only: ['edit']),
        ];
    }

    public function index()
    {
        $title = 'Production Request List';

        $text_subtitle = 'Production List is used to display, manage, and monitor production data in the system';

        return view('manage-production.production-request-index', compact('title', 'text_subtitle'));
    }

    public function edit(Production $production)
    {
        $title = 'Edit Production';

        $text_subtitle = 'This page displays form edit production data.';

        if (StatusProduction::WAITING_FOR_RESPONSE !== $production->status) {
            abort(403, 'Only productions with status "Waiting for Response" can be edited.');
        }

        return view('manage-production.production-request-edit', compact('title', 'text_subtitle', 'production'));
    }
}
