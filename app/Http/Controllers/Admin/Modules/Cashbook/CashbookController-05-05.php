<?php

namespace App\Http\Controllers\Admin\Modules\Cashbook;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cashbook;
use App\Models\CashbookDetail;
use App\Models\OpeningBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CashbookController extends Controller
{
    public function cashbookList()
    {
        $branchList = Branch::get(['id', 'branch_name']);

        $current_date = date('Y-m-d');
        $opening_closing_data = OpeningBalance::where('datetime', $current_date)->get();

        return view('backend.admin.modules.cashbook.cashbook', compact('branchList', 'opening_closing_data', 'current_date'));
    }

    public function cashbookBranch($id)
    {
        $branches = Branch::where('id', $id)->pluck('branch_name');
        $branch_id = $id;
        return view('backend.admin.modules.cashbook.report', compact('branch_id', 'branches'));
    }

    public function cashbook()
    {
        $cashbooks = Cashbook::where('statement', 'BRANCH')->get();
        return view('backend.admin.modules.cashbook.view_cashbook', compact('cashbooks'));
    }

    public function createCashbook()
    {
        $branchList = Branch::get(['id', 'branch_name']);
        return view('backend.admin.modules.cashbook.create_cashbook', compact('branchList'));
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'amount' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $user = Auth::user();

            $cashbook = new Cashbook();
            $cashbook->statement = 'BRANCH';
            $cashbook->branch_id = $request->branch;
            $cashbook->total_amount = $request->amount;
            $cashbook->requested_by = $user->id;
            if ($cashbook->save()) {

                $count_notes = 0;
                $last_id = $cashbook->id;
                $currency_id[] = $request->currency;
                $count = 0;
                foreach ($currency_id as $pt) {
                    $count += count($pt);
                }

                $note[] = $request->no_of_note;
                $totals[] = $request->total;

                for ($z = 0; $z < count($currency_id); $z++) {
                    for ($i = 0; $i < $count; $i++) {
                        $currency_data = $currency_id[$z][$i];
                        $note_data = $note[$z][$i];
                        $total_data = $totals[$z][$i];

                        $count_notes += $note_data;

                        if ($total_data > 0) {
                            $cashbook_detail = new CashbookDetail();
                            $cashbook_detail->cashbook_id = $last_id;
                            $cashbook_detail->currency = $currency_data;
                            $cashbook_detail->no_of_notes = $note_data;
                            $cashbook_detail->amount = $total_data;
                            $cashbook_detail->save();
                        }
                    }
                }

                $update_cashbook = Cashbook::where('id', $last_id)->update([
                    'no_of_notes' => $count_notes,
                ]);
            }
            if ($cashbook) {
                $response = response()->json(['success' => 'Transfer Cash To Branch Successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in Transferring cash, please try again'], 200);
            }

            return $response;
        }
    }

    public function adminCashbook()
    {
        return view('backend.admin.modules.cashbook.admin_report');
    }

    public function updateOpeningBalance(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'branch' => ['required'],
                'opening_balance' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $chk_branch = OpeningBalance::where('datetime', date('Y-m-d'))->where('branch_id', $request->branch)->get();
            if (sizeof($chk_branch) > 0) {
                $response = response()->json(['errors_success' => 'You Already added the Opening Balance of this Branch'], 200);
            } else {
                $insert_opening_balance = new OpeningBalance();
                $insert_opening_balance->branch_id = $request->branch;
                $insert_opening_balance->datetime = date('Y-m-d');
                $insert_opening_balance->opening_balance = $request->opening_balance;
                $insert_opening_balance->closing_balance = $request->opening_balance;
                $insert_opening_balance->save();
                if ($insert_opening_balance) {
                    $response = response()->json(['success' => 'Opening Balance Added Successfully'], 200);
                } else {
                    $response = response()->json(['errors_success' => 'Error in Adding Opening Balance, please try again'], 200);
                }
            }

            return $response;
        }
    }
}
