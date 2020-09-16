<?php

use Illuminate\Database\Seeder;
use App\Models\EventComment;
class EventCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_comments')->delete();
        factory(EventComment::class, 1000)->create();
    }
}
