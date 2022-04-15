<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {

       use HasFactory,
           Notifiable,
           HasApiTokens;

       /**
        * The attributes that are mass assignable.
        *
        * @var array
        */
       protected $fillable = [
           'name',
           'email',
           'password',
       ];

       /**
        * The attributes that should be hidden for arrays.
        *
        * @var array
        */
       protected $hidden = [
//        'password',
           'remember_token',
       ];

       public function my_images() {
              return $this->hasMany('App\Models\fm_feed_image', 'id', 'user_id');
       }

       /**
        * The attributes that should be cast to native types.
        *
        * @var array
        */
       protected $casts = [
           'email_verified_at' => 'datetime',
       ];

}
