<?php

namespace App\Http\Controllers\Admin\Modules\Estimate;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DuePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin.modules.estimates.due-payment-lists');
    }

    public function getDuePaymentList(Request $request)
    {
        if ($request->ajax()) {
            $data = Estimate::with('EstimateProductLists', 'EstimatePaymentLists')
                ->where('payment_status', 'PAYMENT DUE')
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row->user->branch->branch_name;
                })
                ->addColumn('dues_paid', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimatePaymentLists as $key => $EstimatePaymentList) {
                        $options .= '<li>' . $EstimatePaymentList->total_paid . '</li>';
                    }
                    $options .= '<ul>';
                    return $options;
                })
                ->addColumn('mode', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimatePaymentLists as $key => $EstimatePaymentList) {
                        if ($EstimatePaymentList->paid_in_cash == 0) {
                            $mode = 'Bank';
                        } else if ($EstimatePaymentList->paid_in_bank == 0) {
                            $mode = 'Cash';
                        } else {
                            $mode = 'Cash & Bank';
                        }
                        $options .= '<li>' . $mode . '</li>';
                    }
                    $options .= '<ul>';
                    return $options;
                })
                ->addColumn('date', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimatePaymentLists as $key => $EstimatePaymentList) {
                        $options .= '<li>' . $EstimatePaymentList->date_time . '</li>';
                    }
                    $options .= '<ul>';
                    return $options;
                })
//                ->addColumn('total', function ($row) {
//                    return $row->EstimatePaymentLists->sum('total_paid');
//                })
                ->addColumn('delivery_status', function ($row) {
                    return $row->delivery_status;
                })
                ->rawColumns(['dues_paid', 'mode', 'date'])
                ->make(true);
        }
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
