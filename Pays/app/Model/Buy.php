<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Buy extends Model
{
    protected $table="buy";
    public $primaryKey='buy_id';
    public $timestamps=false;
}
