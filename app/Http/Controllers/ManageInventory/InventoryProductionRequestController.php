<?php

declare(strict_types=1);

namespace App\Http\Controllers\ManageInventory;

use App\Enums\StatusProduction;
use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class InventoryProductionRequestController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),

            new Middleware('permission:view_inventory_production_request', only: ['index']),
            new Middleware('permission:show_inventory_production_request', only: ['show']),
            new Middleware('permission:create_inventory_production_request', only: ['create']),
            new Middleware('permission:edit_inventory_production_request', only: ['edit', 'status']),
        ];
    }

    public function index()
    {
        $title = 'Production Request List';

        $text_subtitle = 'Production Request is used to display, manage, and monitor production data in the system';

        return view('manage-inventory.inventory-production-request-index', compact('title', 'text_subtitle'));
    }

    public function create()
    {
        $title = 'Create Production Request';

        $text_subtitle = 'This page displays form create production data.';

        return view('manage-inventory.inventory-production-request-create', compact('title', 'text_subtitle'));
    }

    public function show(Production $production)
    {
        $title = 'Show Production Request';

        $text_subtitle = 'This page displays detail of production data.';

        return view('manage-inventory.inventory-production-request-show', compact('title', 'text_subtitle', 'production'));
    }

    public function edit(Production $production)
    {
        $title = 'Edit Production Request';

        $text_subtitle = 'This page displays form edit production data.';

        if (StatusProduction::WAITING_FOR_RESPONSE !== $production->status) {
            abort(403, 'Only productions with status "Waiting for Response" can be edited.');
        }

        return view('manage-inventory.inventory-production-request-edit', compact('title', 'text_subtitle', 'production'));
    }

    public function status(Production $production)
    {
        $title = 'Edit Status Production Request';

        $text_subtitle = 'This page displays form edit status production data.';

        if (StatusProduction::PENDING_APPROVAL !== $production->status) {
            abort(403, 'Only productions with status "Pending Approval" can be rejected.');
        }

        return view('manage-inventory.inventory-production-request-edit-status', compact('title', 'text_subtitle', 'production'));
    }
}
