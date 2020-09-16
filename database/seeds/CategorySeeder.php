<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->delete();
        for ($i=0; $i < 5; $i++) { 
        	 Category::create([
	            'title' => 'Danh mục '.$i,
	            'image' => '',
	            'description'=>'Mô tả '.$i,
	            'order'=> $i
	        ]); 
        }
       
    }
}
