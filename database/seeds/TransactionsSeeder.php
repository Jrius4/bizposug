<?php

use App\Product;
use App\Sale;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = new Transaction();

        $date = new Carbon();

        for ($i = 1; $i < 450; $i++) {


            $created =  date_format(date_modify($date->now()->modify((['-', "+", "+", '-'][$i % 4]) . ($i + [89, 405, 430, 405, 203, 20][$i % 5] . " days")), '- ' . [3450, 4560, 632, 5489, 389, 38910, (720 * rand(12, 100))][rand(0, 6)] . 'hours'), 'Y-m-d H:i:s');

            $products = Product::with('brands', 'sizes', 'prodgroup', 'supplier')->find([rand(1, 150), rand(1, 150), rand(151, 175), rand(176, 185), rand(186, 200), rand(201, 250)]);
            $items = array();
            foreach ($products as $product) {
                array_push($items, array_merge($product->toArray(), ['qty' => rand(1, 150)]));
            }
            $profits = rand(111, 999) . [20000, 1500][rand(0, 1)];
            $losses = rand(111, 999) . [500, 0, 1000][rand(0,  2)];
            $total = rand(111, 999) . [75000, 2800][rand(0, 1)];
            $discount = rand(0, 999) . [550, 150][rand(0, 1)];
            $subtotal = $total - $discount;
            $type_of_transaction = ["Whole Sale", "Retail Sale"][rand(0, 1)];
            DB::table('transactions')->insert([
                'code' =>  '4' . strval(rand(11111111, 99999999)) . "5",
                'products' => json_encode($items, true),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'subtotal' => $subtotal,
                'profit' => $profits,
                'loss' => $losses,
                'type_of_transaction' => $type_of_transaction,
                'created_at' => $created, //2020-10-16 09:56:05
                'updated_at' => $created
            ]);

            DB::table('finstatements')->insert([
                'items' => json_encode($items, true),
                'type' => 'sale',
                'sub_type' =>  $type_of_transaction,
                'amount' => $total,
                'profits' => $profits,
                'losses' => $losses,
                'balance' => 0,
                'updated_at' => $created,
                'created_at' => $created
            ]);


            foreach ($items as $item) {
                DB::table('sales')->insert([
                    'name' => $item['name'],
                    'barcode' => $item['barcode'],
                    'company_name' => $item['company_name'],
                    'cost_price' => $item['cost_price'],
                    'wholesale_price' => $item['wholesale_price'],
                    'retailsale_price' => $item['retailsale_price'],
                    'supplier' => ($item['supplier_id'] != null ? $item['supplier']['name'] : null),
                    'tax_percentage' => $item['tax_percentage'],
                    'qty' => $item['qty'],
                    'brand' => $item['brands'][0]['name'],
                    'size' => $item['sizes'][0]['name'],
                    'prodgroup' => $item['prodgroup']['name'],
                    'amount' => $type_of_transaction == 'Whole Sale' ? $item['qty'] * $item['wholesale_price'] : $item['qty'] * $item['retailsale_price'],
                    'wholeprice' => $type_of_transaction == 'Whole Sale' ? 1 : 0,
                    'retailprice' => $type_of_transaction != 'Whole Sale' ? 1 : 0,
                    'updated_at' => $created,
                    'created_at' => $created
                ]);
            }
        }
    }
}
