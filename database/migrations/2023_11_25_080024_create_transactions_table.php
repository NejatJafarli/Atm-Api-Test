<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            //atm id
            $table->unsignedBigInteger('atm_id');
            //user id
            $table->unsignedBigInteger('user_id');
            //transaction type
            $table->string('type');
            //transaction amount
            $table->integer('amount');
            //transaction status
            $table->boolean('status')->default(1);
            //transaction date
            $table->date('date');
            //transaction time
            $table->time('time');
            //transaction how many banknotes use json like this {"100":1,"200":1,"500":1}
            $table->json('banknotes');
            $table->timestamps();
            $table->foreign('atm_id')->references('id')->on('atms');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
