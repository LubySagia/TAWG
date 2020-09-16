<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('club_id')->unsigned();
            $table->foreign('club_id')->references('id')->on('clubs')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->text('name'); // tên sự kiện
            $table->text('image')->nullable(); // Ảnh đại diện
            $table->text('description')->nullable();// mô tả ngắn
            $table->text('content')->nullable();// mô tả chi tiết
            $table->datetime('from')->nullable(); //thời gian bắt đầu sự kiện
            $table->datetime('to')->nullable(); // thời gian kết thúc sự kiện
            $table->integer('number_member')->nullable()->default(0); // số lượng thành viên tham gia
            $table->boolean('status')->nullable()->default(true);// trạng thái 
            $table->boolean('private')->nullable()->default(true);// Sự kiện chỉ cho member trong clb
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
        Schema::dropIfExists('event');
    }
}
