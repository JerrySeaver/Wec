<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    protected $table="coupons";
    public $primaryKey='c_id';
    public $timestamps=false;
}
