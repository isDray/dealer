<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsStock extends Model
{
    //
    protected $table = 'goods_stock';
    protected $fillable = [
        'dealer_id', 
        'goods_id',
        'goods_num',
    ];

}
