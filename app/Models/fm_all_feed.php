<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fm_all_feed extends Model {

       //
       protected $table = 'fm_all_feed';
       public $timestamps = false;
       protected $primaryKey = 'feed_id';

       public function replies() {
              return $this->hasMany('App\Models\Mdl_comment', 'reply_id', 'comment_id');
       }

       public function comment() {
              return $this->hasOne('App\Models\Mdl_comment', 'feed_id', 'feed_id');
       }

       public function image() {
              return $this->hasMany('App\Models\Mdl_feed_image', 'feed_id', 'feed_id');
       }

}
