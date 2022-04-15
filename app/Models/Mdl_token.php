<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mdl_token extends Model {

    //
    protected $table = 'fm_token';
    public $timestamps = false;
    protected $primaryKey = 'token_id';

}
