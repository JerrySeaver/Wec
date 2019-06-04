<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TheTest extends Model
{
    protected $table="thetest";
    public $primaryKey='id';
    public $timestamps=false;
}
