<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table="role";
    public $primaryKey='id';
    public $timestamps=false;
}
