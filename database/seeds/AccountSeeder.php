<?php

use App\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $acc = new Account();
        $acc->create([
            'name' => 'Main Account',
            'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', 'Main Account'))),
            'balance' => 0,
            'min_balance' => 0,
            'description' => 'Main business Account',
        ]);
    }
}
