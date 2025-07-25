<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstimateProductDeliveryStatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'estimate_product_list_id',
        'user_id',
        'qty',
        'delivery_status',
        'date_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function ProductList()
    {
        return $this->hasOne('App\Models\EstimateProductList', 'id', 'estimate_product_list_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
