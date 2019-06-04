<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $table="login";
    public $primaryKey='id';
    public $timestamps=false;
}
