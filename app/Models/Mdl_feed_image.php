<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mdl_feed_image extends Model {

    //
    protected $table = 'fm_feed_image';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function image() {
        return $this->hasMany('App\Models\Mdl_feed_image', 'feed_id', 'feed_id');
    }

}
