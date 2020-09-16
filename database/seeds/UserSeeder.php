<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert([
        	'id'=>1,
            'first_name' => 'Admin',
            'last_name'=>'TAWG',
            'email'=>'admin@admin.com',
            'password'=>bcrypt('123456789'),
            'is_admin'=>true,
            'created_at'=>date("Y-m-d H:i:s",time())
          ]);
        DB::table('users')->insert([
        	'id'=>2,
            'first_name' => 'Thành Viên',
            'last_name'=>'Thứ Nhất',
            'email'=>'tv1@tv1.com',
            'password'=>bcrypt('123456789'),
            'created_at'=>date("Y-m-d H:i:s",time())
          ]);
    }
}
