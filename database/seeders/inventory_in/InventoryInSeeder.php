<?php

declare(strict_types=1);

namespace Database\Seeders\inventory_in;

use App\Models\InventoryIn;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

final class InventoryInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $expiredInventories = InventoryIn::with('product')
            ->where('wasted', 'No')
            ->where('current_stock', '>', 0)
            ->where('expiration_date', '<', $now)
            ->get();

        foreach ($expiredInventories as $inventory) {
            $product = $inventory->product;
            if ($product) {
                $product->stock -= $inventory->current_stock;
                if ($product->stock < 0) {
                    $product->stock = 0;
                }
                $product->save();
            }

            $inventory->wasted = 'Yes';
            $inventory->save();
        }

        $this->command->info('Expired inventory updated and product stock adjusted.');
    }
}
