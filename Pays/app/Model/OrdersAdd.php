<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrdersAdd extends Model
{
    protected $table="ordersadd";
    public $primaryKey='oa_id';
    public $timestamps=false;
}
