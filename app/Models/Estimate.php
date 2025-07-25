<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'sender_address',
        'sender_phone',
        'sender_email',
        'client_mobile',
        'client_name',
        'client_address',
        'client_email',
        'estimate_no',
        'estimate_date',
        'expected_delivery_date',
        'remarks',
        'sub_total',
        'freight_charge',
        'discount_percent',
        'discount_value',
        'grand_total',
        'dues_amount',
        'user_id',
        'status',
        'estimate_status',
        'delivery_status_ready_product',
        'delivery_status_order_to_make',
        'sale_by',
        'is_admin_approved',
    ];

    public function EstimateProductLists()
    {
        return $this->hasMany('App\Models\EstimateProductList', 'estimate_id', 'id');
    }

    public function EstimatePaymentLists()
    {
        return $this->hasMany('App\Models\EstimatePaymentList', 'estimate_id', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function branch()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'branch_id');
    }
}
