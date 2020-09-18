<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

 
class CentersOfInterests extends Model
{

    protected $table = 'centers_of_interests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name'
    ];


}
