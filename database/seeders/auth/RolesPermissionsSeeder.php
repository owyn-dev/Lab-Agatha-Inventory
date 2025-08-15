<?php

declare(strict_types=1);

namespace Database\Seeders\auth;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view_dashboard',
            'view_priority_analysis',
            'view_product',
            'show_product',
            'view_barcode_scanner',
            'create_product',
            'edit_product',
            'delete_product',
            'view_production',
            'show_production',
            'create_production',
            'edit_production',
            'delete_production',
            'view_report_production',
            'view_production_request',
            'show_production_request',
            'create_production_request',
            'edit_production_request',
            'delete_production_request',
            'view_sale',
            'show_sale',
            'create_sale',
            'view_report_sale',
            'view_inventory_in',
            'view_inventory_out',
            'view_report_inventory',
            'view_inventory_production_request',
            'show_inventory_production_request',
            'create_inventory_production_request',
            'edit_inventory_production_request',
            'delete_inventory_production_request',
            'view_user',
            'show_user',
            'profile_user',
            'create_user',
            'edit_user',
            'delete_user',
            'show_widget_product',
            'show_widget_production',
            'show_widget_transaction',
            'show_chart_sale',
            'show_chart_production',
            'show_table_latest_sale',
            'show_table_latest_production',
            'show_table_latest_inventory_in',
            'show_table_latest_inventory_out',
            'view_product_request',
            'show_product_request',
            'create_product_request',
            'edit_product_request',
            'delete_product_request',
            'view_manage_stock',
            'view_inventory_product_request',
            'show_inventory_product_request',
            'create_inventory_product_request',
            'edit_inventory_product_request',
            'delete_inventory_product_request',
            'trash_inventory_in',
            'trash_sale_manage_stock',
            'show_chart_classification',
            'show_table_classification',
        ];

        $rolesWithPermissions = [
            'administrator' => [0, 58, 60, 37, 38, 39, 40, 41, 42, 43, 44, 45, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 57, 58, 59],
            'production' => [0, 58, 60, 37, 38, 41, 43, 1, 2, 3, 4, 8, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 33],
            'sales' => [0, 58, 60, 37, 39, 40, 42, 1, 2, 3, 4, 5, 6, 7, 19, 20, 21, 22, 33, 46, 47, 48, 49, 50, 51, 58],
            'inventory' => [0, 58, 60, 37, 38, 44, 43, 45, 1, 2, 3, 4, 23, 24, 25, 26, 27, 28, 29, 30, 33, 52, 53, 54, 55, 57],
            'testing' => [0, 1, 2, 4, 8, 14, 19, 23, 24, 26, 31, 33],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach ($rolesWithPermissions as $roleName => $indices) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $assignedPermissions = array_intersect_key($permissions, array_flip($indices));
            $role->syncPermissions($assignedPermissions);
        }
    }
}
