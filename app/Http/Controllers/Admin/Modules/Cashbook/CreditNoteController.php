<?php

namespace App\Http\Controllers\Admin\Modules\Cashbook;

use App\Http\Controllers\Controller;
use App\Models\CreditNote;
use Illuminate\Http\Request;

class CreditNoteController extends Controller
{
    public function creditNote()
    {
        $credit_detail = CreditNote::where('status', 'Unused')->get();
        return view('backend.admin.modules.cashbook.creditnote', compact('credit_detail'));
    }
}
