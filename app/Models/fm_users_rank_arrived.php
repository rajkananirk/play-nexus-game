<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fm_users_rank_arrived extends Model {

       //
       protected $table = 'fm_users_rank_arrived';
       public $timestamps = false;

       public function rank_data() {
              return $this->hasMany('App\Models\fm_users_rank_arrived', 'dates', 'dates');
       }

}
