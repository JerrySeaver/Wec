<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table="notice";
    public $primaryKey='id';
    public $timestamps=false;
}
