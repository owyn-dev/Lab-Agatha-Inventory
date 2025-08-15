<?php

declare(strict_types=1);

namespace App\Http\Controllers\ManageInventory;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class InventoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),

            new Middleware('permission:view_inventory_in', only: ['indexIn']),
            new Middleware('permission:view_inventory_out', only: ['indexOut']),
            new Middleware('permission:view_report_inventory', only: ['report']),
        ];
    }

    public function indexIn()
    {
        $title = 'Inventory In List';

        $text_subtitle = 'Inventory In List is used to display and monitor inventory in data in the system';

        return view('manage-inventory.inventory-in-index', compact('title', 'text_subtitle'));
    }

    public function indexOut()
    {
        $title = 'Inventory Out List';

        $text_subtitle = 'Inventory Out List is used to display and monitor inventory out data in the system';

        return view('manage-inventory.inventory-out-index', compact('title', 'text_subtitle'));
    }

    public function report()
    {
        $title = 'Inventory Report';

        $text_subtitle = 'Generate Inventory Reports';

        return view('manage-inventory.inventory-report', compact('title', 'text_subtitle'));
    }
}
