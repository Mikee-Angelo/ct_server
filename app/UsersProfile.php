<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersProfile extends Model
{
    //
    protected $fillable = ['first_name', 'last_name', 'full_name'];

    protected $hidden = ['id'];

}
