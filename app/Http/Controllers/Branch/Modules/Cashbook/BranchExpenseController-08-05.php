<?php

namespace App\Http\Controllers\Branch\Modules\Cashbook;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\OpeningBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BranchExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $expenses = Expense::where('branch_id', $user->branch_id)->get();
        return view('backend.branch.modules.cashbook.expense', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            $expenses->branch_id = $user->branch_id;
            $expenses->amount = $request->amount;
            $expenses->remark = $request->reason;
            $expenses->mode = $request->mode;
            $expenses->transaction_id = $request->transaction_id;
            if ($expenses->save()) {
                $response = response()->json(['success' => 'Expenses Added Successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in adding expenses, please try again'], 200);
            }

            return $response;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expenses = Expense::find($id);
        return response()->json($expenses);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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

            $update_expenses = Expense::where('id', $id)->update([
                'amount' => $request->amount,
                'remark' => $request->reason,
                'mode' => $request->mode,
                'transaction_id' => $request->transaction_id,
            ]);

            if ($update_expenses) {
                $response = response()->json(['success' => 'Expenses Updated successfully'], 200);
            } else {
                $response = response()->json(['errors' => 'Error in updating expenses, please try again'], 200);
            }
            return $response;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
