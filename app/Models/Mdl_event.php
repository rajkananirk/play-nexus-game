<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mdl_event extends Model {

    //
    protected $table = 'fm_event';
    public $timestamps = false;
    protected $primaryKey = 'event_id';

    public function image() {
        return $this->hasMany('App\Models\Mdl_event_image', 'event_id', 'event_id');
    }
    public function event() {
        return $this->hasMany('App\Models\Mdl_event', 'event_id', 'event_date');
    }

}
