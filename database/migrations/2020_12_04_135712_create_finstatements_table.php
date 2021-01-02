<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinstatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finstatements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('sub_type');
            $table->string('amount');
            $table->string('profits')->default('0');
            $table->string('losses')->default('0');
            $table->string('balance')->default('0');
            $table->json('items')->nullable();
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
        Schema::dropIfExists('finstatements');
    }
}
