<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventComment extends Model
{
    protected $table = 'event_comments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'user_id', 'event_id', 'content'
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

    public function event()
    {
        return $this->belongsTo('App\Models\Event','event_id','id');
    }
    public function report()
    {
        return $this->hasMany('App\Models\EventCommentReport','comment_id','id');
    }
}
