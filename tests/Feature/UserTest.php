<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_register()
    {
        //send request to register endpoint
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'Test@test.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        //assert response status
        $response->assertStatus(201);
    }
    public function test_user_login()
    {
        //create user and send request to login endpoint
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test2@test.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test2@test.com',
            'password' => 'password',
        ]);

        //assert response status
        $response->assertStatus(201);


        //token check
        $response->assertJsonStructure(['token']);
        //check message is correct
        $response->assertJson(['message' => 'Login successful']);

        $return['token'] = $response['token'];
        $return['user'] = $user;

        return $return;
    }

    //logout test
    public function test_user_logout()
    {
        //call test_user_login function
        $this->test_user_login();

        //send request to logout endpoint
        $response = $this->postJson('/api/logout');

        //assert response status
        $response->assertStatus(201);
    }

    //get withdraw test
    public function test_user_get_transaction_withdraw()
    {
        //call test_user_login function
        $return = $this->test_user_login();

        $token = $return['token'];
        //create atm 
        $atm = \App\Models\Atm::create([
            'name' => 'atm1',
            'address' => 'address1',
            'status' => 1,
        ]);
        //create user wallet and set balance 1000
        $wallet = \App\Models\Wallet::create([
            'user_id' => $return['user']->id,
            'status' => 1,
            'balance' => 1000,
        ]);
        // //create banknotes
        \App\Models\Banknote::create(
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
            ]
        );
        // //create banknotes-atms
        $banknotesAtms = \App\Models\BanknotesAtms::create(
            [
                'atm_id' => 1,
                'banknote_id' => 1,
                'quantity' => 10,
            ],
            [
                'atm_id' => 1,
                'banknote_id' => 2,
                'quantity' => 10,
            ],
            [
                'atm_id' => 1,
                'banknote_id' => 3,
                'quantity' => 10,
            ],
            [
                'atm_id' => 1,
                'banknote_id' => 4,
                'quantity' => 10,
            ]
        );
        //send post request to withdrawal endpoint with token
        $response = $this->postJson('/api/transaction/withdrawal', [
            'atm_id' => $atm->id,
            'amount' => 100,
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        //assert response status
        $response->assertStatus(201);
        //assert mmessage

        $return['login'] = $return;
        $return['atm'] = $atm;

        return $return;
    }

    public function test_user_get_transaction_history()
    {
        //call test_user_login function
        $return = $this->test_user_login();

        //send get request to transaction history endpoint with token
        $response = $this->getJson('/api/transaction/history', [
            'Authorization' => 'Bearer ' . $return['token'],
        ]);


        //
        $response->assertStatus(201);
        //assert response status
    }
}
