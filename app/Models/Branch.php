<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'branch_name',
        'address',
        'phone',
        'email',
        'print_slug',
        'purchase_permission',
        'product_permission',
    ];

    public function User()
    {
        return $this->hasOne('App\Models\User', 'branch_id', 'id');
    }
}
