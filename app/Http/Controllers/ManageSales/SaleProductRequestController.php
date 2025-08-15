<?php

declare(strict_types=1);

namespace App\Http\Controllers\ManageSales;

use App\Enums\StatusProductRequest;
use App\Http\Controllers\Controller;
use App\Models\ProductRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class SaleProductRequestController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),

            new Middleware('permission:view_product_request', only: ['index']),
            new Middleware('permission:view_manage_stock', only: ['manage_stock']),
            new Middleware('permission:show_product_request', only: ['show']),
            new Middleware('permission:create_product_request', only: ['create']),
            new Middleware('permission:edit_product_request', only: ['edit']),
        ];
    }

    public function index()
    {
        $title = 'Product Request List';

        $text_subtitle = 'Product Request List is used to display, manage, and monitor product request data in the system';

        return view('manage-sales.sales-product-request-index', compact('title', 'text_subtitle'));
    }

    public function manage_stock()
    {
        $title = 'Manage Stock';

        $text_subtitle = 'Manage Stock is used to display, manage, and monitor stock data in the system';

        return view('manage-sales.sales-product-request-manage-stock-index', compact('title', 'text_subtitle'));
    }

    public function create()
    {
        $title = 'Create Product Request';

        $text_subtitle = 'This page displays form create product request data.';

        return view('manage-sales.sales-product-request-create', compact('title', 'text_subtitle'));
    }

    public function show(ProductRequest $product_request)
    {
        $title = 'Show Product Request';

        $text_subtitle = 'This page displays detail of product request data.';

        return view('manage-sales.sales-product-request-show', compact('title', 'text_subtitle', 'product_request'));
    }

    public function edit(ProductRequest $product_request)
    {
        $title = 'Edit Product Request';

        $text_subtitle = 'This page displays form edit product request data.';

        if (StatusProductRequest::WAITING_FOR_RESPONSE !== $product_request->status && StatusProductRequest::IN_PRODUCTION !== $product_request->status) {
            abort(403, 'Only product requests with status "Waiting for Response" or "In Production" can be edited.');
        }

        return view('manage-sales.sales-product-request-edit', compact('title', 'text_subtitle', 'product_request'));
    }
}
