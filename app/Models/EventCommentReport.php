<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCommentReport extends Model
{
    protected $table = 'event_comment_reports';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'user_id', 'comment_id', 'content'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'updated_at' 
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\user','user_id','id');
    }

    public function comment()
    {
        return $this->belongsTo('App\Models\EventComment','comment_id','id');
    }
}
