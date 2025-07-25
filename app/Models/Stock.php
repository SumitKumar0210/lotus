<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'branch_out',
        'branch_in',
        'qty',
        'date',
        'type',
        'status',
        'reason',
        'purchase_no',
        'transfer_no',
        'approve_status',
        'remark',
        'accepted_qty',
        'accepted_date',
        'is_returned',
        'return_reason',
        'branch_user_out',
        'branch_user_in',
        'branch_user_in_date',
        'branch_decline_user',
        'branch_decline_date',
        'branch_decline_remark',
        'branch_return_user',
        'branch_return_date',
        'bill_number',
        'vendor_name',
        'remarks',
        'is_last_purchase',
        'comment'
    ];

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function branchInUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'branch_in');
    }

    public function branchUserOut()
    {
        return $this->hasOne('App\Models\User', 'id', 'branch_user_out');
    }

    public function branchTo()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'branch_in');
    }

    public function branchReturnUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'branch_return_user');
    }

    public function from()
    {
        return $this->hasMany('App\Models\User', 'branch_id', 'branch_out');
    }

    public function fromTwo()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'branch_out');
    }

    public function to()
    {
        return $this->hasOne('App\Models\User', 'branch_id', 'branch_out');
    }

    public function created_by()
    {
        return $this->hasOne('App\Models\User', 'id', 'branch_user_in');
    }

    public function branch_transfer_return_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'branch_transfer_return_user');
    }

    public function branch_transfered_return_branch_in()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'branch_transfered_return_branch_in');
    }
}
