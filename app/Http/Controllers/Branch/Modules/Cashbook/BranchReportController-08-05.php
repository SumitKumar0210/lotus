<?php

namespace App\Http\Controllers\Branch\Modules\Cashbook;

use App\Http\Controllers\Controller;
use App\Models\CreditNote;
use App\Models\Estimate;
use App\Models\EstimatePaymentList;
use App\Models\Expense;
use App\Models\OpeningBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchReportController extends Controller
{
    public function report()
    {
        $branch_id = Auth::user()->branch_id;
        return view('backend.branch.modules.cashbook.report', compact('branch_id'));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $date_from = date('Y-m-d', strtotime($request->date_from));
        $date_to = date('Y-m-d', strtotime($request->date_to));
        $total_cash = 0;
        $total_bank = 0;
        $total_neft = 0;
        $total_cheque = 0;
        $other = 0;

        $payment_detail_datas = EstimatePaymentList::whereBetween('created_at', [$date_from, $date_to])->get();
        foreach ($payment_detail_datas as $data) {
            if ($data->mode != '') {
                if ($data->mode == 'CASH') {
                    $total_cash += $data->total_paid;
                } else if ($data->mode == 'NEFT') {
                    $total_neft += $data->total_paid;
                } else if ($data->mode == 'Bank') {
                    $total_cash += $data->total_paid;
                } else if ($data->mode == 'Cheque') {
                    $total_cheque += $data->total_paid;
                } else {
                    $other += $data->total_paid;
                }
            } else if ($data->paid_in_cash != '') {
                $total_cash += $data->total_paid;
            } else if ($data->paid_in_bank != '') {
                $total_bank += $data->total_paid;
            }
        }

        $expenses = Expense::whereBetween('created_at', [$date_from, $date_to])->get();
        foreach ($expenses as $expense) {
            if ($expense->mode == 'CASH') {
                $total_cash += $expense->amount;
            } else if ($expense->mode == 'CHEQUE') {
                $total_bank += $expense->amount;
            } else if ($expense->mode == 'NEFT') {
                $total_neft += $expense->amount;
            } else {
                $other += $expense->amount;
            }
        }

        $output = '';
        while ($date_from <= $date_to) {
            $output .= '<div class="row">
            <div class="col-lg-12">
                <h4>' . date('d-m-Y', strtotime($date_from)) . '</h4>
            </div>
            <div class="col-lg-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6 class="card-title">Dr</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Particular</th>
                                    <th scope="col">Mode</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>';
            $nextdate = date('Y-m-d', strtotime($date_from . "-1 days"));
            $opening_balance = 0;
            $last_closing_data = OpeningBalance::where('branch_id', $user->branch_id)->where('datetime', $nextdate)->get('closing_balance');
            if (sizeof($last_closing_data) > 0) {
                $opening_balance = $last_closing_data[0]->closing_balance;
            } else {
                $opening_balance = 0;
            }

            //total balance
            $balance = OpeningBalance::where('branch_id', $user->branch_id)->where('datetime', $date_from)->get();
            $output .= '<tr>
                                    <td>Opening Balance</td>
                                    <td></td>
                                    <td>' . $opening_balance . '</td>
                                </tr>';

            $payment_details = EstimatePaymentList::where('date_time', $date_from)->get();
            if (sizeof($payment_details) > 0) {
                foreach ($payment_details as $payment) {
                    $estimate_datas = Estimate::where('id', $payment->estimate_id)->where('branch_id', $user->branch_id)->get();
                    $estimate_no = $estimate_datas[0]->estimate_no;
                    $customer = $estimate_datas[0]->client_name;
                    $mode = '';
                    if ($payment->mode != '') {
                        $mode = $payment->mode;
                    } else if ($payment->paid != '') {
                        $mode = 'CASH';
                    } else {
                        $mode = 'BANK';
                    }
                    $output .= '<tr>
                                    <td>' . $payment->id . '/' . $estimate_no . '/' . $customer . '</td>
                                    <td>' . $mode . '</td>
                                    <td>' . $payment . '</td>
                                </tr>';
                }
            }

            $date1 = $date_from . ' 00:00:00';
            $date2 = $date_from . ' 23:23:59';

            $credit_notes = CreditNote::whereBetween('created_at', [$date1, $date2])->get();
            if (sizeof($credit_notes) > 0) {
                foreach ($credit_notes as $note) {
                    $estimate_datas = Estimate::where('id', $note->estimate_id)->where('branch_id', $user->branch_id)->get();
                    $estimate_no = $estimate_datas[0]->estimate_no;
                    $customer = $estimate_datas[0]->client_name;
                    $mode = '';
                    $amount = 0;
                    if ($note->type == 'other_bill') {
                        $amount = $note->amount;
                    }

                    $output .= '<tr>
                                    <td>' . $note->id . '/' . $estimate_no . '/' . $customer . '</td>
                                    <td>' . $mode . '</td>
                                    <td>' . $amount . '</td>
                                </tr>';
                }
            }

            $output .= '<tr>
                                    <td colspan="2" class="text-right">Total</td>
                                    <td>' . $balance[0]->opening_balance . '</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6 class="card-title">Cr</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Reason</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td></td>
                                </tr>';

            $date1 = $date_from . ' 00:00:00';
            $date2 = $date_from . ' 23:23:59';

            $credit_notes = CreditNote::whereBetween('created_at', [$date1, $date2])->where('type', 'refund')->where('amount_type', 'CASH')->get();
            if (sizeof($credit_notes) > 0) {
                foreach ($credit_notes as $note) {
                    $estimate_datas = Estimate::where('id', $note->estimate_id)->where('branch_id', $user->branch_id)->get();
                    $estimate_no = $estimate_datas[0]->estimate_no;
                    $customer = $estimate_datas[0]->client_name;
                    $mode = '';

                    $output .= '<tr>
                                    <td>' . $note->id . '/' . $estimate_no . '/' . $customer . '</td>
                                    <td>' . $note->amount . '</td>
                                </tr>';
                }
            }

            $expenses = Expense::whereBetween('created_at', [$date1, $date2])->get();
            if (sizeof($expenses) > 0) {
                foreach ($expenses as $expense) {
                    $output .= '<tr>
                                    <td>' . $expense->remark . '</td>
                                    <td>' . $expense->amount . '</td>
                                </tr>';
                }
            }
            $output .= '<tr>
                                    <td class="text-right">Total</td>
                                    <td>' . $balance[0]->closing_balance . '</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>';

            $date_from = date('Y-m-d', strtotime($date_from . "+1 days"));
        }

        $success['data'] = $output;
        $success['cash'] = $total_cash;
        $success['bank'] = $total_bank;
        $success['neft'] = $total_neft;
        $success['cheque'] = $total_cheque;
        $success['other'] = $other;

        return response()->json($success);
    }
}
