<?php

namespace App\Http\Controllers\Branch\Modules\Cashbook;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cashbook;
use App\Models\CashbookDetail;
use App\Models\Estimate;
use App\Models\EstimatePaymentList;
use App\Models\Expense;
use App\Models\OpeningBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BranchCashbookController extends Controller
{
    public function createCashbookList()
    {
        $branch_id = Auth::user()->branch_id;

        $branch_detail = Branch::where('id', $branch_id)->get(['id', 'branch_name']);
        return view('backend.branch.modules.cashbook.create-cashbook', compact('branch_detail'));
    }

    public function cashbookList()
    {
        $branch_id = Auth::user()->branch_id;

        $cashbook_data = Cashbook::where('branch_id', $branch_id)->get();
        return view('backend.branch.modules.cashbook.cashbook', compact('cashbook_data'));
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
            $cashbook->statement = 'HO';
            $cashbook->branch_id = $user->branch_id;
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
                $response = response()->json(['success' => 'Transfer Cash To HO Successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in Transferring cash, please try again'], 200);
            }

            return $response;
        }
    }

    public function receiveCashbookList()
    {
        $branch_id = Auth::user()->branch_id;

        $cashbooks = Cashbook::where('branch_id', $branch_id)->where('statement', 'BRANCH')->get();
        return view('backend.branch.modules.cashbook.receive-cashbook', compact('cashbooks'));
    }

    public function receiveCashData($id)
    {
        $output = '';
        $total = 0;
        $cashbook_details = CashbookDetail::where('cashbook_id', $id)->get();
        foreach ($cashbook_details as $data) {
            $output .= '<tr>
                        <td><input class="form-control" type="number" readonly value="' . $data->currency . '"></td>
                        <td class="pt-3">X</td>
                        <td><input class="form-control" type="number" readonly value="' . $data->no_of_notes . '"></td>
                        <td class="pt-3">=</td>
                        <td><input class="form-control" type="number" readonly value="' . $data->amount . '"></td>
                    </tr>';

            $total += $data->amount;
        }
        $output .= '<tr>
                        <td colspan="3">
                            <p class="text-right pt-2"><strong>Total<strong></p>
                        </td>
                        <td class="pt-3">=</td>
                        <td><input class="form-control" type="number" value="' . $total . '" readonly></td>
                    </tr>';

        return $response = response()->json($output);
    }

    //approved 
    public function approveReceiveCash(Request $request)
    {
        $update_cash = Cashbook::where('id', $request->id)->update([
            'status' => 'Approved',
            'action_by' => Auth::user()->id
        ]);
        if ($update_cash) {
            $response = response()->json(['success' => 'Status updated successfully'], 200);
        } else {
            $response = response()->json(['errors_success' => 'Error in updating received, please try again'], 200);
        }
        return $response;
    }

    public function declineReceiveCash(Request $request)
    {
        $update_cash = Cashbook::where('id', $request->id)->update([
            'status' => 'Decline',
            'remark' => $request->remark,
            'action_by' => Auth::user()->id
        ]);
        if ($update_cash) {
            $response = response()->json(['success' => 'Status updated successfully'], 200);
        } else {
            $response = response()->json(['errors_success' => 'Error in updating received, please try again'], 200);
        }
        return $response;
    }

    public function manageOpeningClosing(Request $request)
    {
        $from_date = $request->from_date;
        $current_date = Carbon::now()->format('Y-m-d');
        $last_date = date('Y-m-d', strtotime($current_date . ' +1 day'));

        $previous_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
        $chk_opening_balance = OpeningBalance::where('branch_id', Auth::user()->branch_id)->where('datetime', $previous_date)->get(['closing_balance']);
        if (sizeof($chk_opening_balance) > 0) {

            while ($from_date != $last_date) {

                $date_from = \Carbon\Carbon::parse($from_date)->format('Y-m-d 00:00:00');
                $date_to = \Carbon\Carbon::parse($from_date)->format('Y-m-d 23:59:59');
                $previous_date_record = date('Y-m-d', strtotime($from_date . ' -1 day'));

                $openings = OpeningBalance::where('datetime', $previous_date_record)->where('branch_id', Auth::user()->branch_id)->get();
                if (sizeof($openings) > 0) {
                    $opening_balances = $openings[0]->closing_balance;
                } else {
                    $opening_balances = 0;
                }

                $total_opening = 0;
                $estimate_payments = EstimatePaymentList::whereBetween('created_at', [$date_from, $date_to])->get(['estimate_id', 'paid_in_cash']);

                if (!empty($estimate_payments)) { {
                        foreach ($estimate_payments as $item)

                            $estimate_datas = Estimate::where(
                                'id',
                                $item->estimate_id
                            )->where('branch_id', Auth::user()->branch_id)->get();

                        if (sizeof($estimate_datas) > 0) {
                            if ($item->paid_in_cash > 0) {
                                $total_opening += $item->paid_in_cash;
                            }
                        }
                    }
                }

                $total_opening_balance = $total_opening + $opening_balances;

                $total_expenses = 0;
                $expenses = Expense::where('mode', 'CASH')->whereBetween('created_at', [$date_from, $date_to])->where('branch_id', Auth::user()->branch_id)->get();
                if (!empty($expenses)) {
                    foreach ($expenses as $item) {
                        $total_expenses += $item->amount;
                    }
                }

                $total_closing_balance = $total_opening_balance - $total_expenses;

                $chk_date_present = OpeningBalance::where('branch_id', Auth::user()->branch_id)->where('datetime', $from_date)->get();

                if (sizeof($chk_date_present) > 0) {
                    $set_opening_balance = OpeningBalance::where('branch_id', Auth::user()->branch_id)->where('datetime', $from_date)->update([
                        'opening_balance' => $opening_balances,
                        'closing_balance' => $total_closing_balance,
                    ]);
                } else {
                    // return response()->json('add');
                    $set_opening_balance = new OpeningBalance();
                    $set_opening_balance->datetime = $from_date;
                    $set_opening_balance->branch_id = Auth::user()->branch_id;
                    $set_opening_balance->opening_balance = $opening_balances;
                    $set_opening_balance->closing_balance = $total_closing_balance;
                    $set_opening_balance->save();
                }

                $from_date = date('Y-m-d', strtotime($from_date . ' +1 day'));
            }

            $response = response()->json(['success' => 'Update Opening and Closing Balance'], 200);
        } else {
            $response = response()->json(['errors_success' => 'No Record Of ' . $previous_date . ' Date, Please Request Admin to update Opening Date'], 200);
        }
        return $response;
    }
}
