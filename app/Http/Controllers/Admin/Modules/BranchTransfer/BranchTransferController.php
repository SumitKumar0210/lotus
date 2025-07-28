<?php

namespace App\Http\Controllers\Admin\Modules\BranchTransfer;

use App\Http\Controllers\Controller;
use App\Models\BranchTransfer;
use App\Models\BranchTransferItems;
use App\Models\Stock;
use App\Models\StockQty;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

class BranchTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin.modules.branch-transfer.branch-transfer');
    }

    public function getInTransitList(Request $request)
    {
        if ($request->ajax()) {
            $stock = Stock::with([
                    'fromTwo:id,branch_name',
                    'branchTo:id,branch_name',
                    'Product:id,product_name,product_code',
                    'branchReturnUser:id,name',
                    'branchUserOut:id,name'
                ])->where('reason', 'BRANCH TRANSFER')->skip(30000)->take(15000)->orderBy('created_at', 'desc');
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
                ->addColumn('model_no', function ($row) {
                    return optional($row->Product)->product_code ?? '';
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
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereDate('created_at', date('Y-m-d', strtotime($keyword)));
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
                ->filterColumn('branch_transfer_no', function ($query, $keyword) {
                    $query->where('transfer_no', 'like', "%{$keyword}%");
                })
                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('product_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('model_no', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('product_code', 'like', "%{$keyword}%");
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
        $stock = Stock::find($id);
        return view('backend.admin.modules.branch-transfer.branch-transfer-edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'qty' => ['required', 'numeric'],
        ]);

        $stock_qty = StockQty::where('branch_id', $request->branch_out)
            ->where('product_id', $request->product_id)
            ->first();


        if ($request->qty > $stock_qty->qty) {
            return redirect()->back()->with(['error' => 'stock not available']);
        }


        //stock
        $stock = Stock::where('id', $id)->first();
        $stock_current_qty = $stock->qty;
        $result = $stock_current_qty - $request->qty;
        //stock


        //stock qty update
        $StockQty = StockQty::where('branch_id', $request->branch_out)
            ->where('product_id', $request->product_id)
            ->first();

        $old_qty = $StockQty->qty;
        $updated_qty = ($old_qty + $result);

        StockQty::where('branch_id', $request->branch_out)
            ->where('product_id', $request->product_id)->update([
                "qty" => $updated_qty,
            ]);
        //stock qty update


        $stock = Stock::where('id', $id)->update(
            [
                'qty' => $request->qty,
            ]
        );

        if ($stock) {
            return redirect()->back()->with(['success' => 'Transfer qty updated successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Error in updating Product, please try again']);
        }
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
