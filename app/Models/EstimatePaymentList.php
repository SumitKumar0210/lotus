<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstimatePaymentList extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'estimate_id',
        'date_time',
        'paid_in_cash',
        'paid_in_bank',
        'total_paid',
    ];
}
