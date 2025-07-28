<?php

namespace App\Http\Controllers\Branch\Modules\BranchTransfer;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockQty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransferRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.branch.modules.branch-transfer.transfer-record');
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


    public function getTransferRecordList(Request $request)
    {
        if ($request->ajax()) {

            //$stock = Stock::
            //where('type', 'BRANCH STOCK')
            //->where('status', 'IN STOCK')
            // where('branch_out', Auth::user()->branch_id)
            //->where('is_returned', 'RETURNED')
            // ->get();

            // $stock = Stock::with([
            //         'branchUserOut:id,name', 
            //         'branchUserIn:id,name', 
            //         'Product:id,product_name,product_code,size,category_id',
            //         'Product.category:id,category_name',
            //         'branchReturnUser:id,name',
            //         'fromTwo:id,branch_name', 
            //         'branchTo:id,branch_name'
            //     ])
            //     ->orWhere('branch_out', Auth::user()->branch_id)
            //     ->orWhere('branch_in', Auth::user()->branch_id)
            //     //->where('type', 'BRANCH STOCK')
            //     //->where('status', 'IN STOCK')
            //     ->where('reason', 'BRANCH TRANSFER');
            //     // ->get();

            $branchId = Auth::user()->branch_id;

            $stock = Stock::with([
                    'branchUserOut:id,name', 
                    'Product:id,product_name,product_code,size,category_id',
                    'Product.category:id,category_name',
                    'branchReturnUser:id,name',
                    'fromTwo:id,branch_name', 
                    'branchTo:id,branch_name'
                ])
                ->where('reason', 'BRANCH TRANSFER')
                ->where(function ($q) use ($branchId) {
                    $q->where('branch_out', $branchId)
                    ->orWhere('branch_in', $branchId);
                });


            return DataTables::eloquent($stock)
                ->addIndexColumn()
                ->addColumn('from', function ($row) {
                    return optional($row->fromTwo)->branch_name ?? '';
                })
                ->addColumn('to', function ($row) {
                    return optional($row->branchTo)->branch_name ?? '';
                })

                ->addColumn('branch_transfer_no', function ($row) {
                    return $row->transfer_no ?? '';
                })
                ->addColumn('product_name', function ($row) {
                    return optional($row->Product)->product_name ?? '';
                })
				 ->addColumn('category', function ($row) {
                    return optional($row->Product->category)->category_name ?? '';
                })
                ->addColumn('model_no', function ($row) {
                    return optional($row->Product)->product_code ?? '';
                })
				->addColumn('size', function ($row) {
                    return optional($row->Product)->size ?? '';
                })
                ->addColumn('created_by', function ($row) {
                    return optional($row->branchReturnUser)->name ?? '';
                })
                ->addColumn('qty', function ($row) {
                    return $row->qty ?? '';
                })
                ->addColumn('returned_qty', function ($row) {
                    $actual_transferred_qty = $row->qty;
                    $accepted_qty = $row->accepted_qty;
                    $returned_qty = ($actual_transferred_qty - $accepted_qty);
                    return $returned_qty;
                })

                ->addColumn('created_by', function ($row) {
                    return optional($row->created_by)->name ?? '';
                })
                ->addColumn('accepted_by', function ($row) {
                    return optional($row->branchUserOut)->name ?? '';
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d-F-Y') ?? '';
                })
                ->filterColumn('branch_transfer_no', function ($query, $keyword) {
                    $query->where('transfer_no', 'like', "%{$keyword}%");
                })
                ->filterColumn('from', function ($query, $keyword) {
                    $query->whereHas('fromTwo', function ($q) use ($keyword) {
                        $q->where('branch_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('to', function ($query, $keyword) {
                    $query->whereHas('branchTo', function ($q) use ($keyword) {
                        $q->where('branch_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('product_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('category', function ($query, $keyword) {
                    $query->whereHas('Product.category', function ($q) use ($keyword) { 
                        $q->where('category_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('model_no', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('product_code', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('size', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('size', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('created_by', function ($query, $keyword) {
                    $query->whereHas('branchReturnUser', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('accepted_by', function ($query, $keyword) {
                    $query->whereHas('branchUserOut', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    try {
                        $formatted = date('Y-m-d', strtotime($keyword));
                        $query->whereDate('created_at', $formatted);
                    } catch (\Exception $e) {
                        // Invalid date format — ignore filter
                    }
                })
                ->make(true);
        }
    }
}
