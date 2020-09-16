<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGift extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name'); // tên sự kiện
            $table->text('image')->nullable(); // Hình ảnh
            $table->text('description')->nullable(); // Mô tả
            $table->integer('request_point')->default(0); // Mô tả
            $table->integer('quantity')->default(0); // Mô tả
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
        Schema::dropIfExists('gifts');
    }
}
