<?php

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
                'name' => 'Kazibwe Julius Junior',
                'email' => 'kazibwejuliusjunior@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('admins3cret'), // password
                'remember_token' => Str::random(10),
            ]
        );
    }
}
