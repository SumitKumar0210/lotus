<?php

namespace App\Http\Controllers\WareHouse\Modules\BranchTransfer;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockQty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BranchTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::where('id', '!=', Auth::user()->branch_id)->latest()->get();
        $products = Product::latest()->get();
        $warehouse_detail = Branch::where('type', 'WAREHOUSE')->first();
        return view('backend.warehouse.modules.branch-transfer.branch-transfer', compact('branches', 'products', 'warehouse_detail'));
    }


    public function getBranchTransferProductDetail($product_id)
    {
        $product = Product::with('brand', 'category')->find($product_id);
        $StockQty = StockQty::where('branch_id', Auth::user()->branch_id)
            ->where('product_id', $product_id)->first();
        if (!empty($StockQty)) {
            $new_stock_qty = $StockQty->qty;
        } else {
            $new_stock_qty = 0;
        }
        if (!empty($product)) {
            return response()->json(['product' => $product, 'product_stock_qty' => $new_stock_qty]);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'from_warehouse_id' => ['required'],
                'to_branch_id' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            //generate branch_transfer_no
            $carbon = Carbon::now();
            $current_year = $carbon->format('y');
            $next_year = $carbon->addYear()->format('y');
            $prefix = 'TOT_' . $current_year . '-' . $next_year . '_';
            $last_branch_transfer = Stock::withTrashed()->latest()->first();
            if (!empty($last_branch_transfer)) {
                $last_branch_transfer_prefix = $last_branch_transfer->transfer_no;
                $num = substr($last_branch_transfer_prefix, 10);
                $branch_transfer_no = $prefix . ($num + 1);
            } else {
                $branch_transfer_no = $prefix . '1';
            }
            //generate branch_transfer_no


            foreach ($request->products as $key => $product) {
                $stock = Stock::create([
                    "product_id" => $product['product_id'],
                    "branch_in" => $request->to_branch_id,
                    "branch_out" => Auth::user()->branch_id,
                    "qty" => $product['qty'],
                    "date" => Carbon::now(),
                    "status" => 'OUT STOCK',
                    "reason" => 'BRANCH TRANSFER',
                    "transfer_no" => $branch_transfer_no,
                    "type" => 'IN TRANSIT',
                    "branch_user_out" => Auth::id(),
                    "remarks" => $request->remarks,
                ]);
            }

            foreach ($request->products as $key => $product) {

                $StockQty = StockQty::where('branch_id', $request->from_warehouse_id)
                    ->where('product_id', $product['product_id'])
                    ->first();

                $old_qty = $StockQty->qty;

                $transferred_qty = $product['qty'];
                $updated_qty = ($old_qty - $transferred_qty);

                StockQty::where('branch_id', $request->from_warehouse_id)
                    ->where('product_id', $product['product_id'])->update([
                        "qty" => $updated_qty,
                    ]);
            }

            if ($stock) {
                $response = response()->json(['success' => 'Products Transferred To Branch Successfully'], 200);

            } else {
                $response = response()->json(['errors_success' => 'Error in Transferring products, please try again'], 200);

            }

            return $response;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $branch = Branch::find($id);
        if (!empty($branch)) {
            return response()->json($branch);
        }
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
