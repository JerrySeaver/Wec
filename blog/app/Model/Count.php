<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Count extends Model
{
    protected $table="count";
    public $primaryKey='id';
    public $timestamps=false;
}
