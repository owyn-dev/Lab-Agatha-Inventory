<?php

declare(strict_types=1);

namespace App\Http\Controllers\ManageProduct;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

final class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),

            new Middleware('permission:view_product', only: ['index']),
            new Middleware('permission:show_product', only: ['show']),
            new Middleware('permission:create_product', only: ['create']),
            new Middleware('permission:edit_product', only: ['edit']),
        ];
    }

    public function index()
    {
        $title = 'Product List';

        $text_subtitle = 'Product List is used to display, manage, and monitor product data in the system';

        return view('manage-product.product-index', compact('title', 'text_subtitle'));
    }

    public function create()
    {
        $title = 'Create Product';

        $text_subtitle = 'This page displays form create product data.';

        return view('manage-product.product-create', compact('title', 'text_subtitle'));
    }

    public function show(Product $product)
    {
        $title = 'Show Product';

        $text_subtitle = 'This page displays detail of product data.';

        return view('manage-product.product-show', compact('title', 'text_subtitle', 'product'));
    }

    public function edit(Product $product)
    {
        $title = 'Edit Product';

        $text_subtitle = 'This page displays form edit product data.';

        return view('manage-product.product-edit', compact('title', 'text_subtitle', 'product'));
    }

    public function indexBarcodeScanner()
    {
        $title = 'Barcode Scanner';

        $text_subtitle = 'Barcode Scanner is used to display detail product data in the system';

        return view('manage-product.product-barcode-scanner', compact('title', 'text_subtitle'));
    }
}
