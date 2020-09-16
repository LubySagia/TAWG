<?php

use Illuminate\Database\Seeder;
use App\Models\EventCommentReport;
class EventCommentReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_comment_reports')->delete();
        factory(EventCommentReport::class, 2000)->create();
    }
}
