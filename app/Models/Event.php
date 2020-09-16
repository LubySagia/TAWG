<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id','club_id', 'name', 'description', 'image','content', 'from', 'to', 'number_member', 'status', 'private'
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
    public function club()
    {
        return $this->belongsTo('App\Models\Club','club_id','id')->select('id', 'name');
    }
    public function comment()
    {
        return $this->hasMany('App\Models\EventComment','event_id','id');
    }
}
