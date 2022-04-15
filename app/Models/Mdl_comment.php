<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mdl_comment extends Model {

       //
       protected $table = 'fm_comment';
       public $timestamps = false;
       protected $primaryKey = 'comment_id';

       public function replies() {
              return $this->hasMany('App\Models\Mdl_comment', 'comment_parent_id', 'comment_id');
       }

}
