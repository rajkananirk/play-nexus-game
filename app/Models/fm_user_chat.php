<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fm_user_chat extends Model {

       //
       protected $table = 'fm_user_chat';
       public $timestamps = false;

       public function replies() {
              return $this->hasMany('App\Models\Mdl_comment', 'reply_id', 'comment_id');
       }

}
