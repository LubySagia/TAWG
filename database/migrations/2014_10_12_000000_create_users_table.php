<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name',150)->nullable(); //Tên
            $table->string('last_name',150)->nullable(); // Họ
            $table->text('avatar')->nullable();// Ảnh đại diện
            $table->string('email',150)->unique();// Địa Chỉ email
            $table->string('password',200)->nullable();// Mật Khẩu
            $table->string('phone',50)->nullable();// Số Điện Thoại
            $table->string('gender',50)->nullable();// Giới Tính
            $table->datetime('birthday')->nullable();// Ngày Sinh
            $table->string('address',150)->nullable();// Địa Chỉ
            $table->string('job',150)->nullable();// Công Việc
            $table->string('level',150)->nullable();// Trình Độ
            $table->text('hobby')->nullable();// Sở Thích
            $table->text('id_facebook')->nullable();// ID login facebook
            $table->text('access_token_facebook')->nullable();// access token Facebook
            $table->text('id_google')->nullable();// ID Login google
            $table->text('access_token_google')->nullable();// access token google
            $table->boolean('is_admin')->default(false);// Quản trị viên
            $table->boolean('is_block')->default(false);// Khóa Tài Khoản
            $table->text('auth_token')->nullable();// Token JWT
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
