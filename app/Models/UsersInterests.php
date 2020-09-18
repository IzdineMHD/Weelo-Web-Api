<?php

namespace App\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

 
class UsersInterests extends Model
{

    protected $table = 'users_interests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', "username", 'interest_id', 'interest_type', 'interest_name'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
