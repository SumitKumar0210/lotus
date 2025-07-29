<?php

namespace App\Http\Controllers\Admin\Modules\Cashbook;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function expense()
    {
        $expenses = Expense::get();
        return view('backend.admin.modules.cashbook.expense', compact('expenses'));
    }

    public function addExpense(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'amount' => ['required'],
                'reason' => ['required'],
                'mode' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $user = Auth::user();

            $expenses = new Expense();
            $expenses->user_id = $user->id;
            $expenses->branch_id = 0;
            $expenses->amount = $request->amount;
            $expenses->remark = $request->reason;
            $expenses->mode = $request->mode;
            $expenses->transaction_id = $request->transaction_id;
            $expenses->updated_user_id = Auth::user()->id;

            if ($expenses->save()) {
                $response = response()->json(['success' => 'Expenses Added Successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in adding expenses, please try again'], 200);
            }

            return $response;
        }
    }

    public function editExpense($id)
    {
        $expenses = Expense::find($id);
        $data = [
            'datetime' => date('Y-m-d'),
            'id' => $expenses->id,
            'amount' => $expenses->amount,
            'remark' => $expenses->remark,
            'mode' => $expenses->mode,
            'transaction_id' => $expenses->transaction_id,
        ];
        return response()->json($data);
    }

    public function updateExpense(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'amount' => ['required'],
                'reason' => ['required'],
                'mode' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $update_expenses = Expense::where('id', $request->id)->update([
                'amount' => $request->amount,
                'remark' => $request->reason,
                'mode' => $request->mode,
                'transaction_id' => $request->transaction_id,
                'updated_user_id' => Auth::user()->id,
            ]);

            if ($update_expenses) {
                $response = response()->json(['success' => 'Expenses Updated successfully'], 200);
            } else {
                $response = response()->json(['errors' => 'Error in updating expenses, please try again'], 200);
            }
            return $response;
        }
    }

    public function approveExpense($id)
    {
        $approve = Expense::where('id', $id)->update([
            'status' => 'Approved'
        ]);
        if ($approve) {
            $response = response()->json(['success' => 'Expenses Approved successfully'], 200);
        } else {
            $response = response()->json(['errors' => 'Error in approving expenses, please try again'], 200);
        }
        return $response;
    }
}
