<?php

namespace App\Http\Controllers\Branch\Modules;

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function getBranchTransferInList(Request $request)
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
                ->addColumn('model_no', function ($row) {
                    return $row->Product->product_code;
                })
				->addColumn('size', function ($row) {
                    return $row->Product->size;
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
                        // $old_qty = $StockQty->qty;
                        // $returned_qty = ($request->branch_transfer_item_qty - $request->qty_to_accept);
                        // $updated_qty = $old_qty + $returned_qty;

                        // StockQty::where('branch_id', $return_branch_id)
                        //     ->where('product_id', $request->product_id)->update([
                        //         "qty" => $updated_qty,
                        //     ]);
                    } else {

                        StockQty::create([
                            "branch_id" => $return_branch_id,
                            "product_id" => $request->product_id,
                            "qty" => 0,
                            //"qty" => $request->qty_to_accept,
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


       public function postBranchTransferReturn(Request $request)
       {
           //return response()->json($request->all());
           if ($request->ajax()) {
    
               $validator = Validator::make($request->all(), [
                   'branch_transfer_item_id' => ['required'],
                   'qty_to_return' => ['required'],
                   'reason' => ['required'],
               ]);
    
               if ($validator->fails()) {
                   return response()->json([
                       'errors_validation' => $validator->errors()->all(),
                   ], 200);
               }
    
               $accept_qty = $request->branch_transfer_item_qty - $request->qty_to_return;
               $stock = Stock::where('id', $request->branch_transfer_item_id)->update([
                   'accepted_qty' => $accept_qty,
                   'branch_return_user' => Auth::user()->id,
                   'branch_return_date' => Carbon::now(),
                   'type' => 'BRANCH STOCK',
                   'status' => 'IN STOCK',
                   'return_reason' => $request->reason,
                   'is_returned' => 'RETURNED',
               ]);
    
               if ($stock) {
    
    
                   //accepted qty stock qty manage
                   $StockQty2 = StockQty::where('branch_id', Auth::user()->branch_id)
                       ->where('product_id', $request->product_id)
                       ->first();
    
                   $returned_qty = ($request->branch_transfer_item_qty - $request->qty_to_return);
    
                   if (!empty($StockQty2)) {
                       $old_qty2 = $StockQty2->qty;
                       $updated_qty2 = $old_qty2 + $returned_qty;
    
                       StockQty::where('branch_id', Auth::user()->branch_id)
                           ->where('product_id', $request->product_id)->update([
                               "qty" => $updated_qty2,
                           ]);
                   } else {
                       StockQty::create([
                           "branch_id" => Auth::user()->branch_id,
                           "product_id" => $request->product_id,
                           "qty" => $returned_qty,
                       ]);
                   }
                   //accepted qty stock qty manage
    
    
    //                //returned qty back to parent stock
    //                $return_branch_id = Stock::where('id', $request->branch_transfer_item_id)->first();
    //                $return_branch_id = $return_branch_id->branch_out;
    //
    //                $StockQty = StockQty::where('branch_id', $return_branch_id)
    //                    ->where('product_id', $request->product_id)
    //                    ->first();
    //
    //                if (!empty($StockQty)) {
    //                    $old_qty = $StockQty->qty;
    //                    $updated_qty = $old_qty + $request->qty_to_return;
    //
    //                    StockQty::where('branch_id', $return_branch_id)
    //                        ->where('product_id', $request->product_id)->update([
    //                            "qty" => $updated_qty,
    //                        ]);
    //                } else {
    //                    StockQty::create([
    //                        "branch_id" => $return_branch_id,
    //                        "product_id" => $request->product_id,
    //                        "qty" => $request->qty_to_return,
    //                    ]);
    //                }
    //                //returned qty back to parent stock
    
    
                   $response = response()->json(['success' => 'Returned Successfully'], 200);
               } else {
                   $response = response()->json(['errors_success' => 'Error in returning products, please try again'], 200);
               }
    
           }
           return $response;
    
       }


    public function getBranchTransferReturnList(Request $request)
    {
        if ($request->ajax()) {

            //where('branch_return_user', '!=', null)
            //where('branch_return_date', '!=', null)
            $stock = Stock::where('type', 'BRANCH STOCK')
                ->where('status', 'IN STOCK')
                ->where('branch_out', Auth::user()->branch_id)
                ->where('is_returned', 'RETURNED')
                ->get();


            return Datatables::of($stock)
                ->addIndexColumn()
                ->addColumn('branch_transfer_no', function ($row) {
                    return $row->transfer_no;
                })
                ->addColumn('product_name', function ($row) {
                    return $row->Product->product_name;
                })
                ->addColumn('model_no', function ($row) {
                    return $row->Product->product_code;
                })
                ->addColumn('from', function ($row) {
                    return $row->branchTo->branch_name;
                })
                ->addColumn('created_by', function ($row) {
                    return $row->branchReturnUser->name ?? '';
                })
                ->addColumn('qty', function ($row) {

                    $actual_transferred_qty = $row->qty;
                    $accepted_qty = $row->accepted_qty;
                    $returned_qty = ($actual_transferred_qty - $accepted_qty);

                    return $returned_qty;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('reason', function ($row) {
                    return $row->return_reason;
                })
                ->make(true);
        }
    }


    public function getBranchTransferOutList(Request $request)
    {

        if ($request->ajax()) {
            $branch_id = Auth::user()->branch_id;

            $data = Stock::where('type', 'IN TRANSIT')
                ->where('status', 'OUT STOCK')
                ->where('reason', 'BRANCH TRANSFER')
                ->where('branch_out', $branch_id)
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_to', function ($row) {
                    return $row->branchTo->branch_name;
                })
                ->addColumn('created_by', function ($row) {
                    return $row->branchUserOut->name;
                })
                ->addColumn('product_name', function ($row) {
                    return $row->Product->product_name;
                })
                ->addColumn('product_code', function ($row) {
                    return $row->Product->product_code;
                })
                ->addColumn('color', function ($row) {
                    return $row->Product->color_code;
                })
                ->addColumn('size', function ($row) {
                    return $row->Product->size;
                })
                ->addColumn('qty', function ($row) {
                    return $row->qty;
                })
                ->make(true);
        }
    }





    public function getBranchStockListSearch(Request $request)
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
                ->orwhere('product_name_search', 'like', '%' . $id . '%')
                //->orderByRaw("CHARINDEX('$id',product_code_search, 1) DESC, product_code_search ASC")
                ->orderByRaw("IF(product_code_search = '{$id}',2,IF(product_code_search LIKE '{$id}%',1,0)) ASC")
                ->get();


            $product_code = [];
            foreach ($products as $p) {
                $product_code[] = $p->product_code;
            }
            $stocks = Product::with('category')->whereIn('product_code', $product_code)->get();
        }
        return response()->json($stocks);
    }






    public function getBranchStockListBySearch(Request $request)
    {
        if ($request->ajax()) {

            $usersQuery = Stock::query();
            if (!empty($_GET["product_id"])) {

                $branches_id = Branch::get()->pluck('id');
                $usersQuery
                    ->where('status', 'IN STOCK')
                    ->whereIn('branch_in', $branches_id)
                    ->where('product_id', $_GET["product_id"])
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
                    ->addColumn('opening_qty', function ($row) {

                        $branch_id = $row->branch_in;
                        $product_id = $row->product_id;

                        $stock_qty = StockQty::where('branch_id', $branch_id)
                            ->where('product_id', $product_id)
                            ->first();

                        if (!empty($stock_qty)) {
                            $product_starting_date = Carbon::parse($stock_qty->created_at)->startOfDay();
                            $product_previous_day = Carbon::now()->subDays('1')->endOfDay();

                            $stock_qty = StockQty::where('branch_id', $branch_id)
                                ->where('product_id', $product_id)
                                ->whereBetween('created_at', [$product_starting_date, $product_previous_day])
                                ->get();

                            $stock_qty = $stock_qty->sum('qty');
                            return $stock_qty;
                        } else {
                            return 0;
                        }
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
}
