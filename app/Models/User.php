<?php

namespace App\Models;


use App\Models\Post;
use App\Models\UsersInterests;
use Laravel\Passport\HasApiTokens;  
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{

    use Notifiable, SoftDeletes, HasApiTokens;


    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname', 'email', 'username', 'phone_number', 'password', 'sexe', 'age', 'function', 'location_address', 'avatar', 'active', 'activation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes of deleted at to dates format.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    

    public function posts() 
    {
        return $this->hasMany(Post::class);
    }


    public function interests() 
    {
        return $this->hasMany(UsersInterests::class);
    }


}
