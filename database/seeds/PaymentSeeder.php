<?php

use App\Payment;
use App\Supplier;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $worker = new Worker();
        $payments = new Payment();
        $suppliers = new Supplier();
        $str = new Str();
        $faker = Faker::create();
        $date = new Carbon();

        for ($i = 0; $i < 25; $i++) {
            $gender = ['female', 'male'][$i % 2];
            $worker->create([
                'name' => $faker->name($gender),
                'contact' => $faker->phoneNumber,
                'address' => $faker->address,
                'salary' => 15000 * rand(15, 20),
                'balance' => 500 * rand(15, 20),
                'gender' => $gender,
                'd_o_b' => $date->now()->modify('-' . rand(18, 35) * rand(1, 12) . ' months')->format('d/M/Y'),
                'role' => ['attendant', 'cleaner', 'manager', 'casher'][rand(0, 3)]
            ]);
        }

        for ($i = 0; $i < 200; $i++) {
            $s = false;
            $su = null;
            $wkr = null;
            $id = rand(1, 25);
            if ($i % 3) {
                $su = $suppliers->find($id);
                $s = true;
            } else {
                $wkr = $worker->find($id);
            }
            $ranAmount = 25000 * rand(50, 500);
            $ranBalance = 1200 * rand(50, 500);
            DB::table('payments')->insert([[
                'serial_no' => Str::uuid(),
                'supplier_id' => ($s ? $id : null),
                'worker_id' => (!$s ? $id : null),
                'received_by' => ($s ? ($su != null ? $su->name : null) : ($wkr != null ? $wkr->name : null)),
                'type_payment' => ['supplier', 'worker'][($s ? 0 : 1)],
                'paid' => $ranAmount,
                'balance' => $ranBalance,
                'reciever' => json_encode([
                    'name' => ($s ? ($su != null ? $su->name : null) : ($wkr != null ? $wkr->name : null)),
                    'contact' => ($s ? ($su != null ? $su->contact : null) : ($wkr != null ? $wkr->contact : null)),
                ], true),
                'created_at' => $date->now()->modify((['-', "+", "+", '-'][$i % 4]) . ($i + [89, 405, 430, 405, 203, 20][$i % 5] . " days")),
                'updated_at' => $date->now()->modify((['+', "+", "+", "+"][$i % 4]) . ($i + [89, 405, 430, 405, 203, 20][$i % 5] . " days"))

            ]]);
            DB::table('finstatements')->insert([
                'items' => json_encode([
                    [
                        'name' => ($s ? ($su != null ? $su->name : null) : ($wkr != null ? $wkr->name : null)),
                        'brand' => null,
                        'size' => null,
                        'group' => null,
                        'amount' => $ranAmount,
                        'balance' => $ranBalance,
                    ]
                ], true),
                'type' => 'expense',
                'sub_type' => ['supplier', 'worker'][($s ? 0 : 1)],
                'amount' => $ranAmount,
                'balance' => $ranBalance,
                'created_at' => $date->now()->modify((['-', "+", "+", '-'][$i % 4]) . ($i + [89, 405, 430, 405, 203, 20][$i % 5] . " days")),
                'updated_at' => $date->now()->modify((['+', "+", "+", "+"][$i % 4]) . ($i + [89, 405, 430, 405, 203, 20][$i % 5] . " days"))
            ]);
        }
    }
}
