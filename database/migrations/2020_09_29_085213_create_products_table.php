<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('prodgroup_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('barcode')->default('4' . strval(rand(11111111, 99999999)) . "5");
            $table->string('brand')->nullable();
            $table->string('size')->nullable();
            $table->string('category');
            $table->string('company_name')->nullable();
            $table->string('cost_price');
            $table->string('wholesale_price');
            $table->string('retailsale_price');
            $table->string('stock_type')->nullable();
            $table->bigInteger('quantity')->default(0);
            $table->string('tax_percentage')->default(0);
            $table->mediumText('description')->nullable();
            $table->string('avatar')->nullable("/images/store.png");
            $table->timestamps();

            $table->foreign('prodgroup_id')->references('id')->on('prodgroups')
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
        Schema::dropIfExists('products');
    }
}
