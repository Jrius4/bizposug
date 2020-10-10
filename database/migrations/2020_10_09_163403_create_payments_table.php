<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial_no')->unique();
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->json('reciever')->nullable();
            $table->json('items')->nullable();
            $table->string('type_payment')->nullable();
            $table->string('paid')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('received_by')->nullable();
            $table->string('balance')->nullable();
            $table->string('issued_by')->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('workers')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('supplier_id')->references('id')->on('suppliers')
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
        Schema::dropIfExists('payments');
    }
}
