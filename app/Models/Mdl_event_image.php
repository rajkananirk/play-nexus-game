<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mdl_event_image extends Model {

    //
    protected $table = 'fm_event_image';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function image() {
        return $this->hasMany('App\Models\Mdl_event_image', 'event_id', 'event_id');
    }

}
