<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mdl_category extends Model {

       //
       protected $table = 'fm_category';
       public $timestamps = false;
       protected $primaryKey = 'category_id';

       public function category() {
              return $this->hasMany('App\Models\fm_all_feed', 'category_id', 'category_id');
       }

}
