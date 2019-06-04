<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    protected $table="favorites";
    public $primaryKey='f_id';
    public $timestamps=false;
}
