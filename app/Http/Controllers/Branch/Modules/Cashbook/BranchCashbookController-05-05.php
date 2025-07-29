<?php

namespace App\Http\Controllers\Branch\Modules\Cashbook;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Cashbook;
use App\Models\CashbookDetail;
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
}
