<?php

namespace App\Http\Controllers\Admin\Modules\Reports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Estimate;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $branches = Branch::latest()->get();
        return view('backend.admin.modules.reports.customer-report', compact('branches'));
    }


    public function getCustomerReportList(Request $request)
    {
        if ($request->ajax()) {

            $usersQuery = Estimate::query();
            if (!empty($_GET["branch_id"])) {
                $usersQuery->where('branch_id', $_GET["branch_id"])
                    ->groupBy(['client_mobile']);
            } else {
                $usersQuery->groupBy(['client_mobile']);
            }
            $data = $usersQuery->latest();
            // ->get();

//            $data = Estimate::with('EstimateProductLists', 'EstimatePaymentLists')
//                //->where('delivery_status', 'DELIVERED')
//                ->groupBy(['client_mobile'])
//                ->latest()
//                ->get();

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('estimate_no', function ($row) {
                    $estimates = Estimate::where('client_mobile', $row->client_mobile)
                        ->get();

                    $options = '<ul>';
                    foreach ($estimates as $key => $estimate) {
                        // $options .= '<li>' . $estimate->estimate_no . '</li>';
                        $options .= '<li><a title="Print Sale" href="'.route("admin.sale.printSale", $estimate->id).'" target="_blank">' . $estimate->estimate_no . '</a></li>';
                    }
                    $options .= '<ul>';
                    return $options;
                })
                ->addColumn('estimate_amount', function ($row) {

                    $estimates = Estimate::where('client_mobile', $row->client_mobile)
                        ->get();

                    $options = '<ul>';
                    foreach ($estimates as $key => $estimate) {
                        //$options .= '<li>' . $estimate->grand_total . '</li>';
                        $options .= '<li><a title="Show Sale" href="'.route('estimate.show', $estimate->id).'" target="_blank">' .  $estimate->grand_total . '</a></li>';

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
                // ->filterColumn('estimate_no', function ($query, $keyword) {
                //     $query->whereHas('EstimateProductLists', function ($q) use ($keyword) {
                //         $q->where('estimate_no', 'like', "%{$keyword}%");
                //     });
                // })
                // ->filterColumn('estimate_amount', function ($query, $keyword) {
                //     $query->whereHas('EstimateProductLists', function ($q) use ($keyword) {
                //         $q->where('grand_total', 'like', "%{$keyword}%");
                //     });
                // })
               
                // ->filterColumn('total_paid', function ($query, $keyword) {
                //     $query->whereHas('EstimatePaymentLists', function ($q) use ($keyword) {
                //         $q->where('total_paid', 'like', "%{$keyword}%");
                //     });
                // })
                // ->filterColumn('dues', function ($query, $keyword) {
                //     $query->whereHas('EstimatePaymentLists', function ($q) use ($keyword) {
                //         $q->where('dues_amount', 'like', "%{$keyword}%");
                //     });
                // })
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
