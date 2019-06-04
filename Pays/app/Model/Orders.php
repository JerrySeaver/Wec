<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table="orders";
    public $primaryKey='Order_id';
    public $timestamps=false;
}
