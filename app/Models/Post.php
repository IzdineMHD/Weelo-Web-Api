<?php

namespace App\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Post extends Model
{

	protected $table = 'posts';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body', 'user_id'
    ];
	
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
