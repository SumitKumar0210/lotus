<?php

namespace App\Http\Controllers\Branch\Modules\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use App\Models\EstimateProductList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SaleReturnedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.branch.modules.delivery.sale-returned');
    }


    public function getSaleReturnedList(Request $request)
    {
        if ($request->ajax()) {
            $estimate = Estimate::where('payment_status', 'PAYMENT DONE')
                ->where('branch_id', Auth::user()->branch->id)
                ->latest()
                ->pluck('id');

            $data = EstimateProductList::where('delivery_status', 'DELIVERED')
                ->whereIn('estimate_id', $estimate)
                ->where('is_sale_returned', 'ITEM SALE RETURNED')
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
