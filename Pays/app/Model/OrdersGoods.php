<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrdersGoods extends Model
{
    protected $table="ordersgoods";
    public $primaryKey='og_id';
    public $timestamps=false;
}

