<?php

namespace App\Http\Controllers\Admin\Modules\Reports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Estimate;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerHistoryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::latest()->get();
        return view('backend.admin.modules.reports.customer-history-report', compact('branches'));
    }


    public function getCustomerHistoryReportList(Request $request)
    {
        if ($request->ajax()) {

            $usersQuery = Estimate::query();
            if (!empty($_GET["branch_id"])) {
                $usersQuery->where('branch_id', $_GET["branch_id"])
                    ->groupBy(['client_mobile']);
            } else {
                $usersQuery->groupBy(['client_mobile']);
            }
            $data = $usersQuery->latest()->get();


//            $data = Estimate::with('EstimateProductLists', 'EstimatePaymentLists')
//                //->where('delivery_status', 'DELIVERED')
//                ->groupBy(['client_mobile'])
//                ->latest()
//                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('estimate_no', function ($row) {

                    $estimates = Estimate::where('client_mobile', $row->client_mobile)
                        ->get();

                    $options = '<ul>';
                    foreach ($estimates as $key => $estimate) {
                        $options .= '<li>' . $estimate->estimate_no . '</li>';
                    }
                    $options .= '<ul>';
                    return $options;
                })
                ->addColumn('estimate_amount', function ($row) {

                    $estimates = Estimate::where('client_mobile', $row->client_mobile)
                        ->get();

                    $options = '<ul>';
                    foreach ($estimates as $key => $estimate) {
                        $options .= '<li>' . $estimate->grand_total . '</li>';
                    }
                    $options .= '<ul>';
                    return $options;
                })
                ->addColumn('total_paid', function ($row) {
                    return $row->EstimatePaymentLists->sum('total_paid');
                })
                ->addColumn('dues', function ($row) {
                    $estimates = Estimate::where('client_mobile', $row->client_mobile)
                        ->where('payment_status', 'PAYMENT DUE')
                        ->get();
                    $sum_due = $estimates->sum('dues_amount');
                    return $sum_due;
                })
                ->rawColumns(['estimate_no', 'estimate_amount'])
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
