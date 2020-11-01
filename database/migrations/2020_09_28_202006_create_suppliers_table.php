<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->string('biograpy')->nullable();
            $table->string('name')->unique();
            $table->unsignedBigInteger('suppgroup_id')->nullable();
            $table->string('company')->nullable();
            $table->string('address')->nullable();
            $table->string('balance')->nullable();
            $table->string('contact');
            $table->timestamps();

            $table->foreign('suppgroup_id')->references('id')->on('suppgroups')
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
        Schema::dropIfExists('suppliers');
    }
}
