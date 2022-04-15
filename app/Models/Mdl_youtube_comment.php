<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mdl_youtube_comment extends Model {

    //
    protected $table = 'fm_youtube_comment';
    public $timestamps = false;
    protected $primaryKey = 'youtube_id';

}
