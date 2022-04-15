<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mdl_feed extends Model {

    //
    protected $table = 'fm_feed';
    public $timestamps = false;
    protected $primaryKey = 'feed_id';

    public function category() {
        return $this->hasOne('App\Models\Mdl_category', 'category_id', 'category_id');
    }
    public function image() {
        return $this->hasMany('App\Models\Mdl_feed_image', 'feed_id', 'feed_id');
    }

}
