<?php

declare(strict_types=1);

namespace Database\Seeders\product;

use App\Models\Product;
use Illuminate\Database\Seeder;

final class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['code' => 'NTR-TS', 'name' => 'Nastar', 'image' => 'nastar.jpg', 'variant' => 'tabung_s', 'price' => '80000', 'expired_day' => '30', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'NTR-TM', 'name' => 'Nastar', 'image' => 'nastar.jpg', 'variant' => 'tabung_m', 'price' => '110000', 'expired_day' => '30', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'KST-TS', 'name' => 'Kastengel', 'image' => 'kastangel.jpg', 'variant' => 'tabung_s', 'price' => '132000', 'expired_day' => '60', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'KST-TM', 'name' => 'Kastengel', 'image' => 'kastangel.jpg', 'variant' => 'tabung_m', 'price' => '170500', 'expired_day' => '60', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CLK-TM', 'name' => 'Choco Chips', 'image' => 'choco_chips.jpg', 'variant' => 'tabung_m', 'price' => '71500', 'expired_day' => '30', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CLK-KT', 'name' => 'Choco Chips', 'image' => 'choco_chips.jpg', 'variant' => 'kotak', 'price' => '55000', 'expired_day' => '30', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'MVN-KT', 'name' => 'Mawar Vanilla', 'image' => 'mawar_vanilla.jpg', 'variant' => 'kotak', 'price' => '44000', 'expired_day' => '30', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'SCS-TM', 'name' => 'Snow Cashew', 'image' => 'snow_cashew.jpg', 'variant' => 'tabung_m', 'price' => '77000', 'expired_day' => '60', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CHC-TM', 'name' => 'Choco Cashew', 'image' => 'choco_cashew.jpg', 'variant' => 'tabung_m', 'price' => '77000', 'expired_day' => '60', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CHC-KT', 'name' => 'Choco Cashew', 'image' => 'choco_cashew.jpg', 'variant' => 'kotak', 'price' => '61600', 'expired_day' => '60', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CRF-TM', 'name' => 'Cornflakes', 'image' => 'cornflakes.jpg', 'variant' => 'tabung_m', 'price' => '82500', 'expired_day' => '30', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CRF-KT', 'name' => 'Cornflakes', 'image' => 'cornflakes.jpg', 'variant' => 'kotak', 'price' => '66000', 'expired_day' => '30', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'LDK-TS', 'name' => 'Lidah Kucing', 'image' => 'lidah_kucing.jpg', 'variant' => 'tabung_s', 'price' => '49500', 'expired_day' => '30', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'LDK-TM', 'name' => 'Lidah Kucing', 'image' => 'lidah_kucing.jpg', 'variant' => 'tabung_m', 'price' => '60500', 'expired_day' => '30', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CHS-TS', 'name' => 'Cheese Sagoo', 'image' => 'cheese_sagoo.jpg', 'variant' => 'tabung_s', 'price' => '51700', 'expired_day' => '60', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CHS-TM', 'name' => 'Cheese Sagoo', 'image' => 'cheese_sagoo.jpg', 'variant' => 'tabung_m', 'price' => '71500', 'expired_day' => '60', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CHS-KT', 'name' => 'Cheese Sagoo', 'image' => 'cheese_sagoo.jpg', 'variant' => 'kotak', 'price' => '58300', 'expired_day' => '60', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PBC-TM', 'name' => 'Peanut Butter Cookies', 'image' => 'peanut_butter.jpg', 'variant' => 'tabung_m', 'price' => '60500', 'expired_day' => '60', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'SSC-TM', 'name' => 'Sea Salt Cookies', 'image' => 'sea_salt_cookies.jpg', 'variant' => 'tabung_m', 'price' => '88000', 'expired_day' => '30', 'stock' => '0', 'created_at' => now(), 'updated_at' => now()],
        ];

        Product::insert($products);
    }
}
