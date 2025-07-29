<?php

namespace App\Http\Controllers\Branch\Modules\Cashbook;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cashbook;
use App\Models\CashbookDetail;
use App\Models\OpeningBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Estimate;
use App\Models\EstimatePaymentList;
use App\Models\Expense;

class HOCashbokController extends Controller
{
    public function cashbookList()
    {
        $branchList = Branch::get(['id', 'branch_name']);

        $current_date = date('Y-m-d');
        $opening_closing_data = OpeningBalance::where('datetime', $current_date)->get();

        return view('backend.branch.modules.cashbookHO.cashbook', compact('branchList', 'opening_closing_data', 'current_date'));
    }

    public function cashbookBranch($id)
    {
        $branches = Branch::where('id', $id)->pluck('branch_name');
        $branch_id = $id;
        return view('backend.branch.modules.cashbookHO.report', compact('branch_id', 'branches'));
    }

    public function cashbook()
    {
        $cashbooks = Cashbook::where('statement', 'BRANCH')->get();
        return view('backend.branch.modules.cashbookHO.view_cashbook', compact('cashbooks'));
    }

    public function createCashbook()
    {
        $branchList = Branch::get(['id', 'branch_name']);
        return view('backend.branch.modules.cashbookHO.create_cashbook', compact('branchList'));
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

    public function adminCashbookHO()
    {
        return view('backend.branch.modules.cashbookHO.admin_report');
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

    public function adminManageOpeningClosing(Request $request)
    {
        $from_date2 = $request->from_date;
        $current_date = Carbon::now()->format('Y-m-d');
        $last_date2 = date('Y-m-d', strtotime($current_date . ' +1 day'));

        while ($from_date2 != $last_date2) {

            $date_from = \Carbon\Carbon::parse($from_date2)->format('Y-m-d 00:00:00');
            $date_to = \Carbon\Carbon::parse($from_date2)->format('Y-m-d 23:59:59');
            $previous_date_record = date('Y-m-d', strtotime($from_date2 . ' -1 day'));

            $openings = OpeningBalance::where('datetime', $previous_date_record)->where('branch_id', '0')->get();
            if (sizeOf($openings) > 0) {
                $opening_balances_admin = $openings[0]->opening_balance;
            } else {
                $opening_balances_admin = 0;
            }

            $total_amount = 0;
            $cashbook_ho = Cashbook::where('status', 'Approved')->where('statement', "HO")->whereBetween('created_at', [$date_from, $date_to])->get();
            if (!empty($cashbook_ho)) {
                foreach ($cashbook_ho as $item) {
                    $total_amount += $item->total_amount;
                }
            }

            $opening = $total_amount + $opening_balances_admin;

            $total_collect = 0;
            $cashbook_branch = Cashbook::where('status', 'Approved')->where('statement', 'BRANCH')->whereBetween('created_at', [$date_from, $date_to])->get();
            if (!empty($cashbook_branch)) {
                foreach ($cashbook_branch as $item) {
                    $total_collect += $item->total_amount;
                }
            }

            $expenses = Expense::where(
                'mode',
                'CASH'
            )->where('status', 'Approved')->whereBetween('datetime', [$date_from, $date_to])->where('branch_id', '0')->get();
            if (!empty($expenses)) {
                foreach ($expenses as $item) {
                    $total_collect += $item->amount;
                }
            }

            $closing = $opening - $total_collect;

            $chk_date_present = OpeningBalance::where('branch_id', '0')->where('datetime', $from_date2)->get();
            if (sizeof($chk_date_present) > 0) {
                $set_opening_balance = OpeningBalance::where('branch_id', '0')->where('datetime', $from_date2)->update([
                    'opening_balance' => $opening_balances_admin,
                    'closing_balance' => $closing,
                ]);
            } else {
                $set_opening_balance = new OpeningBalance();
                $set_opening_balance->datetime = $from_date2;
                $set_opening_balance->branch_id = '0';
                $set_opening_balance->opening_balance = $opening_balances_admin;
                $set_opening_balance->closing_balance = $closing;
                $set_opening_balance->save();
            }

            //branch
            $branches = Branch::where('id', '!=', '1')->get(['id']);
            if (!empty($branches)) {
                foreach ($branches as $branch) {
                    $openings = OpeningBalance::where('datetime', $previous_date_record)->where('branch_id', $branch->id)->get();
                    if (sizeOf($openings) > 0) {
                        $opening_balances = $openings[0]->closing_balance;
                    } else {
                        $opening_balances = 0;
                    }

                    //opening balance
                    $total_opening = 0;
                    $estimate_payments = EstimatePaymentList::whereBetween('created_at', [$date_from, $date_to])->get();
                    if (!empty($estimate_payments)) {
                        foreach ($estimate_payments as $item) {
                            $estimate_datas = Estimate::where('id', $item->estimate_id)->where('branch_id', $branch->id)->get();
                            if (sizeOf($estimate_datas) > 0) {
                                if ($item->paid_in_cash > 0) {
                                    $total_opening += $item->paid_in_cash;
                                }
                            }
                        }
                    }

                    $opening = $total_opening + $opening_balances;

                    //closing balance
                    $total_expenses = 0;
                    $expenses = Expense::where('mode', 'CASH')->where('status', 'Approved')->whereBetween('datetime', [$date_from, $date_to])->where('branch_id', $branch->id)->get();
                    if (!empty($expenses)) {
                        foreach ($expenses as $item) {
                            $total_expenses += $item->amount;
                        }
                    }
                    $closing = $opening - $total_expenses;

                    $chk_date_present = OpeningBalance::where('branch_id', $branch->id)->where('datetime', $from_date2)->get();

                    if (sizeof($chk_date_present) > 0) {
                        $set_opening_balance = OpeningBalance::where('branch_id', $branch->id)->where('datetime', $from_date2)->update([
                            'opening_balance' => $opening_balances,
                            'closing_balance' => $closing,
                        ]);
                    } else {
                        // return response()->json('add');
                        $set_opening_balance = new OpeningBalance();
                        $set_opening_balance->datetime = $from_date2;
                        $set_opening_balance->branch_id = $branch->id;
                        $set_opening_balance->opening_balance = $opening_balances;
                        $set_opening_balance->closing_balance = $closing;
                        $set_opening_balance->save();
                    }
                }
            }
            $from_date2 = date('Y-m-d', strtotime($from_date2 . ' +1 day'));
        }

        return response()->json(['success' => 'Update Opening and Closing Balance'], 200);
    }
}
