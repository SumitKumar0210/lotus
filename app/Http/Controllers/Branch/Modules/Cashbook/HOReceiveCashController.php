<?php

namespace App\Http\Controllers\Branch\Modules\Cashbook;

use App\Http\Controllers\Controller;
use App\Models\Cashbook;
use App\Models\CashbookDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HOReceiveCashController extends Controller
{
    public function receiveCashList()
    {
        $cashbooks = Cashbook::where('statement', 'HO')->get();
        return view('backend.branch.modules.cashbookHO.receivecash', compact('cashbooks'));
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
