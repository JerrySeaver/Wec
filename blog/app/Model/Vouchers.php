<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vouchers extends Model
{
    protected $table="vouchers";
    public $primaryKey='id';
    public $timestamps=false;
}
