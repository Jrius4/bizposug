<?php

use App\Customer;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create(
            [
                'name' => 'Manager',
                'email' => 'manager@bixpos.com',
                'email_verified_at' => now(),
                'password' => bcrypt('manager1234'), // password
                'remember_token' => Str::random(10),
            ]
        );

        Customer::create([
            'name' => 'customer',
            'contact' => '######',
            'address' => '#######',
            'balance' => 0,
            'gender' => '####',
            'd_o_b' => '####',
        ]);
    }
}
