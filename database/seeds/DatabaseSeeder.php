<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         //$this->call(UserSeeder::class);
         //$this->call(CategorySeeder::class);
         //$this->call(ArticleSeeder::class);
         //$this->call(ClubSeeder::class);
         //$this->call(EventSeeder::class);
         $this->call(EventCommentSeeder::class);
         $this->call(EventCommentReportSeeder::class);
    }
}
