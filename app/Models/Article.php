<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id','category_id', 'title', 'description', 'status', 'image','content', 'is_hot'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'status','created_at','updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * [get relationshi[]]
     * @return [type] [description]
     */
    public function categories()
    {
        return $this->belongsTo('App\Models\Category','category_id','id');
    }
}
