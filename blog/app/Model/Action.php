<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $table="action";
    public $primaryKey='id';
    public $timestamps=false;
}
