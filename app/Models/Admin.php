<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model {

       //
       protected $table = 'admin';
       public $timestamps = false;
       protected $primaryKey = 'admin_id';

       public function category() {
              return $this->hasMany('App\Models\fm_all_feed', 'category_id', 'category_id');
       }

}
