<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('amount');
            $table->string('prodgroup');
            $table->string('cost_price');
            $table->string('wholesale_price');
            $table->string('retailsale_price');
            $table->string('supplier');
            $table->string('tax_percentage');
            $table->string('company_name');
            $table->string('name');
            $table->string('barcode');
            $table->string('size');
            $table->string('brand');
            $table->string('qty');
            $table->string('wholeprice')->default(0);
            $table->string('retailprice')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
