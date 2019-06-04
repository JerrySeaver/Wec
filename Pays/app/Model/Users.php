<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table="users";
    public $primaryKey='id';
    public $timestamps=false;
}
