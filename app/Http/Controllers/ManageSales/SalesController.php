<?php

declare(strict_types=1);

namespace App\Http\Controllers\ManageSales;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class SalesController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),

            new Middleware('permission:view_sale', only: ['index']),
            new Middleware('permission:show_sale', only: ['show']),
            new Middleware('permission:create_sale', only: ['create']),
            new Middleware('permission:view_report_sale', only: ['report']),
        ];
    }

    public function index()
    {
        $title = 'Sales List';

        $text_subtitle = 'Sales List is used to display, manage, and monitor sales data in the system';

        return view('manage-sales.sales-index', compact('title', 'text_subtitle'));
    }

    public function create()
    {
        $title = 'Create Sales';

        $text_subtitle = 'This page displays form create sales data.';

        return view('manage-sales.sales-create', compact('title', 'text_subtitle'));
    }

    public function show(Sale $sales)
    {
        $title = 'Show Sales';

        $text_subtitle = 'This page displays detail of sales data.';

        return view('manage-sales.sales-show', compact('title', 'text_subtitle', 'sales'));
    }

    public function report()
    {
        $title = 'Sales Report';

        $text_subtitle = 'Generate sales Reports';

        return view('manage-sales.sales-report', compact('title', 'text_subtitle'));
    }
}
