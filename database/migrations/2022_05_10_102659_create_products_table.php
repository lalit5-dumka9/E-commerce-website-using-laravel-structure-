<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('category')->nullable()->default(0);
            $table->unsignedBigInteger('brand')->nullable()->default(0);
            $table->unsignedBigInteger('cupon')->nullable()->default(0);
            $table->text('details');
            $table->text('features');
            $table->integer('Price')->default(0);
            $table->integer('discount')->default(0);
            $table->unsignedBigInteger('quantity')->default(0);
            $table->string('thumbnail');
            $table->string('images');
            $table->string('slug');
            $table->string('colors')->nullable();
            $table->string('extra')->nullable();
            $table->string('uId')->unique();
            $table->unsignedBigInteger('viewCount')->default(0);
            $table->integer('status');
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
        Schema::dropIfExists('products');
    }
};
