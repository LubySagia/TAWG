<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventMember extends Model
{
    protected $table = 'event_members';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'user_id', 'event_id', 'check_in','check_in_time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'updated_at' 
    ];

    protected $casts = [
    	'check_in'=>'bool'
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\user','user_id','id');
    }

    public function event()
    {
        return $this->belongsTo('App\Models\Event','event_id','id');
    }
}
