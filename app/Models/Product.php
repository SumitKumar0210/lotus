<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'category_id',
        'product_name',
        'product_code',
        'color_code',
        'size',
        'maximum_retail_price',
        'minimum_stock_quantity',
        'description',
        'product_type',
        'status',
        'product_code_search',
        'product_name_search',
    ];

    public function brand()
    {
        return $this->hasOne('App\Models\Brand','id','brand_id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }


    public function category_details()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }

    public function stockqty_detail()
    {
        return $this->hasOne('App\Models\StockQty', 'product_id', 'id');
    }
}
