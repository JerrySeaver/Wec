<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Historys extends Model
{
    protected $table="historys";
    public $primaryKey='h_id';
    public $timestamps=false;
}
