<?php

declare(strict_types=1);

namespace App\View\Components\Layouts\Partials;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

final class Sidebar extends Component
{
    private array $menu;

    private array $userPermissions;

    public function __construct()
    {
        $this->userPermissions = Auth::user()->getAllPermissions()->pluck('name')->toArray();
        $this->buildMenu();
    }

    public function render()
    {
        return view('layouts.partials.sidebar', [
            'menu' => array_filter($this->menu, fn ($item) => $item['hasPermission']),
        ]);
    }

    private function buildMenu(): void
    {
        $this->menu = [
            // Dashboard
            $this->createMenuItem(
                title: 'Dashboard',
                icon: 'bi bi-grid-fill',
                link: route('dashboard'),
                permissions: ['view_dashboard'],
                activeRoutes: ['dashboard']
            ),

            // Priority Analysis
            $this->createMenuItem(
                title: 'Priority Analysis',
                icon: 'bi bi-graph-up',
                link: route('priority-analysis'),
                permissions: ['view_priority_analysis'],
                activeRoutes: ['priority-analysis']
            ),

            // Manage Product
            $this->createMenuItem(
                title: 'Manage Product',
                icon: 'bi bi-cake2',
                permissions: ['view_product', 'view_barcode_scanner'],
                activeRoutes: ['product.*'],
                subItems: [
                    $this->createSubItem(
                        title: 'Product List',
                        link: route('product.index'),
                        permissions: ['view_product'],
                        activeRoutes: ['product.index', 'product.create', 'product.show', 'product.edit']
                    ),
                    $this->createSubItem(
                        title: 'Barcode Scanner',
                        link: route('product.barcode-scanner'),
                        permissions: ['view_barcode_scanner'],
                        activeRoutes: ['product.barcode-scanner']
                    ),
                ]
            ),

            // Manage Production
            $this->createMenuItem(
                title: 'Manage Production',
                icon: 'bi bi-cake',
                permissions: [
                    'view_production',
                    'show_production',
                    'edit_production',
                    'view_report_production',
                    'view_production_request',
                    'create_production_request',
                ],
                activeRoutes: ['production.*'],
                subItems: [
                    $this->createSubItem(
                        title: 'Production List',
                        link: route('production.index'),
                        permissions: ['view_production'],
                        activeRoutes: ['production.index', 'production.show', 'production.edit']
                    ),
                    $this->createSubItem(
                        title: 'Production Request',
                        link: route('production.request.index'),
                        permissions: ['view_production_request'],
                        activeRoutes: ['production.request.*']
                    ),
                    $this->createSubItem(
                        title: 'Production Report',
                        link: route('production.report'),
                        permissions: ['view_report_production'],
                        activeRoutes: ['production.report']
                    ),
                ]
            ),

            // Manage Sales
            $this->createMenuItem(
                title: 'Manage Sales',
                icon: 'bi bi-basket',
                permissions: ['view_sale', 'show_sale', 'create_sale', 'view_report_sale'],
                activeRoutes: ['sales.*'],
                subItems: [
                    $this->createSubItem(
                        title: 'Sales List',
                        link: route('sales.index'),
                        permissions: ['view_sale'],
                        activeRoutes: ['sales.index', 'sales.create', 'sales.show']
                    ),
                    $this->createSubItem(
                        title: 'Product Request',
                        link: route('sales.request.product.index'),
                        permissions: ['view_product_request'],
                        activeRoutes: ['sales.request.product.*']
                    ),
                    $this->createSubItem(
                        title: 'Sales Report',
                        link: route('sales.report'),
                        permissions: ['view_report_sale'],
                        activeRoutes: ['sales.report']
                    ),
                ]
            ),

            // Manage Inventory
            $this->createMenuItem(
                title: 'Manage Inventory',
                icon: 'bi bi-box-seam',
                permissions: [
                    'view_inventory_in',
                    'view_inventory_out',
                    'view_report_inventory',
                    'view_inventory_production_request',
                    'show_inventory_production_request',
                    'create_inventory_production_request',
                    'edit_inventory_production_request',
                ],
                activeRoutes: ['inventory.*'],
                subItems: [
                    $this->createSubItem(
                        title: 'Inventory In',
                        link: route('inventory.in.index'),
                        permissions: ['view_inventory_in'],
                        activeRoutes: ['inventory.in.index']
                    ),
                    $this->createSubItem(
                        title: 'Inventory Out',
                        link: route('inventory.out.index'),
                        permissions: ['view_inventory_out'],
                        activeRoutes: ['inventory.out.index']
                    ),
                    $this->createSubItem(
                        title: 'Product Request',
                        link: route('inventory.request.product.index'),
                        permissions: ['view_inventory_product_request'],
                        activeRoutes: ['inventory.request.product.*']
                    ),
                    $this->createSubItem(
                        title: 'Production Request',
                        link: route('inventory.request.index'),
                        permissions: ['view_inventory_production_request'],
                        activeRoutes: ['inventory.request.index', 'inventory.request.create', 'inventory.request.show', 'inventory.request.edit', 'inventory.request.edit-status']
                    ),
                    $this->createSubItem(
                        title: 'Inventory Report',
                        link: route('inventory.report'),
                        permissions: ['view_report_inventory'],
                        activeRoutes: ['inventory.report']
                    ),
                ]
            ),

            // Manage Access
            $this->createMenuItem(
                title: 'Manage Access',
                icon: 'bi bi-people',
                link: route('user.index'),
                permissions: ['view_user'],
                activeRoutes: ['user.*']
            ),
        ];
    }

    private function createMenuItem(
        string $title,
        string $icon,
        ?string $link = null,
        array $permissions = [],
        array $activeRoutes = [],
        array $subItems = []
    ): array {
        $hasPermission = empty($permissions) || (bool) array_intersect($permissions, $this->userPermissions);

        return [
            'title' => $title,
            'icon' => $icon,
            'link' => $link,
            'permissions' => $permissions,
            'activeRoutes' => $activeRoutes,
            'subItems' => array_filter($subItems, fn ($sub) => $sub['hasPermission']),
            'hasPermission' => $hasPermission,
            'isActive' => request()->routeIs($activeRoutes),
        ];
    }

    private function createSubItem(
        string $title,
        string $link,
        array $permissions = [],
        array $activeRoutes = []
    ): array {
        $hasPermission = empty($permissions) || (bool) array_intersect($permissions, $this->userPermissions);

        return [
            'title' => $title,
            'link' => $link,
            'permissions' => $permissions,
            'activeRoutes' => $activeRoutes,
            'hasPermission' => $hasPermission,
            'isActive' => request()->routeIs($activeRoutes),
        ];
    }
}
