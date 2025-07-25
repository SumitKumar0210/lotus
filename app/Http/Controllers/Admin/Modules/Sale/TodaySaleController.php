<?php

namespace App\Http\Controllers\Admin\Modules\Sale;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Estimate;
use App\Models\EstimateProductList;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

class TodaySaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.admin.modules.sale.sale-list-today');
    }



    public function getSaleListToday(Request $request)
    {
        if ($request->ajax()) {

            $startDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $endDay   = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

            $estimate = Estimate::where('payment_status', 'PAYMENT DONE')
                ->whereBetween('updated_at',array($startDay,$endDay))
                ->latest()
                ->pluck('id');

            $data = EstimateProductList::where('delivery_status', 'DELIVERED')
                ->whereIn('estimate_id', $estimate)
                ->latest()
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('estimate_no', function ($row) {
                    return $row->Estimate->estimate_no;
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->Estimate->user->branch->branch_name;
                })
                ->addColumn('client_name', function ($row) {
                    return $row->Estimate->client_name;
                })
                ->addColumn('client_mobile', function ($row) {
                    return $row->Estimate->client_mobile;
                })
                ->addColumn('client_address', function ($row) {
                    return $row->Estimate->client_address;
                })
                ->addColumn('delivery_date', function ($row) {
                    return $row->delivery_date;
                })
                ->addColumn('product_name', function ($row) {
                    return $row->product_name;
                })
                ->addColumn('product_code', function ($row) {
                    return $row->product_code;
                })
                ->addColumn('color', function ($row) {
                    return $row->color;
                })
                ->addColumn('size', function ($row) {
                    return $row->size;
                })
                ->addColumn('quantity', function ($row) {
                    return $row->qty;
                })
                ->addColumn('sale_returned_date', function ($row) {
                    return $row->updated_at;
                })
                ->addColumn('sale_returned_qty', function ($row) {
                    return $row->sale_returned_qty;
                })
                ->addColumn('action', function ($row) {

                    return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('admin.sale.printSaleToday', $row->estimate_id) . '" target="_blank"><i class="far fa fa-print text-primary"></i> Print</a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }




    public function printSaleToday(Request $request, $id){

        $estimate = Estimate::with('EstimateProductLists', 'EstimatePaymentLists', 'user.branch')->find($id);
        $paid_in_cash = 0;
        $paid_in_bank = 0;
        $total_paid = 0;
        foreach ($estimate->EstimatePaymentLists as $key => $EstimatePaymentList) {
            $paid_in_cash += $EstimatePaymentList->paid_in_cash;
            $paid_in_bank += $EstimatePaymentList->paid_in_bank;
            $total_paid += $EstimatePaymentList->total_paid;
        }

        $current_branch = Branch::where('id',$estimate->branch_id)->first();
        return view('backend.admin.modules.sale.sale-print-today', compact('estimate', 'paid_in_cash', 'paid_in_bank', 'total_paid','current_branch'));

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
