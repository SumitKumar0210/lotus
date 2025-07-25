<?php

namespace App\Http\Controllers\WareHouse\Modules;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockQty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{


    public function getBranchTransferReturnList(Request $request)
    {
        if ($request->ajax()) {

            $data = Stock::where('type', 'BRANCH STOCK')
                ->where('status', 'IN STOCK')
                ->where('reason', 'BRANCH TRANSFER')
                ->where('is_returned', 'RETURNED')
                ->where('branch_return_user', '!=', null)
                ->where('branch_out', Auth::user()->branch_id)
                ->where('branch_transfered_return_user', null)
                ->latest()
                ->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_transfer_no', function ($row) {
                    return 'static';
                })
                ->addColumn('product_name', function ($row) {
                    return $row->Product->product_name;
                })
                ->addColumn('product_code', function ($row) {
                    return $row->Product->product_code;
                })
                ->addColumn('from', function ($row) {
                    return $row->from[0]->Branch->branch_name;
                })
				->addColumn('category', function ($row) {
                    return $row->Product->category->category_name;
                })
				->addColumn('category', function ($row) {
                    return $row->Product;
                })
                ->addColumn('created_by', function ($row) {
                    return $row->branchReturnUser->name;
                })
                ->addColumn('qty', function ($row) {
                    return ($row->qty - $row->accepted_qty);
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('reason', function ($row) {
                    return $row->reason;
                })
                ->addColumn('action', function ($row) {
                    return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '"  data-branch_transfered_return_user="' . Auth::user()->id . '"     data-branch_transfered_return_date="' . Carbon::now() . '"   data-branch_transfered_return_branch_in="' . Auth::user()->branch_id . '" data-product_id="' . $row->product_id . '"     data-return_qty="' . ($row->qty - $row->accepted_qty) . '" class="dropdown-item conformProduct" href="javascript:void(0)"><i class="fe fe-edit text-primary"></i> Conform</a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function getBranchTransferOutList(Request $request)
    {
        if ($request->ajax()) {

            $data = Stock::whereIn('type', ['IN TRANSIT', 'BRANCH STOCK'])
                ->whereIn('status', ['OUT STOCK', 'IN STOCK'])
                ->where('reason', 'BRANCH TRANSFER')
                ->where('branch_out', Auth::user()->branch_id)
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_to', function ($row) {
                    return $row->branchTo->branch_name ?? '';
                })
            	 ->addColumn('created_by', function ($row) {
					//return $row->branchUserOut->name ?? '';
					 return null;
                })
                ->addColumn('product_name', function ($row) {
                    return $row->Product->product_name ?? '';
                })
				->addColumn('category', function ($row) {
                    return null;
					//return $row->Product->category->category_name ?? '';
                })
				->addColumn('product_code', function ($row) {
                    return $row->Product->product_code ?? '';
                })
                ->addColumn('color', function ($row) {
                    return $row->Product->color_code ?? '';
                })
                ->addColumn('size', function ($row) {
                    return $row->Product->size ?? '';
                })
                ->addColumn('qty', function ($row) {
                    return $row->qty ?? '';
                })
                ->make(true);
        }
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                'stock_row_id' => ['required'],
                'branch_transfered_return_user' => ['required'],
                'branch_transfered_return_date' => ['required'],
                'branch_transfered_return_branch_in' => ['required'],
                'product_id' => ['required'],
                'return_qty' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $stock = Stock::where('id', $request->stock_row_id)->update(
                [
                    'branch_transfered_return_user' => $request->branch_transfered_return_user,
                    'branch_transfered_return_date' => $request->branch_transfered_return_date,
                    'branch_transfered_return_branch_in' => $request->branch_transfered_return_branch_in,
                ]
            );
            if ($stock) {

                $StockQty = StockQty::where('branch_id', Auth::user()->branch_id)
                    ->where('product_id', $request->product_id)
                    ->first();

                $old_qty = $StockQty->qty;

                $updated_qty = ($old_qty + $request->return_qty);
                $stock_qty_update = StockQty::where('branch_id', Auth::user()->branch_id)
                    ->where('product_id', $request->product_id)->update([
                        "qty" => $updated_qty,
                    ]);


                $response = response()->json(['success' => 'Product accepted successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in accepting Product, please try again'], 200);
            }
            return $response;
        }
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


    /**
     * Escape special characters for a LIKE query.
     *
     * @param string $value
     * @param string $char
     *
     * @return string
     */

    public function getWarehouseStockListSearch(Request $request)
    {
        //        $stocks = [];
        //        if ($request->has('q')) {
        //            $search = $request->q;
        //            $stocks = Product::select("id", "product_code")
        //                ->where('product_code', 'LIKE', "%$search%")
        //                ->get();
        //        }
        //        return response()->json($stocks);
        $stocks = [];
        if ($request->has('q')) {
            $special_characters = [':', '-', '/', '%', '#', '&', '@', '$', '*', ' ', ')', '(', '!', '^', '-', '+', '_', '=', '{', '}', '[', ']', '', '<', '>' . '?'];
            $id = str_replace($special_characters, '', $request->q);
            $products = DB::table('products')
                ->where('product_code_search', 'like', '%' . $id . '%')
                ->orWhere('product_name_search', 'like', '%' . $id . '%')
                ->get();
            $product_code = [];
            foreach ($products as $p) {
                $product_code[] = $p->product_code;
            }
            $stocks = Product::with('category')->whereIn('product_code', $product_code)->get();
        }
        return response()->json($stocks);
    }


    // public function getWarehouseStockListBySearch(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $usersQuery = Stock::query();
    //         if (!empty($_GET["product_id"])) {

    //             $branch = Branch::where('type','WAREHOUSE')->first();
    //             $branch_id = $branch->id;

    //             $usersQuery
    //                 ->where('reason', 'PURCHASE')
    //                 ->where('branch_in', $branch_id)
    //                 ->where('product_id', $_GET["product_id"])
    //                 ->latest();

    //             $data = $usersQuery->groupBy('product_id')->get();
    //             return Datatables::of($data)
    //                 ->addIndexColumn()
    //                 ->addColumn('product_name', function ($row) {
    //                     return $row->product->product_name;
    //                 })
    //                 ->addColumn('product_code', function ($row) {
    //                     return $row->product->product_code;
    //                 })
    //                 ->addColumn('category', function ($row) {
    //                     return $row->product->category->category_name;
    //                 })
    //                 ->addColumn('color', function ($row) {
    //                     return $row->product->color_code;
    //                 })
    //                 ->addColumn('size', function ($row) {
    //                     return $row->product->size;
    //                 })
    //                 ->addColumn('opening_qty', function ($row) {


    //                     $branch = Branch::where('type','WAREHOUSE')->first();
    //                     $branch_id = $branch->id;
    //                     $product_id = $row->product_id;

    //                     $stock_qty = StockQty::where('branch_id', $branch_id)
    //                         ->where('product_id', $product_id)
    //                         ->first();

    //                     if (!empty($stock_qty)) {
    //                         $product_starting_date = Carbon::parse($stock_qty->created_at)->startOfDay();
    //                         $product_previous_day = Carbon::now()->subDays('1')->endOfDay();


    //                         $stock_qty = StockQty::where('branch_id', $branch_id)
    //                             ->where('product_id', $product_id)
    //                             ->whereBetween('created_at', [$product_starting_date, $product_previous_day])
    //                             ->get();

    //                         $stock_qty = $stock_qty->sum('qty');
    //                         return $stock_qty;
    //                     } else {
    //                         return 0;
    //                     }
    //                 })
    //                 ->addColumn('closing_qty', function ($row) {

    //                     $branch = Branch::where('type','WAREHOUSE')->first();
    //                     $branch_id = $branch->id;
    //                     $product_id = $row->product_id;

    //                     $stock_qty = StockQty::where('branch_id', $branch_id)
    //                         ->where('product_id', $product_id)
    //                         ->first();

    //                     if (!empty($stock_qty)) {
    //                         $stock_qty = $stock_qty->qty;
    //                         return $stock_qty;
    //                     } else {
    //                         return 0;
    //                     }
    //                 })
    //                 ->make(true);
    //         }
    //     }
    // }



     public function getWarehouseStockListBySearch(Request $request)
    {
        if ($request->ajax()) {

                if (!empty($_GET["product_id"])) {


                //check zero stock data first so we will eliminate zero stock data
                $branches_id = Branch::get()->pluck('id');
                $usersQuery2 = Stock::query();
                $usersQuery2->where('status', 'IN STOCK')
                    ->whereIn('branch_in', $branches_id)
                    ->where('product_id', $_GET["product_id"])
                    ->latest();
                $data1 = $usersQuery2->get();


                $stocks_id = [];

                foreach ($data1 as $row) {

                    $stock_qtys = StockQty::where('product_id', $row->product_id)
                        ->get();
                    if (sizeof($stock_qtys) > 0) {

                        foreach ($stock_qtys as $q) {
                            if ($q->qty > 0) {
                                $stocks_id[] = $row->id;
                            }
                        }
                    }
                }
                //check zero stock data first so we will eliminate zero stock data


                $usersQuery = Stock::query();
                $usersQuery
                    ->where('status', 'IN STOCK')
                    ->whereIn('branch_in', $branches_id)
                    ->where('product_id', $_GET["product_id"])
                    ->whereIn('id', $stocks_id)
                    ->latest();

                $data = $usersQuery->groupBy('branch_in')->get();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('branch_name', function ($row) {
                        return $row->branchTo->branch_name;
                    })
                    ->addColumn('brand_name', function ($row) {
                        return $row->product->brand->brand_name;
                    })
                    ->addColumn('product_name', function ($row) {
                        return $row->product->product_name;
                    })
                    ->addColumn('product_code', function ($row) {
                        return $row->product->product_code;
                    })
                    ->addColumn('category', function ($row) {
                        return $row->product->category->category_name;
                    })
                    ->addColumn('color', function ($row) {
                        return $row->product->color_code;
                    })
                    ->addColumn('size', function ($row) {
                        return $row->product->size;
                    })

                    ->addColumn('closing_qty', function ($row) {

                        $branch_id = $row->branch_in;
                        $product_id = $row->product_id;

                        $stock_qty = StockQty::where('branch_id', $branch_id)
                            ->where('product_id', $product_id)
                            ->first();

                        if (!empty($stock_qty)) {
                            $stock_qty = $stock_qty->qty;
                            return $stock_qty;
                        } else {
                            return 0;
                        }
                    })
                    ->make(true);
            }
         
        }
    }
    //tab three branch transfer to warehouse*************************************************************************
    public function getWarehouseTransferInList(Request $request)
    {

        if ($request->ajax()) {
            $branch_id = Auth::user()->branch_id;

            $data = Stock::where('type', 'IN TRANSIT')
                ->where('status', 'OUT STOCK')
                ->where('reason', 'BRANCH TRANSFER')
                ->where('branch_in', $branch_id)
                ->where('branch_return_user', null)
                ->where('branch_return_date', null)
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_transfer_no', function ($row) {
                    return $row->transfer_no;
                })
                ->addColumn('product_name', function ($row) {
                    return $row->Product->product_name;
                })
				->addColumn('category', function ($row) {
                    return $row->Product->category->category_name;
                })
				->addColumn('size', function ($row) {
                    return $row->Product->size;
                })
                ->addColumn('model_no', function ($row) {
                    return $row->Product->product_code;
                })
                ->addColumn('from', function ($row) {
                    return $row->fromTwo->branch_name;
                })
                ->addColumn('created_by', function ($row) {
                    return $row->branchUserOut->name ?? '';
                })
                ->addColumn('qty_received', function ($row) {
                    return $row->qty;
                })
                ->addColumn('status', function ($row) {
                    return $row->reason;
                })
                ->addColumn('action', function ($row) {
                    return '<nav class="nav">
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        <a class="dropdown-item " href="javascript:void(0);"><label class="ckbox"><input type="checkbox" data-qty="' . $row->qty . '"  data-id="' . $row->id . '"  data-product_id="' . $row->product_id . '"  class="acceptProduct"><span>Confirm</span></label></a>
                        <!--<a data-qty="' . $row->qty . '"  data-id="' . $row->id . '"  data-product_id="' . $row->product_id . '"  class="dropdown-item returnProduct" href="javascript:void(0)"><i class="fas fa-undo-alt"></i><span style="margin-left: 10px;"> Return </span></a>-->
                    </div>
                    <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                </nav>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function postWarehouseTransferInList(Request $request)
    {

        //return response()->json($request->all());
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'branch_transfer_item_id' => ['required'],
                'qty_to_accept' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            if ($request->branch_transfer_item_qty == $request->qty_to_accept) {
                $is_returned = 'NOT RETURNED';
            } else {
                $is_returned = 'RETURNED';
            }


            $stock = Stock::where('id', $request->branch_transfer_item_id)->update([
                'type' => 'BRANCH STOCK',
                'status' => 'IN STOCK',
                'accepted_qty' => $request->qty_to_accept,
                'accepted_date' => Carbon::now(),
                'branch_user_in' => Auth::user()->id,
                'is_returned' => $is_returned,
                'branch_return_user' => Auth::user()->id,
                'return_reason' => $request->return_reason,
            ]);

            if ($stock) {


                //accepted qty stock qty manage
                $StockQty2 = StockQty::where('branch_id', Auth::user()->branch_id)
                    ->where('product_id', $request->product_id)
                    ->first();

                if (!empty($StockQty2)) {
                    $old_qty2 = $StockQty2->qty;
                    $updated_qty2 = $old_qty2 + $request->qty_to_accept;

                    StockQty::where('branch_id', Auth::user()->branch_id)
                        ->where('product_id', $request->product_id)->update([
                            "qty" => $updated_qty2,
                        ]);
                } else {
                    StockQty::create([
                        "branch_id" => Auth::user()->branch_id,
                        "product_id" => $request->product_id,
                        "qty" => $request->qty_to_accept,
                    ]);
                }
                //accepted qty stock qty manage


                if ($is_returned == 'RETURNED') {
                    $return_branch_id = Stock::where('id', $request->branch_transfer_item_id)->first();
                    $return_branch_id = $return_branch_id->branch_out;

                    $StockQty = StockQty::where('branch_id', $return_branch_id)
                        ->where('product_id', $request->product_id)
                        ->first();

                    if (!empty($StockQty)) {
                        $old_qty = $StockQty->qty;
                        $returned_qty = ($request->branch_transfer_item_qty - $request->qty_to_accept);
                        $updated_qty = $old_qty + $returned_qty;

                        StockQty::where('branch_id', $return_branch_id)
                            ->where('product_id', $request->product_id)->update([
                                "qty" => $updated_qty,
                            ]);
                    } else {

                        StockQty::create([
                            "branch_id" => $return_branch_id,
                            "product_id" => $request->product_id,
                            "qty" => $request->qty_to_accept,
                        ]);
                    }
                }


                $response = response()->json(['success' => 'Branch Transfer Accepted Successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in accepting products, please try again'], 200);
            }


            return $response;
        }
    }
    //tab three branch transfer to warehouse*************************************************************************
}
