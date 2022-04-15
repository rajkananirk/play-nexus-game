<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mdl_comment_favourite extends Model {

       //
       protected $table = 'fm_comment_favourite';
       public $timestamps = false;

       public function replies() {
              return $this->hasMany('App\Models\Mdl_comment', 'reply_id', 'comment_id');
       }

}
