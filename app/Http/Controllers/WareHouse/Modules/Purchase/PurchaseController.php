<?php

namespace App\Http\Controllers\WareHouse\Modules\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockQty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::latest()->get();
        $products = Product::latest()->get();
        $warehouse_detail = Branch::where('type', 'WAREHOUSE')->first();

        //generate purchase no
        $carbon = Carbon::now();
        $current_year = $carbon->format('y');
        $next_year = $carbon->addYear()->format('y');
        $prefix = 'LOT_' . $current_year . '-' . $next_year . '_';

//        $last_purchase_product = Stock::withTrashed()->where('reason','PURCHASE')
//            ->orderBy('created_at', 'desc')
//            ->first();
        //$last_purchase_product = DB::select("SELECT * FROM `stocks` where `reason` = 'PURCHASE' ORDER BY `created_at` desc LIMIT 1");


        $last_purchase_product = Stock::withTrashed()->where('is_last_purchase', 'YES')->first();

        //dd($last_purchase_product);
        //is_last_purchase

        if (!empty($last_purchase_product)) {
            //dd("hii");
            $last_purchase_prefix = $last_purchase_product->purchase_no;
            //dd($last_purchase_prefix);
            $num = substr($last_purchase_prefix, 10);
            //dd($num);
            $purchase_no = $prefix . ($num + 1);
        } else {
            $purchase_no = $prefix . '1';
        }
        //generate purchase no

        // dd($purchase_no);

        return view('backend.warehouse.modules.purchase.purchase', compact('branches', 'products', 'warehouse_detail', 'purchase_no'));
    }


    public function getBranchTransferProductDetail($product_code)
    {
        $product = Product::with('brand', 'category')->find($product_code);
        if (!empty($product)) {
            return response()->json($product);
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
        //return response()->json($request->all());
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'from_warehouse_id' => ['required'],
                'purchase_no' => ['nullable', 'unique:stocks'],
                'bill_number' => ['required'],
                'vendor_name' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            //generate purchase no
            $carbon = Carbon::now();
            $current_year = $carbon->format('y');
            $next_year = $carbon->addYear()->format('y');
            $prefix = 'LOT_' . $current_year . '-' . $next_year . '_';
//            $last_purchase_product = Stock::withTrashed()->latest()->first();
            $last_purchase_product = Stock::withTrashed()->where('is_last_purchase', 'YES')->first();
            if (!empty($last_purchase_product)) {
                $last_purchase_prefix = $last_purchase_product->purchase_no;
                $num = substr($last_purchase_prefix, 10);
                $purchase_no = $prefix . ($num + 1);
            } else {
                $purchase_no = $prefix . '1';
            }
            //generate purchase no


            Stock::withTrashed()->where('is_last_purchase', 'YES')->update([
                'is_last_purchase' => null,
            ]);




            $array_stock_ids = [];
            foreach ($request->products as $key => $product) {
                $stock = Stock::create([
                    "product_id" => $product['product_id'],
                    "branch_in" => $request->from_warehouse_id,
                    "qty" => $product['qty'],
                    "date" => Carbon::now(),
                    "status" => 'IN STOCK',
                    "reason" => 'PURCHASE',
                    "purchase_no" => $purchase_no,
                    "type" => 'WAREHOUSE STOCK',
                    "branch_user_in" => Auth::id(),
                    "branch_user_in_date" => Carbon::now(),
                    "approve_status" => 'NOT APPROVED',
                    "bill_number" => $request->bill_number,
                    "vendor_name" => $request->vendor_name,
                    "remarks" => $request->remarks,
                    "is_last_purchase" => "YES",
                ]);

                $array_stock_ids[] = $stock->id;
            }



            $stock = Stock::whereIn('id', $array_stock_ids)->update([
                "approve_status" => 'APPROVED',
            ]);

            if ($stock) {

                foreach ($array_stock_ids as $stock_id) {
                    $stock_details = Stock::where('id', $stock_id)->first();
                    $product_stock_qty = $stock_details->qty;
                    $branch_id = $stock_details->branch_in;
                    $product_id = $stock_details->product_id;

                    $stock_qty = StockQty::where('branch_id', $branch_id)
                        ->where('product_id', $product_id)
                        ->first();

                    if (!empty($stock_qty)) {
                        $old_stock_qty = $stock_qty->qty;

                        StockQty::where('branch_id', $branch_id)
                            ->where('product_id', $product_id)->update([
                                'qty' => ($old_stock_qty + $product_stock_qty),
                            ]);
                    } else {
                        StockQty::create([
                            "branch_id" => $branch_id,
                            "product_id" => $product_id,
                            "qty" => $product_stock_qty,
                        ]);
                    }
                }


                $response = response()->json(['success' => 'Products Purchased Successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in purchasing Product, please try again'], 200);
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
