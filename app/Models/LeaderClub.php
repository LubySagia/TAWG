<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderClub extends Model
{
    protected $table = 'leader_club';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'user_id', 'club_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'created_at','updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\user','user_id','id');
    }

    public function club()
    {
        return $this->belongsTo('App\Models\Club','club_id','id');
    }
}
