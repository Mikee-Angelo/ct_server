<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Logs extends Model
{
    //
    use Notifiable;
    
    protected $fillable = ['users_id', 'places_id'];

    protected $hidden = ['password', 'uuid', 'qr', 'remember_token', 'email'];

    protected function user(){
        return $this->belongsTo('App\UsersProfile');
    }
}
