<?php

namespace App\Http\Controllers\WareHouse\Modules\BranchTransfer;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Yajra\DataTables\DataTables;

class TransferRecordWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.warehouse.modules.branch-transfer.transfer-record-warehouse');
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


    public function getTransferRecordWarehouseList(Request $request)
    {
		
        if ($request->ajax()) {

            //$stock = Stock::
            //where('type', 'BRANCH STOCK')
            //->where('status', 'IN STOCK')
            // where('branch_out', Auth::user()->branch_id)
            //->where('is_returned', 'RETURNED')
            // ->get();

            $stock = Stock::orWhere('branch_out', Auth::user()->branch_id)
                ->orWhere('branch_in', Auth::user()->branch_id)
                //->where('type', 'BRANCH STOCK')
                //->where('status', 'IN STOCK')
                ->where('reason', 'BRANCH TRANSFER')
                ->orderBy('id', 'DESC');
                //->get();



            return Datatables::of($stock)
                ->addIndexColumn()
                ->addColumn('from', function ($row) {
                   return $row->fromTwo->branch_name ?? '';
                })
                ->addColumn('to', function ($row) {
                  return $row->branchTo->branch_name ?? '';
                })
                ->addColumn('branch_transfer_no', function ($row) {
                  return $row->transfer_no ?? '';
                })
                ->addColumn('product_name', function ($row) {
                 return $row->Product->product_name ?? '';
                })
				->addColumn('category', function ($row) {
                 return $row->Product->category->category_name ?? '';
                })
                ->addColumn('model_no', function ($row) {
                   return $row->Product->product_code ?? '';
                })
				 ->addColumn('size', function ($row) {
                   return $row->Product->size ?? '';
                })
                ->addColumn('created_by', function ($row) {
                   return $row->branchReturnUser->name ?? '';
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
                   return $row->created_by->name ?? '';
                })
                ->addColumn('accepted_by', function ($row) {
				    return 0;
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d-F-Y') ?? '';
                })
                ->make(true);
        }
    }
}
