<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstimateProductList extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'estimate_id',
        'product_id',
        'product_type',
        'product_name',
        'product_code',
        'color',
        'size',
        'qty',
        'mrp',
        'amount',
        'is_sale_returned',
        'sale_returned_qty',
    ];



    public function Estimate()
    {
        return $this->hasOne('App\Models\Estimate','id','estimate_id');
    }


    public function Product()
    {
        return $this->hasOne('App\Models\Product','id','product_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }


    public function EstimateProductDeliveryStatus()
    {
        return $this->hasMany('App\Models\EstimateProductDeliveryStatus','estimate_product_list_id','id');
    }



}
