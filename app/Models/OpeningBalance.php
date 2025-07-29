<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpeningBalance extends Model
{
    use HasFactory, SoftDeletes;

    public function BranchDetail()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'branch_id');
    }
}
