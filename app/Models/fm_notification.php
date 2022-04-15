<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fm_notification extends Model {

       //
       protected $table = 'fm_notification';
       public $timestamps = false;
       protected $primaryKey = 'notification_id';

       public function datas() {
              return $this->hasMany('App\Models\fm_notification', 'notification_date', 'notification_date');
       }

}
