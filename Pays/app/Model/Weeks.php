<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Weeks extends Model
{
    protected $table="weeks";
    public $primaryKey='id';
    public $timestamps=false;
}
