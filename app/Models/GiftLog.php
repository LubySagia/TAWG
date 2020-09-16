<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftLog extends Model
{
    protected $table = 'gift_log';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id', 'user_id', 'gift_id','number', 'status'
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
    	'status'=>'bool'
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function gift()
    {
        return $this->belongsTo('App\Models\Gift','gift_id','id');
    }
}
