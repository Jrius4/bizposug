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
            $table->string('code');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->json('products')->nullable();
            $table->json('profit')->nullable();
            $table->json('loss')->nullable();
            $table->string('discount')->default('0');
            $table->string('total')->default('0');
            $table->mediumText('description')->nullable();
            $table->string('subtotal')->default('0');
            $table->string('type_of_transaction')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')
                ->onUpdate('cascade')->onDelete('cascade');
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
