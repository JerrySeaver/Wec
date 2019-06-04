<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OpenId extends Model
{
    protected $table="openid";
    public $primaryKey='id';
    public $timestamps=false;
}
