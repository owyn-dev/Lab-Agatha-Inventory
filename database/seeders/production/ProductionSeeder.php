<?php

declare(strict_types=1);

namespace Database\Seeders\production;

use App\Models\DetailProduction;
use App\Models\InventoryIn;
use App\Models\Product;
use App\Models\Production;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvDataProduction = [
            ['01/07/2023', '30/08/2023', 'Snow Cashew', 'Tabung M', '33'],
            ['01/07/2023', '30/08/2023', 'Choco Cashew', 'Tabung M', '30'],
            ['02/07/2023', '01/08/2023', 'Mawar Vanilla', 'Kotak', '22'],
            ['02/07/2023', '31/08/2023', 'Choco Chips', 'Tabung M', '18'],
            ['02/07/2023', '01/08/2023', 'Sea Salt Cookies', 'Tabung M', '24'],
            ['03/07/2023', '02/08/2023', 'Nastar', 'Tabung M', '36'],
            ['03/07/2023', '02/08/2023', 'Nastar', 'Tabung S', '26'],
            ['04/07/2023', '02/09/2023', 'Choco Chips', 'Kotak', '19'],
            ['04/07/2023', '03/08/2023', 'Cornflakes', 'Tabung M', '27'],
            ['05/07/2023', '04/08/2023', 'Lidah Kucing', 'Tabung S', '31'],
            ['06/07/2023', '05/08/2023', 'Lidah Kucing', 'Tabung M', '43'],
            ['07/07/2023', '05/09/2023', 'Kastengel', 'Tabung S', '42'],
            ['07/07/2023', '05/09/2023', 'Peanut Butter Cookies', 'Tabung M', '39'],
            ['08/07/2023', '06/09/2023', 'Cheese Sagoo', 'Tabung S', '24'],
            ['09/07/2023', '07/09/2023', 'Cheese Sagoo', 'Tabung M', '38'],
            ['09/07/2023', '07/09/2023', 'Cheese Sagoo', 'Kotak', '25'],
            ['10/07/2023', '09/08/2023', 'Cornflakes', 'Kotak', '35'],
            ['11/07/2023', '10/08/2023', 'Mawar Vanilla', 'Kotak', '45'],
            ['23/07/2023', '21/09/2023', 'Kastengel', 'Tabung M', '55'],
            ['25/07/2023', '23/09/2023', 'Choco Cashew', 'Kotak', '35'],

            ['01/08/2023', '30/09/2023', 'Snow Cashew', 'Tabung M', '11'],
            ['01/08/2023', '30/09/2023', 'Choco Cashew', 'Tabung M', '10'],
            ['02/08/2023', '01/09/2023', 'Mawar Vanilla', 'Kotak', '10'],
            ['02/08/2023', '01/10/2023', 'Choco Chips', 'Tabung M', '8'],
            ['03/08/2023', '02/09/2023', 'Sea Salt Cookies', 'Tabung M', '12'],
            ['03/08/2023', '02/09/2023', 'Nastar', 'Tabung M', '10'],
            ['04/08/2023', '03/09/2023', 'Nastar', 'Tabung S', '12'],
            ['04/08/2023', '03/10/2023', 'Choco Chips', 'Kotak', '14'],
            ['05/08/2023', '04/09/2023', 'Cornflakes', 'Tabung M', '10'],
            ['05/08/2023', '04/09/2023', 'Lidah Kucing', 'Tabung S', '10'],
            ['06/08/2023', '05/09/2023', 'Lidah Kucing', 'Tabung M', '10'],
            ['07/08/2023', '06/10/2023', 'Kastengel', 'Tabung S', '25'],
            ['07/08/2023', '06/10/2023', 'Snow Cashew', 'Tabung M', '32'],
            ['08/08/2023', '07/10/2023', 'Peanut Butter Cookies', 'Tabung M', '30'],
            ['08/08/2023', '07/10/2023', 'Snow Cashew', 'Tabung M', '20'],
            ['09/08/2023', '08/10/2023', 'Cheese Sagoo', 'Tabung S', '10'],
            ['10/08/2023', '09/10/2023', 'Cheese Sagoo', 'Tabung M', '5'],
            ['10/08/2023', '09/10/2023', 'Cheese Sagoo', 'Kotak', '5'],
            ['11/08/2023', '10/09/2023', 'Cornflakes', 'Kotak', '10'],
            ['20/08/2023', '19/10/2023', 'Kastengel', 'Tabung M', '15'],
            ['25/08/2023', '24/10/2023', 'Choco Cashew', 'Kotak', '16'],
            ['25/08/2023', '24/10/2023', 'Peanut Butter Cookies', 'Tabung M', '10'],

            ['01/09/2023', '31/10/2023', 'Snow Cashew', 'Tabung M', '21'],
            ['01/09/2023', '31/10/2023', 'Choco Cashew', 'Tabung M', '25'],
            ['02/09/2023', '02/10/2023', 'Mawar Vanilla', 'Kotak', '12'],
            ['02/09/2023', '01/11/2023', 'Choco Chips', 'Tabung M', '8'],
            ['03/09/2023', '03/10/2023', 'Sea Salt Cookies', 'Tabung M', '14'],
            ['03/09/2023', '02/11/2023', 'Kastengel', 'Tabung M', '30'],
            ['04/09/2023', '04/10/2023', 'Nastar', 'Tabung M', '12'],
            ['04/09/2023', '04/10/2023', 'Nastar', 'Tabung S', '10'],
            ['05/09/2023', '04/11/2023', 'Choco Chips', 'Kotak', '10'],
            ['05/09/2023', '05/10/2023', 'Nastar', 'Tabung M', '25'],
            ['05/09/2023', '05/10/2023', 'Cornflakes', 'Tabung M', '10'],
            ['06/09/2023', '06/10/2023', 'Lidah Kucing', 'Tabung S', '15'],
            ['06/09/2023', '06/10/2023', 'Lidah Kucing', 'Tabung M', '30'],
            ['07/09/2023', '06/11/2023', 'Kastengel', 'Tabung S', '10'],
            ['07/09/2023', '07/10/2023', 'Sea Salt Cookies', 'Tabung M', '25'],
            ['08/09/2023', '07/11/2023', 'Peanut Butter Cookies', 'Tabung M', '20'],
            ['09/09/2023', '08/11/2023', 'Cheese Sagoo', 'Tabung S', '10'],
            ['10/09/2023', '09/11/2023', 'Cheese Sagoo', 'Tabung M', '10'],
            ['11/09/2023', '10/11/2023', 'Cheese Sagoo', 'Kotak', '10'],
            ['12/09/2023', '12/10/2023', 'Cornflakes', 'Kotak', '12'],
            ['13/09/2023', '12/11/2023', 'Snow Cashew', 'Tabung M', '21'],
            ['14/09/2023', '13/11/2023', 'Peanut Butter Cookies', 'Tabung M', '30'],
            ['22/09/2023', '22/10/2023', 'Nastar', 'Tabung M', '10'],
            ['23/09/2023', '22/11/2023', 'Snow Cashew', 'Tabung M', '20'],
            ['25/09/2023', '24/11/2023', 'Choco Cashew', 'Kotak', '5'],
            ['26/09/2023', '26/10/2023', 'Sea Salt Cookies', 'Tabung M', '15'],

            ['01/10/2023', '31/10/2023', 'Lidah Kucing', 'Tabung S', '20'],
            ['01/10/2023', '30/11/2023', 'Snow Cashew', 'Tabung M', '16'],
            ['01/10/2023', '30/11/2023', 'Choco Cashew', 'Tabung M', '30'],
            ['02/10/2023', '01/11/2023', 'Mawar Vanilla', 'Kotak', '10'],
            ['02/10/2023', '01/12/2023', 'Choco Chips', 'Tabung M', '20'],
            ['03/10/2023', '02/11/2023', 'Sea Salt Cookies', 'Tabung M', '15'],
            ['04/10/2023', '03/11/2023', 'Nastar', 'Tabung M', '11'],
            ['04/10/2023', '03/11/2023', 'Nastar', 'Tabung S', '4'],
            ['05/10/2023', '04/12/2023', 'Choco Chips', 'Kotak', '20'],
            ['05/10/2023', '04/11/2023', 'Cornflakes', 'Tabung M', '12'],
            ['06/10/2023', '05/11/2023', 'Lidah Kucing', 'Tabung M', '5'],
            ['07/10/2023', '06/12/2023', 'Kastengel', 'Tabung S', '25'],
            ['08/10/2023', '07/12/2023', 'Peanut Butter Cookies', 'Tabung M', '10'],
            ['09/10/2023', '08/12/2023', 'Cheese Sagoo', 'Tabung S', '16'],
            ['10/10/2023', '09/12/2023', 'Cheese Sagoo', 'Tabung M', '18'],
            ['11/10/2023', '10/12/2023', 'Cheese Sagoo', 'Kotak', '6'],
            ['12/10/2023', '11/11/2023', 'Cornflakes', 'Kotak', '6'],
            ['22/10/2023', '21/12/2023', 'Kastengel', 'Tabung M', '10'],
            ['22/10/2023', '21/11/2023', 'Mawar Vanilla', 'Kotak', '25'],
            ['22/10/2023', '21/11/2023', 'Lidah Kucing', 'Tabung S', '40'],
            ['25/10/2023', '24/12/2023', 'Choco Cashew', 'Kotak', '9'],
            ['26/10/2023', '25/12/2023', 'Choco Chips', 'Tabung M', '20'],

            ['01/11/2023', '31/12/2023', 'Snow Cashew', 'Tabung M', '25'],
            ['01/11/2023', '31/12/2023', 'Choco Cashew', 'Tabung M', '12'],
            ['02/11/2023', '02/12/2023', 'Mawar Vanilla', 'Kotak', '25'],
            ['02/11/2023', '01/01/2024', 'Choco Chips', 'Tabung M', '20'],
            ['03/11/2023', '03/12/2023', 'Sea Salt Cookies', 'Tabung M', '25'],
            ['04/11/2023', '04/12/2023', 'Nastar', 'Tabung M', '25'],
            ['04/11/2023', '04/12/2023', 'Nastar', 'Tabung S', '15'],
            ['05/11/2023', '04/01/2024', 'Choco Chips', 'Kotak', '12'],
            ['05/11/2023', '05/12/2023', 'Cornflakes', 'Tabung M', '15'],
            ['06/11/2023', '06/12/2023', 'Lidah Kucing', 'Tabung S', '14'],
            ['06/11/2023', '06/12/2023', 'Lidah Kucing', 'Tabung M', '10'],
            ['07/11/2023', '06/01/2024', 'Kastengel', 'Tabung S', '10'],
            ['08/11/2023', '07/01/2024', 'Peanut Butter Cookies', 'Tabung M', '30'],
            ['08/11/2023', '07/01/2024', 'Choco Chips', 'Tabung M', '35'],
            ['09/11/2023', '08/01/2024', 'Cheese Sagoo', 'Tabung S', '5'],
            ['10/11/2023', '09/01/2024', 'Cheese Sagoo', 'Tabung M', '10'],
            ['11/11/2023', '10/01/2024', 'Cheese Sagoo', 'Kotak', '10'],
            ['12/11/2023', '12/12/2023', 'Cornflakes', 'Kotak', '9'],
            ['22/11/2023', '21/01/2024', 'Kastengel', 'Tabung M', '10'],
            ['25/11/2023', '24/01/2024', 'Choco Cashew', 'Kotak', '10'],
            ['27/11/2023', '26/01/2024', 'Snow Cashew', 'Tabung M', '50'],
            ['28/11/2023', '27/01/2024', 'Peanut Butter Cookies', 'Tabung M', '20'],

            ['01/12/2023', '30/01/2024', 'Snow Cashew', 'Tabung M', '18'],
            ['01/12/2023', '30/01/2024', 'Choco Cashew', 'Tabung M', '10'],
            ['02/12/2023', '01/01/2024', 'Mawar Vanilla', 'Kotak', '12'],
            ['02/12/2023', '31/01/2024', 'Choco Chips', 'Tabung M', '10'],
            ['03/12/2023', '02/01/2024', 'Sea Salt Cookies', 'Tabung M', '14'],
            ['04/12/2023', '03/01/2024', 'Nastar', 'Tabung M', '20'],
            ['04/12/2023', '03/01/2024', 'Nastar', 'Tabung S', '22'],
            ['04/12/2023', '02/02/2024', 'Choco Cashew', 'Tabung M', '20'],
            ['05/12/2023', '03/02/2024', 'Choco Chips', 'Kotak', '9'],
            ['05/12/2023', '04/01/2024', 'Cornflakes', 'Tabung M', '14'],
            ['06/12/2023', '05/01/2024', 'Lidah Kucing', 'Tabung S', '11'],
            ['06/12/2023', '05/01/2024', 'Lidah Kucing', 'Tabung M', '30'],
            ['07/12/2023', '05/02/2024', 'Kastengel', 'Tabung S', '24'],
            ['08/12/2023', '06/02/2024', 'Peanut Butter Cookies', 'Tabung M', '12'],
            ['09/12/2023', '07/02/2024', 'Cheese Sagoo', 'Tabung S', '10'],
            ['10/12/2023', '08/02/2024', 'Cheese Sagoo', 'Tabung M', '8'],
            ['11/12/2023', '09/02/2024', 'Cheese Sagoo', 'Kotak', '5'],
            ['11/12/2023', '10/01/2024', 'Sea Salt Cookies', 'Tabung M', '30'],
            ['12/12/2023', '11/01/2024', 'Cornflakes', 'Kotak', '8'],
            ['22/12/2023', '20/02/2024', 'Kastengel', 'Tabung M', '19'],
            ['23/12/2023', '22/01/2024', 'Mawar Vanilla', 'Kotak', '20'],
            ['25/12/2023', '23/02/2024', 'Choco Cashew', 'Kotak', '12'],
        ];

        $groupedProductions = [];

        foreach ($csvDataProduction as $row) {
            [$productionDate, $expirationDate, $menu, $variant, $quantity] = $row;

            $productionDate = Carbon::createFromFormat('d/m/Y', mb_trim($productionDate));
            $expirationDate = Carbon::createFromFormat('d/m/Y', mb_trim($expirationDate));

            $product = Product::where('name', $this->cleanText($menu))
                ->where('variant', mb_strtolower(str_replace(' ', '_', mb_trim($variant))))
                ->first();

            if ( ! $product) {
                continue;
            }

            $groupedProductions[$productionDate->toDateString()][] = [
                'product_id' => $product->id,
                'batch_code' => $this->generateBatchCode(),
                'expiration_date' => $expirationDate,
                'variant' => $variant,
                'quantity' => (int) $quantity,
            ];
        }

        DB::transaction(function () use ($groupedProductions): void {
            foreach ($groupedProductions as $date => $details) {
                $production = Production::create([
                    'inventory_user_id' => 1,
                    'production_request_date' => Carbon::parse($date),
                    'production_user_id' => 1,
                    'production_date' => Carbon::parse($date),
                    'status' => 'approved',
                    'note' => 'Data CSV',
                ]);

                foreach ($details as $detail) {
                    $quantity = $detail['quantity'];

                    DetailProduction::create([
                        'production_id' => $production->id,
                        'product_id' => $detail['product_id'],
                        'batch_code' => $detail['batch_code'],
                        'shelf_name' => '-',
                        'quantity' => $quantity,
                    ]);

                    InventoryIn::create([
                        'product_id' => $detail['product_id'],
                        'batch_code' => $detail['batch_code'],
                        'transaction_date' => Carbon::parse($date),
                        'shelf_name' => '-',
                        'stock_start' => $quantity,
                        'current_stock' => $quantity,
                        'unit_price' => Product::find($detail['product_id'])->price,
                        'expiration_date' => $detail['expiration_date'],
                    ]);

                    $product = Product::find($detail['product_id']);
                    $product->increment('stock', $quantity);
                }
            }
        });
    }

    public function cleanText($text)
    {
        $text = str_replace("\u{A0}", ' ', $text);

        return mb_trim(preg_replace('/\s+/', ' ', $text));
    }

    public function generateBatchCode()
    {
        $uuid = Str::uuid()->toString();
        $barcode = mb_substr(preg_replace('/[^0-9]/', '', $uuid), 0, 10);

        return mb_str_pad($barcode, 10, '0', STR_PAD_LEFT);
    }
}
