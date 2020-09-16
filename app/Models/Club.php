<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $table = 'clubs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'name', 'description', 'status', 'image','content'
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

    public function leaders()
    {
        return $this->hasMany('App\Models\LeaderClub','club_id','id');
    }

    public function members()
    {
        return $this->hasMany('App\Models\MemberClub','club_id','id');
    }
    public function suggestions()
    {
        return $this->hasMany('App\Models\SuggestionClub','club_id','id');
    }
}
