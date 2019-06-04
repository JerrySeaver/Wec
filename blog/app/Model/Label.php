<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $table="label";
    public $primaryKey='tagid';
    public $timestamps=false;
}
