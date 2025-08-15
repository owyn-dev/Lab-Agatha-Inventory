<?php

declare(strict_types=1);

namespace Database\Seeders\sale;

use App\Models\DetailSale;
use App\Models\InventoryIn;
use App\Models\InventoryOut;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class SaleSeeder extends Seeder
{
    public $csv_data_sale = [
        ['01/07/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['01/07/2023', 'Choco Cashew', 'Tabung M', '1', '70000', '70000'],
        ['02/07/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['02/07/2023', 'Choco Cashew', 'Tabung M', '2', '70000', '140000'],
        ['03/07/2023', 'Choco Cashew', 'Tabung M', '1', '70000', '70000'],
        ['03/07/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['04/07/2023', 'Choco Chips', 'Tabung M', '1', '65000', '65000'],
        ['04/07/2023', 'Nastar', 'Tabung S', '3', '80000', '240000'],
        ['05/07/2023', 'Sea Salt Cookies', 'Tabung M', '4', '80000', '320000'],
        ['05/07/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['06/07/2023', 'Nastar', 'Tabung M', '2', '100000', '200000'],
        ['07/07/2023', 'Choco Cashew', 'Tabung M', '1', '70000', '70000'],
        ['07/07/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['08/07/2023', 'Nastar', 'Tabung M', '6', '100000', '600000'],
        ['08/07/2023', 'Lidah Kucing', 'Tabung M', '30', '55000', '1650000'],
        ['09/07/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['09/07/2023', 'Kastengel', 'Tabung S', '20', '120000', '2400000'],
        ['10/07/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['11/07/2023', 'Choco Cashew', 'Tabung M', '6', '70000', '420000'],
        ['11/07/2023', 'Choco Chips', 'Kotak', '1', '50000', '50000'],
        ['12/07/2023', 'Mawar Vanilla', 'Kotak', '40', '40000', '1600000'],
        ['13/07/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['14/07/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['14/07/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['15/07/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['15/07/2023', 'Lidah Kucing', 'Tabung S', '1', '45000', '45000'],
        ['16/07/2023', 'Cheese Sagoo', 'Kotak', '6', '53000', '318000'],
        ['16/07/2023', 'Cheese Sagoo', 'Tabung M', '4', '65000', '260000'],
        ['17/07/2023', 'Cheese Sagoo', 'Tabung S', '6', '47000', '282000'],
        ['17/07/2023', 'Kastengel', 'Tabung S', '1', '120000', '120000'],
        ['18/07/2023', 'Sea Salt Cookies', 'Tabung M', '4', '80000', '320000'],
        ['18/07/2023', 'Mawar Vanilla', 'Kotak', '4', '40000', '160000'],
        ['19/07/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['20/07/2023', 'Cheese Sagoo', 'Kotak', '5', '53000', '265000'],
        ['21/07/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['22/07/2023', 'Cornflakes', 'Kotak', '3', '60000', '180000'],
        ['22/07/2023', 'Mawar Vanilla', 'Kotak', '4', '40000', '160000'],
        ['23/07/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['24/07/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['25/07/2023', 'Kastengel', 'Tabung M', '30', '155000', '4650000'],
        ['25/07/2023', 'Nastar', 'Tabung S', '6', '80000', '480000'],
        ['26/07/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['27/07/2023', 'Kastengel', 'Tabung M', '4', '155000', '620000'],
        ['27/07/2023', 'Choco Chips', 'Tabung M', '4', '65000', '260000'],
        ['28/07/2023', 'Sea Salt Cookies', 'Tabung M', '2', '80000', '160000'],
        ['29/07/2023', 'Cheese Sagoo', 'Tabung M', '1', '65000', '65000'],
        ['30/07/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['30/07/2023', 'Cornflakes', 'Tabung M', '6', '75000', '450000'],
        ['31/07/2023', 'Choco Cashew', 'Kotak', '3', '56000', '168000'],
        ['31/07/2023', 'Cornflakes', 'Kotak', '1', '60000', '60000'],

        ['01/08/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['02/08/2023', 'Cornflakes', 'Tabung M', '1', '75000', '75000'],
        ['02/08/2023', 'Choco Cashew', 'Tabung M', '4', '70000', '280000'],
        ['02/08/2023', 'Choco Chips', 'Tabung M', '3', '65000', '195000'],
        ['03/08/2023', 'Peanut Butter Cookies', 'Tabung M', '6', '55000', '330000'],
        ['04/08/2023', 'Snow Cashew', 'Tabung M', '6', '70000', '420000'],
        ['04/08/2023', 'Choco Chips', 'Kotak', '2', '50000', '100000'],
        ['04/08/2023', 'Lidah Kucing', 'Tabung S', '1', '45000', '45000'],
        ['05/08/2023', 'Lidah Kucing', 'Tabung M', '1', '55000', '55000'],
        ['05/08/2023', 'Snow Cashew', 'Tabung M', '3', '70000', '210000'],
        ['05/08/2023', 'Cheese Sagoo', 'Kotak', '1', '53000', '53000'],
        ['06/08/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['06/08/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['07/08/2023', 'Cornflakes', 'Tabung M', '1', '75000', '75000'],
        ['08/08/2023', 'Nastar', 'Tabung M', '3', '100000', '300000'],
        ['08/08/2023', 'Kastengel', 'Tabung M', '5', '155000', '775000'],
        ['09/08/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['09/08/2023', 'Snow Cashew', 'Tabung M', '30', '70000', '2100000'],
        ['09/08/2023', 'Cornflakes', 'Kotak', '5', '60000', '300000'],
        ['10/08/2023', 'Kastengel', 'Tabung S', '3', '120000', '360000'],
        ['10/08/2023', 'Lidah Kucing', 'Tabung M', '5', '55000', '275000'],
        ['11/08/2023', 'Choco Cashew', 'Kotak', '2', '56000', '112000'],
        ['11/08/2023', 'Peanut Butter Cookies', 'Tabung M', '5', '55000', '275000'],
        ['11/08/2023', 'Choco Cashew', 'Tabung M', '1', '70000', '70000'],
        ['12/08/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['12/08/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['13/08/2023', 'Cheese Sagoo', 'Tabung M', '4', '65000', '260000'],
        ['14/08/2023', 'Snow Cashew', 'Tabung M', '6', '70000', '420000'],
        ['14/08/2023', 'Mawar Vanilla', 'Kotak', '6', '40000', '240000'],
        ['15/08/2023', 'Cheese Sagoo', 'Tabung M', '5', '65000', '325000'],
        ['15/08/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['16/08/2023', 'Choco Cashew', 'Kotak', '5', '56000', '280000'],
        ['17/08/2023', 'Snow Cashew', 'Tabung M', '3', '70000', '210000'],
        ['18/08/2023', 'Lidah Kucing', 'Tabung S', '5', '45000', '225000'],
        ['18/08/2023', 'Snow Cashew', 'Tabung M', '4', '70000', '280000'],
        ['19/08/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['19/08/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['19/08/2023', 'Choco Cashew', 'Kotak', '1', '56000', '56000'],
        ['20/08/2023', 'Choco Cashew', 'Kotak', '2', '56000', '112000'],
        ['21/08/2023', 'Kastengel', 'Tabung M', '1', '155000', '155000'],
        ['21/08/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['21/08/2023', 'Choco Cashew', 'Tabung M', '4', '70000', '280000'],
        ['22/08/2023', 'Snow Cashew', 'Tabung M', '6', '70000', '420000'],
        ['22/08/2023', 'Choco Chips', 'Tabung M', '4', '65000', '260000'],
        ['23/08/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['24/08/2023', 'Cornflakes', 'Kotak', '6', '60000', '360000'],
        ['24/08/2023', 'Snow Cashew', 'Tabung M', '4', '70000', '280000'],
        ['24/08/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['25/08/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['25/08/2023', 'Choco Cashew', 'Kotak', '4', '56000', '224000'],
        ['26/08/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['27/08/2023', 'Choco Chips', 'Kotak', '3', '50000', '150000'],
        ['27/08/2023', 'Peanut Butter Cookies', 'Tabung M', '40', '55000', '2200000'],
        ['28/08/2023', 'Peanut Butter Cookies', 'Tabung M', '2', '55000', '110000'],
        ['28/08/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['28/08/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['29/08/2023', 'Choco Chips', 'Kotak', '4', '50000', '200000'],
        ['29/08/2023', 'Snow Cashew', 'Tabung M', '6', '70000', '420000'],
        ['30/08/2023', 'Cheese Sagoo', 'Tabung S', '4', '47000', '188000'],
        ['30/08/2023', 'Kastengel', 'Tabung S', '4', '120000', '480000'],
        ['30/08/2023', 'Cheese Sagoo', 'Kotak', '1', '53000', '53000'],

        ['01/09/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['01/09/2023', 'Cheese Sagoo', 'Tabung S', '1', '47000', '47000'],
        ['02/09/2023', 'Lidah Kucing', 'Tabung M', '1', '55000', '55000'],
        ['03/09/2023', 'Kastengel', 'Tabung M', '4', '155000', '620000'],
        ['03/09/2023', 'Cheese Sagoo', 'Tabung M', '1', '65000', '65000'],
        ['04/09/2023', 'Peanut Butter Cookies', 'Tabung M', '4', '55000', '220000'],
        ['04/09/2023', 'Cornflakes', 'Tabung M', '1', '75000', '75000'],
        ['05/09/2023', 'Kastengel', 'Tabung M', '40', '155000', '6200000'],
        ['05/09/2023', 'Lidah Kucing', 'Tabung M', '5', '55000', '275000'],
        ['05/09/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['05/09/2023', 'Cheese Sagoo', 'Kotak', '1', '53000', '53000'],
        ['06/09/2023', 'Choco Cashew', 'Tabung M', '1', '70000', '70000'],
        ['06/09/2023', 'Nastar', 'Tabung M', '30', '100000', '3000000'],
        ['07/09/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['07/09/2023', 'Cornflakes', 'Tabung M', '6', '75000', '450000'],
        ['08/09/2023', 'Choco Chips', 'Tabung M', '2', '65000', '130000'],
        ['08/09/2023', 'Sea Salt Cookies', 'Tabung M', '30', '80000', '4000000'],
        ['09/09/2023', 'Snow Cashew', 'Tabung M', '6', '70000', '420000'],
        ['09/09/2023', 'Kastengel', 'Tabung S', '5', '120000', '600000'],
        ['09/09/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['09/09/2023', 'Choco Chips', 'Tabung M', '1', '65000', '65000'],
        ['09/09/2023', 'Sea Salt Cookies', 'Tabung M', '3', '80000', '240000'],
        ['10/09/2023', 'Cornflakes', 'Kotak', '3', '60000', '180000'],
        ['10/09/2023', 'Snow Cashew', 'Tabung M', '6', '70000', '420000'],
        ['10/09/2023', 'Snow Cashew', 'Tabung M', '2', '70000', '140000'],
        ['11/09/2023', 'Snow Cashew', 'Tabung M', '5', '70000', '350000'],
        ['12/09/2023', 'Nastar', 'Tabung M', '4', '100000', '400000'],
        ['13/09/2023', 'Choco Cashew', 'Tabung M', '2', '70000', '140000'],
        ['13/09/2023', 'Choco Cashew', 'Kotak', '1', '56000', '56000'],
        ['14/09/2023', 'Cheese Sagoo', 'Tabung M', '6', '65000', '390000'],
        ['14/09/2023', 'Snow Cashew', 'Tabung M', '20', '70000', '1400000'],
        ['15/09/2023', 'Peanut Butter Cookies', 'Tabung M', '6', '55000', '330000'],
        ['15/09/2023', 'Peanut Butter Cookies', 'Tabung M', '40', '55000', '2200000'],
        ['15/09/2023', 'Mawar Vanilla', 'Kotak', '2', '40000', '80000'],
        ['15/09/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['16/09/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['16/09/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['16/09/2023', 'Choco Chips', 'Kotak', '1', '50000', '50000'],
        ['16/09/2023', 'Cheese Sagoo', 'Kotak', '2', '53000', '106000'],
        ['17/09/2023', 'Sea Salt Cookies', 'Tabung M', '3', '80000', '240000'],
        ['17/09/2023', 'Cornflakes', 'Tabung M', '1', '75000', '75000'],
        ['18/09/2023', 'Peanut Butter Cookies', 'Tabung M', '4', '55000', '220000'],
        ['18/09/2023', 'Sea Salt Cookies', 'Tabung M', '5', '80000', '400000'],
        ['18/09/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['18/09/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['19/09/2023', 'Mawar Vanilla', 'Kotak', '6', '40000', '240000'],
        ['19/09/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['20/09/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['21/09/2023', 'Snow Cashew', 'Tabung M', '2', '70000', '140000'],
        ['21/09/2023', 'Cheese Sagoo', 'Tabung M', '1', '65000', '65000'],
        ['22/09/2023', 'Lidah Kucing', 'Tabung S', '6', '45000', '270000'],
        ['22/09/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['23/09/2023', 'Sea Salt Cookies', 'Tabung M', '2', '80000', '160000'],
        ['23/09/2023', 'Nastar', 'Tabung M', '20', '100000', '3000000'],
        ['23/09/2023', 'Kastengel', 'Tabung M', '1', '155000', '155000'],
        ['24/09/2023', 'Choco Chips', 'Kotak', '3', '50000', '150000'],
        ['25/09/2023', 'Snow Cashew', 'Tabung M', '20', '70000', '2800000'],
        ['25/09/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['26/09/2023', 'Sea Salt Cookies', 'Tabung M', '3', '80000', '240000'],
        ['27/09/2023', 'Lidah Kucing', 'Tabung S', '30', '45000', '1350000'],
        ['27/09/2023', 'Sea Salt Cookies', 'Tabung M', '10', '80000', '3200000'],
        ['28/09/2023', 'Mawar Vanilla', 'Kotak', '5', '40000', '200000'],
        ['28/09/2023', 'Choco Cashew', 'Tabung M', '40', '70000', '2800000'],
        ['28/09/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['28/09/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['29/09/2023', 'Peanut Butter Cookies', 'Tabung M', '3', '55000', '165000'],
        ['29/09/2023', 'Choco Cashew', 'Kotak', '1', '56000', '56000'],
        ['29/09/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['30/09/2023', 'Cornflakes', 'Tabung M', '1', '75000', '75000'],
        ['30/09/2023', 'Kastengel', 'Tabung S', '2', '120000', '240000'],
        ['30/09/2023', 'Kastengel', 'Tabung M', '1', '155000', '155000'],

        ['01/10/2023', 'Lidah Kucing', 'Tabung S', '6', '45000', '270000'],
        ['02/10/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['02/10/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['02/10/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['02/10/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['02/10/2023', 'Choco Chips', 'Kotak', '1', '50000', '50000'],
        ['02/10/2023', 'Lidah Kucing', 'Tabung S', '5', '45000', '225000'],
        ['03/10/2023', 'Choco Chips', 'Kotak', '1', '50000', '50000'],
        ['04/10/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['05/10/2023', 'Choco Cashew', 'Kotak', '4', '56000', '224000'],
        ['06/10/2023', 'Sea Salt Cookies', 'Tabung M', '4', '80000', '320000'],
        ['06/10/2023', 'Lidah Kucing', 'Tabung M', '1', '55000', '55000'],
        ['07/10/2023', 'Cornflakes', 'Kotak', '2', '60000', '120000'],
        ['07/10/2023', 'Snow Cashew', 'Tabung M', '2', '70000', '140000'],
        ['07/10/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['08/10/2023', 'Cheese Sagoo', 'Tabung M', '1', '65000', '65000'],
        ['09/10/2023', 'Nastar', 'Tabung S', '3', '80000', '240000'],
        ['09/10/2023', 'Cornflakes', 'Kotak', '1', '60000', '60000'],
        ['10/10/2023', 'Choco Chips', 'Tabung M', '5', '65000', '325000'],
        ['10/10/2023', 'Choco Chips', 'Kotak', '30', '50000', '1500000'],
        ['10/10/2023', 'Mawar Vanilla', 'Kotak', '2', '40000', '1200000'],
        ['11/10/2023', 'Choco Chips', 'Tabung M', '4', '65000', '260000'],
        ['11/10/2023', 'Cheese Sagoo', 'Tabung M', '40', '65000', '2600000'],
        ['11/10/2023', 'Choco Cashew', 'Kotak', '1', '56000', '56000'],
        ['12/10/2023', 'Choco Chips', 'Kotak', '2', '50000', '100000'],
        ['12/10/2023', 'Kastengel', 'Tabung M', '6', '155000', '930000'],
        ['12/10/2023', 'Choco Cashew', 'Tabung M', '20', '70000', '1400000'],
        ['13/10/2023', 'Lidah Kucing', 'Tabung M', '3', '55000', '165000'],
        ['13/10/2023', 'Lidah Kucing', 'Tabung M', '1', '55000', '55000'],
        ['13/10/2023', 'Peanut Butter Cookies', 'Tabung M', '3', '55000', '165000'],
        ['14/10/2023', 'Cheese Sagoo', 'Kotak', '2', '53000', '106000'],
        ['15/10/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['15/10/2023', 'Lidah Kucing', 'Tabung M', '1', '55000', '55000'],
        ['16/10/2023', 'Snow Cashew', 'Tabung M', '5', '70000', '350000'],
        ['16/10/2023', 'Snow Cashew', 'Tabung M', '6', '70000', '420000'],
        ['16/10/2023', 'Mawar Vanilla', 'Kotak', '4', '40000', '1200000'],
        ['17/10/2023', 'Nastar', 'Tabung S', '2', '80000', '160000'],
        ['17/10/2023', 'Lidah Kucing', 'Tabung M', '6', '55000', '330000'],
        ['17/10/2023', 'Sea Salt Cookies', 'Tabung M', '4', '80000', '320000'],
        ['18/10/2023', 'Choco Chips', 'Kotak', '1', '50000', '50000'],
        ['19/10/2023', 'Choco Cashew', 'Kotak', '1', '56000', '56000'],
        ['19/10/2023', 'Mawar Vanilla', 'Kotak', '2', '40000', '80000'],
        ['20/10/2023', 'Choco Cashew', 'Kotak', '2', '56000', '112000'],
        ['21/10/2023', 'Snow Cashew', 'Tabung M', '6', '70000', '420000'],
        ['21/10/2023', 'Lidah Kucing', 'Tabung S', '6', '45000', '270000'],
        ['22/10/2023', 'Choco Cashew', 'Tabung M', '1', '70000', '70000'],
        ['22/10/2023', 'Sea Salt Cookies', 'Tabung M', '5', '80000', '400000'],
        ['22/10/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['22/10/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['22/10/2023', 'Mawar Vanilla', 'Kotak', '6', '40000', '240000'],
        ['23/10/2023', 'Mawar Vanilla', 'Kotak', '30', '40000', '1200000'],
        ['23/10/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['23/10/2023', 'Lidah Kucing', 'Tabung S', '40', '45000', '1800000'],
        ['24/10/2023', 'Lidah Kucing', 'Tabung M', '1', '55000', '55000'],
        ['24/10/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['24/10/2023', 'Cheese Sagoo', 'Tabung S', '3', '47000', '141000'],
        ['25/10/2023', 'Kastengel', 'Tabung M', '1', '155000', '155000'],
        ['26/10/2023', 'Kastengel', 'Tabung S', '40', '120000', '4800000'],
        ['26/10/2023', 'Cheese Sagoo', 'Tabung S', '5', '47000', '235000'],
        ['27/10/2023', 'Cornflakes', 'Tabung M', '30', '75000', '2250000'],
        ['27/10/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['27/10/2023', 'Cheese Sagoo', 'Kotak', '1', '53000', '53000'],
        ['28/10/2023', 'Choco Chips', 'Tabung M', '40', '65000', '2600000'],
        ['28/10/2023', 'Cornflakes', 'Tabung M', '4', '75000', '300000'],
        ['29/10/2023', 'Choco Cashew', 'Kotak', '1', '56000', '56000'],
        ['30/10/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['31/10/2023', 'Cornflakes', 'Kotak', '4', '60000', '240000'],
        ['31/10/2023', 'Choco Cashew', 'Tabung M', '1', '70000', '70000'],

        ['01/11/2023', 'Choco Cashew', 'Kotak', '2', '56000', '112000'],
        ['01/11/2023', 'Choco Cashew', 'Tabung M', '1', '70000', '70000'],
        ['02/11/2023', 'Nastar', 'Tabung S', '20', '80000', '1600000'],
        ['03/11/2023', 'Mawar Vanilla', 'Kotak', '5', '40000', '200000'],
        ['03/11/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['04/11/2023', 'Cornflakes', 'Kotak', '3', '60000', '180000'],
        ['04/11/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['05/11/2023', 'Choco Cashew', 'Kotak', '5', '56000', '280000'],
        ['05/11/2023', 'Choco Chips', 'Tabung M', '1', '65000', '65000'],
        ['06/11/2023', 'Choco Cashew', 'Kotak', '6', '56000', '336000'],
        ['07/11/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['07/11/2023', 'Sea Salt Cookies', 'Tabung M', '2', '80000', '160000'],
        ['08/11/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['09/11/2023', 'Nastar', 'Tabung M', '5', '100000', '500000'],
        ['09/11/2023', 'Choco Cashew', 'Tabung M', '1', '70000', '70000'],
        ['10/11/2023', 'Choco Chips', 'Tabung M', '40', '65000', '2600000'],
        ['10/11/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['11/11/2023', 'Sea Salt Cookies', 'Tabung M', '20', '80000', '1600000'],
        ['12/11/2023', 'Snow Cashew', 'Tabung M', '4', '70000', '280000'],
        ['13/11/2023', 'Snow Cashew', 'Tabung M', '3', '70000', '210000'],
        ['14/11/2023', 'Lidah Kucing', 'Tabung S', '4', '45000', '180000'],
        ['15/11/2023', 'Cornflakes', 'Tabung M', '1', '75000', '75000'],
        ['16/11/2023', 'Kastengel', 'Tabung M', '5', '155000', '775000'],
        ['17/11/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['17/11/2023', 'Lidah Kucing', 'Tabung M', '4', '55000', '220000'],
        ['18/11/2023', 'Choco Chips', 'Kotak', '1', '50000', '50000'],
        ['18/11/2023', 'Choco Cashew', 'Kotak', '1', '56000', '56000'],
        ['18/11/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['18/11/2023', 'Nastar', 'Tabung M', '20', '100000', '2000000'],
        ['18/11/2023', 'Peanut Butter Cookies', 'Tabung M', '5', '55000', '275000'],
        ['18/11/2023', 'Peanut Butter Cookies', 'Tabung M', '3', '55000', '165000'],
        ['19/11/2023', 'Cornflakes', 'Tabung M', '1', '75000', '75000'],
        ['19/11/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['20/11/2023', 'Peanut Butter Cookies', 'Tabung M', '4', '55000', '220000'],
        ['21/11/2023', 'Cheese Sagoo', 'Tabung S', '4', '47000', '188000'],
        ['22/11/2023', 'Choco Chips', 'Tabung M', '4', '65000', '260000'],
        ['22/11/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['23/11/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['23/11/2023', 'Cheese Sagoo', 'Tabung S', '1', '47000', '47000'],
        ['24/11/2023', 'Peanut Butter Cookies', 'Tabung M', '5', '55000', '275000'],
        ['25/11/2023', 'Cheese Sagoo', 'Tabung M', '2', '65000', '130000'],
        ['26/11/2023', 'Snow Cashew', 'Tabung M', '5', '70000', '350000'],
        ['27/11/2023', 'Mawar Vanilla', 'Kotak', '5', '40000', '200000'],
        ['28/11/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['28/11/2023', 'Nastar', 'Tabung M', '5', '100000', '500000'],
        ['29/11/2023', 'Snow Cashew', 'Tabung M', '50', '70000', '3500000'],
        ['29/11/2023', 'Peanut Butter Cookies', 'Tabung M', '20', '55000', '1100000'],
        ['30/11/2023', 'Peanut Butter Cookies', 'Tabung M', '2', '55000', '110000'],

        ['01/12/2023', 'Lidah Kucing', 'Tabung S', '1', '45000', '45000'],
        ['01/12/2023', 'Snow Cashew', 'Tabung M', '4', '70000', '280000'],
        ['02/12/2023', 'Mawar Vanilla', 'Kotak', '5', '40000', '200000'],
        ['03/12/2023', 'Mawar Vanilla', 'Kotak', '4', '40000', '160000'],
        ['03/12/2023', 'Cornflakes', 'Tabung M', '4', '75000', '300000'],
        ['04/12/2023', 'Choco Chips', 'Kotak', '6', '50000', '300000'],
        ['04/12/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['04/12/2023', 'Mawar Vanilla', 'Kotak', '1', '40000', '40000'],
        ['05/12/2023', 'Lidah Kucing', 'Tabung S', '1', '45000', '45000'],
        ['05/12/2023', 'Kastengel', 'Tabung S', '4', '120000', '480000'],
        ['05/12/2023', 'Choco Cashew', 'Tabung M', '30', '70000', '2100000'],
        ['06/12/2023', 'Kastengel', 'Tabung S', '5', '120000', '600000'],
        ['07/12/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['08/12/2023', 'Nastar', 'Tabung S', '3', '80000', '240000'],
        ['08/12/2023', 'Cornflakes', 'Kotak', '1', '60000', '60000'],
        ['09/12/2023', 'Peanut Butter Cookies', 'Tabung M', '5', '55000', '275000'],
        ['10/12/2023', 'Nastar', 'Tabung M', '20', '100000', '2000000'],
        ['11/12/2023', 'Peanut Butter Cookies', 'Tabung M', '1', '55000', '55000'],
        ['11/12/2023', 'Nastar', 'Tabung M', '1', '100000', '100000'],
        ['11/12/2023', 'Cornflakes', 'Kotak', '1', '60000', '60000'],
        ['12/12/2023', 'Sea Salt Cookies', 'Tabung M', '2', '80000', '160000'],
        ['13/12/2023', 'Choco Cashew', 'Tabung M', '3', '70000', '210000'],
        ['13/12/2023', 'Choco Cashew', 'Tabung M', '2', '70000', '140000'],
        ['13/12/2023', 'Choco Cashew', 'Kotak', '1', '56000', '56000'],
        ['14/12/2023', 'Sea Salt Cookies', 'Tabung M', '2', '80000', '160000'],
        ['14/12/2023', 'Choco Cashew', 'Kotak', '1', '56000', '56000'],
        ['14/12/2023', 'Cheese Sagoo', 'Kotak', '2', '53000', '106000'],
        ['15/12/2023', 'Nastar', 'Tabung S', '1', '80000', '80000'],
        ['15/12/2023', 'Choco Cashew', 'Tabung M', '6', '70000', '420000'],
        ['15/12/2023', 'Sea Salt Cookies', 'Tabung M', '1', '80000', '80000'],
        ['16/12/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['17/12/2023', 'Lidah Kucing', 'Tabung S', '1', '45000', '45000'],
        ['18/12/2023', 'Choco Chips', 'Tabung M', '1', '65000', '65000'],
        ['19/12/2023', 'Sea Salt Cookies', 'Tabung M', '2', '80000', '160000'],
        ['19/12/2023', 'Sea Salt Cookies', 'Tabung M', '6', '80000', '480000'],
        ['19/12/2023', 'Cheese Sagoo', 'Tabung S', '20', '47000', '940000'],
        ['20/12/2023', 'Snow Cashew', 'Tabung M', '4', '70000', '280000'],
        ['20/12/2023', 'Lidah Kucing', 'Tabung S', '1', '45000', '45000'],
        ['20/12/2023', 'Lidah Kucing', 'Tabung M', '50', '55000', '2750000'],
        ['21/12/2023', 'Lidah Kucing', 'Tabung M', '1', '55000', '55000'],
        ['22/12/2023', 'Nastar', 'Tabung S', '30', '80000', '2400000'],
        ['22/12/2023', 'Cheese Sagoo', 'Tabung M', '6', '65000', '390000'],
        ['23/12/2023', 'Lidah Kucing', 'Tabung S', '2', '45000', '90000'],
        ['24/12/2023', 'Mawar Vanilla', 'Kotak', '4', '40000', '160000'],
        ['24/12/2023', 'Sea Salt Cookies', 'Tabung M', '30', '80000', '2400000'],
        ['24/12/2023', 'Lidah Kucing', 'Tabung S', '1', '45000', '45000'],
        ['24/12/2023', 'Mawar Vanilla', 'Kotak', '20', '40000', '800000'],
        ['25/12/2023', 'Choco Chips', 'Kotak', '4', '50000', '200000'],
        ['25/12/2023', 'Kastengel', 'Tabung M', '1', '155000', '155000'],
        ['25/12/2023', 'Cheese Sagoo', 'Kotak', '4', '53000', '212000'],
        ['26/12/2023', 'Choco Cashew', 'Kotak', '1', '56000', '56000'],
        ['26/12/2023', 'Snow Cashew', 'Tabung M', '1', '70000', '70000'],
        ['27/12/2023', 'Lidah Kucing', 'Tabung S', '1', '45000', '45000'],
        ['27/12/2023', 'Kastengel', 'Tabung M', '20', '155000', '3100000'],
        ['28/12/2023', 'Cheese Sagoo', 'Tabung S', '1', '47000', '47000'],
        ['28/12/2023', 'Peanut Butter Cookies', 'Tabung M', '4', '55000', '220000'],
        ['29/12/2023', 'Cornflakes', 'Tabung M', '4', '75000', '300000'],
        ['29/12/2023', 'Kastengel', 'Tabung S', '1', '120000', '120000'],
        ['30/12/2023', 'Choco Cashew', 'Kotak', '6', '56000', '336000'],
        ['30/12/2023', 'Choco Chips', 'Tabung M', '2', '65000', '130000'],
        ['31/12/2023', 'Choco Chips', 'Kotak', '4', '50000', '200000'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupedSales = $this->add_batch_code_sale();

        DB::transaction(function () use ($groupedSales): void {
            foreach ($groupedSales as $date => $details) {
                $totalAmount = 0;

                $sales = Sale::create([
                    'sales_user_id' => 1,
                    'transaction_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $date),
                    'total_amount' => $totalAmount,
                ]);

                foreach ($details as $detail) {
                    DetailSale::create([
                        'sales_id' => $sales->id,
                        'product_id' => $detail['product_id'],
                        'quantity' => $detail['quantity'],
                        'price' => $detail['price'],
                        'sub_total' => $detail['sub_total'],
                    ]);

                    $totalAmount += $detail['sub_total'];

                    $inventoryIn = InventoryIn::where('batch_code', $detail['batch_code'])->first();

                    InventoryOut::create([
                        'inventory_in_id' => $inventoryIn->id,
                        'batch_code' => $detail['batch_code'],
                        'transaction_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $date),
                        'shelf_name' => '-',
                        'stock_out' => $detail['quantity'],
                    ]);
                }

                $sales->total_amount = $totalAmount;
                $sales->save();
            }
        });
    }

    public function add_batch_code_sale()
    {
        $groupedSales = [];

        foreach ($this->csv_data_sale as $sale) {
            $saleDate = $sale[0];
            $productName = $sale[1];
            $variant = $sale[2];
            $saleQuantity = (int) $sale[3];
            $price = (float) $sale[4];

            $dateKey = $saleDate;

            $inventoryItems = InventoryIn::with('product')
                ->whereHas('product', function ($query) use ($productName, $variant): void {
                    $query->where('name', $this->cleanText($productName))
                        ->where('variant', mb_strtolower(str_replace(' ', '_', mb_trim($variant))));
                })
                ->where('current_stock', '>', 0)
                ->orderBy('expiration_date', 'asc')
                ->get();

            foreach ($inventoryItems as $inventory) {
                if ($saleQuantity <= 0) {
                    break;
                }

                $availableStock = $inventory->current_stock;
                if ($availableStock <= 0) {
                    continue;
                }

                $usedQty = min($saleQuantity, $availableStock);

                $inventory->current_stock -= $usedQty;
                $inventory->save();

                $product = Product::find($inventory->product_id);
                $product->stock -= $usedQty;
                $product->save();

                $groupedSales[$dateKey][] = [
                    'batch_code' => $inventory->batch_code,
                    'product_id' => $inventory->product_id,
                    'quantity' => $usedQty,
                    'price' => $price,
                    'sub_total' => $usedQty * $price,
                ];

                $saleQuantity -= $usedQty;
            }
        }

        return $groupedSales;
    }

    public function cleanText($text)
    {
        $text = str_replace("\u{A0}", ' ', $text);

        return mb_trim(preg_replace('/\s+/', ' ', $text));
    }
}
