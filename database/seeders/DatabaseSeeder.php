<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\auth\RolesPermissionsSeeder;
use Database\Seeders\auth\UsersRolesSeeder;
use Database\Seeders\inventory_in\InventoryInSeeder;
use Database\Seeders\product\ProductsSeeder;
use Database\Seeders\production\ProductionSeeder;
use Database\Seeders\sale\SaleSeeder;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesPermissionsSeeder::class);
        $this->call(UsersRolesSeeder::class);

        $this->call(ProductsSeeder::class);

        $this->call(ProductionSeeder::class);

        $this->call(SaleSeeder::class);

        $this->call(InventoryInSeeder::class);
    }
}
