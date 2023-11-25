<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        DB::table('users')->insert([
            'name' => 'Normal User',
            'email' => 'user@user.com',
            'password' => bcrypt('user'),
            'role' => 'user',
        ]);

        //create user wallets
        DB::table('wallets')->insert([
            'user_id' => 1,
            'status' => 1,
            'balance' => 1000,
        ]);
        DB::table('wallets')->insert([
            'user_id' => 2,
            'status' => 1,
            'balance' => 120,
        ]);

        DB::table('atms')->insert([
            'name' => 'atm1',
            'address' => 'address1',
            'status' => 1,
        ]);
        DB::table('atms')->insert([
            'name' => 'atm2',
            'address' => 'address2',
            'status' => 1,
        ]);

        //create banknotes
        DB::table('banknotes')->insert([
            [
                'value' => 100,
                'prefix' => 'AZN',
            ],
            [
                'value' => 50,
                'prefix' => 'AZN',
            ],
            [
                'value' => 20,
                'prefix' => 'AZN',
            ],
            [
                'value' => 10,
                'prefix' => 'AZN',
            ],
            [
                'value' => 5,
                'prefix' => 'AZN',
            ],
            [
                'value' => 1,
                'prefix' => 'AZN',
            ]
        ]);

        // //fill banknotes-atms
        DB::table('banknotesatms')->insert(
            [[
                'atm_id' => 1,
                'banknote_id' => 1,
                'quantity' => 10,
            ], [
                'atm_id' => 1,
                'banknote_id' => 2,
                'quantity' => 10,
            ], [
                'atm_id' => 1,
                'banknote_id' => 3,
                'quantity' => 10,
            ], [
                'atm_id' => 1,
                'banknote_id' => 4,
                'quantity' => 10,
            ], [
                'atm_id' => 1,
                'banknote_id' => 5,
                'quantity' => 10,
            ], [
                'atm_id' => 1,
                'banknote_id' => 6,
                'quantity' => 10,
            ]]
        );

        //atm 2 have a 100 banknote 10 quantity
        DB::table('banknotesatms')->insert([
            'atm_id' => 2,
            'banknote_id' => 1,
            'quantity' => 10,
        ]);
    }
}
