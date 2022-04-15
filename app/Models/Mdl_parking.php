<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mdl_parking extends Model {

       //
       protected $table = 'fm_parking';
       public $timestamps = false;
       protected $primaryKey = 'parking_id';

       public function image() {
              return $this->hasMany('App\Models\Mdl_parking_image', 'parking_id', 'parking_id');
       }

}
