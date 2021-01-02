<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinservicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businservices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->json('served')->nullable();
            $table->json('items')->nullable();
            $table->string('service_name');
            $table->string('service_type');
            $table->string('serial_no');
            $table->bigInteger('amount_paid')->default(0);
            $table->bigInteger('item_amount')->default(0);
            $table->bigInteger('cost')->default(0);
            $table->bigInteger('profit')->default(0);
            $table->bigInteger('prev_balance')->default(0);
            $table->bigInteger('amount_agreed')->default(0);
            $table->bigInteger('balance_due')->default(0);
            $table->string('comment')->nullable();
            $table->string('whatz_left')->nullable();
            $table->string('served_by')->nullable();
            $table->string('sys_user');
            $table->json('client_details');
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
        Schema::dropIfExists('businservices');
    }
}
