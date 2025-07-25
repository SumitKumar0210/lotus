<?php

namespace App\Http\Controllers\Branch\Modules\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use App\Models\EstimateProductDeliveryStatus;
use App\Models\EstimateProductList;
use App\Models\StockQty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Product;

class DueDeliveryNewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.branch.modules.delivery.due-delivery-new');
    }


    public function getDuesDeliveryListNew(Request $request)
    {

        if ($request->ajax()) {

            $estimate_ids_one = Estimate::where('payment_status', 'PAYMENT DONE')
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->where('delivery_status', 'NOT DELIVERED')
                ->where('branch_id', Auth::user()->branch->id)
                ->pluck('id')
                ->toArray();
            // return response()->json($estimate_ids_one);

            $estimate_ids_two = Estimate::where('payment_status', 'PAYMENT DUE')
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->where('delivery_status', 'NOT DELIVERED')
                ->where('branch_id', Auth::user()->branch->id)
                ->where('is_admin_approved', 'YES')
                ->pluck('id')
                ->toArray();

            //return response()->json($estimate_ids_two);

            $merged_estimate_ids_one_estimate_ids_two = array_merge($estimate_ids_one, $estimate_ids_two);
            $estimate = Estimate::whereIn('id', $merged_estimate_ids_one_estimate_ids_two)
                ->latest()
                ->get();

            return Datatables::of($estimate)
                ->addIndexColumn()
                ->addColumn('estimate_no', function ($row) {
                    return $row->estimate_no;
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->user->branch->branch_name;
                })
                ->addColumn('client_name', function ($row) {
                    return $row->client_name . ',<br>' . $row->client_mobile . ',<br>' . $row->client_address;
                })
                ->addColumn('expected_delivery_date', function ($row) {
                    return $row->expected_delivery_date;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->addColumn('action', function ($row) {
                    return '<nav class="nav noExl">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a class="dropdown-item" href="' . route('dues-delivery-list-new.show', $row->id) . '" traget="_blank">Confirm Delivery</a>
                           </div>
                            <button class="btn ripple  btn-outline-primary btn-rounded btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                })
                ->rawColumns(['action', 'client_name'])
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
        $estimate = Estimate::with('EstimatePaymentLists', 'user.branch')->find($id);

        $paid_in_cash = 0;
        $paid_in_bank = 0;
        $total_paid = 0;
        foreach ($estimate->EstimatePaymentLists as $key => $EstimatePaymentList) {
            $paid_in_cash += $EstimatePaymentList->paid_in_cash;
            $paid_in_bank += $EstimatePaymentList->paid_in_bank;
            $total_paid += $EstimatePaymentList->total_paid;
        }

        return view('backend.branch.modules.delivery.due-delivery-new-conform', compact(
            'estimate',
            'paid_in_cash',
            'paid_in_bank',
            'total_paid'
        ));
    }



    public function postDuesDeliveryListNew(Request $request)
    {

        //dd($request->all());
        $request->validate([
            'estimate_id' => 'required',
            'estimate_product_list_ids' => 'required|array',
            'qty_to_mark_delivers' => 'required|array',
            'product_ids' => 'required|array',
        ]);


        if (sizeof($request->qty_to_mark_delivers) < 0) {
            return redirect()->back()->with('error', 'Please select a product');
        }


        // foreach ($request->product_ids as $product_id) {

        //     $product = Product::where('id', $product_id)->first();
        //     $StockQty = StockQty::where('branch_id', Auth::user()->branch_id)
        //         ->where('product_id', $product_id)
        //         ->first();

        //     if (empty($StockQty)) {
        //         return redirect()->back()->with('error', "$product->product_name is not avaliable in stock");
        //     }
        //     if ($StockQty->qty == 0) {
        //         return redirect()->back()->with('error', "$product->product_name is not avaliable in stock 2");
        //     }
        //     if ($StockQty->qty < 0) {
        //         return redirect()->back()->with('error', "$product->product_name is not avaliable in stock 2");
        //     }
        // }


        $estimate_product_delivery_status_ids = [];
        $estimate_numbers = [];

        foreach ($request->estimate_product_list_ids as $key => $estimate_product_list_id) {


            //check every stock product qty
            $product_id = $request->product_ids[$key];
            $product = Product::where('id', $product_id)->first();
            $StockQty = StockQty::where('branch_id', Auth::user()->branch_id)
                ->where('product_id', $product_id)
                ->first();

            if (empty($StockQty)) {
                return redirect()->back()->with('error', "$product->product_name is not avaliable in stock!");
            }
            //$request->qty_to_mark_delivers

            if ($StockQty->qty == 0) {
                return redirect()->back()->with('error', "$product->product_name is not avaliable in stock!!");
            }
            if ($StockQty->qty < 1) {
                return redirect()->back()->with('error', "$product->product_name is not avaliable in stock!!!");
            }
            //check every stock product qty


            if ($request->qty_to_mark_delivers[$key] > 0) {

                if ($StockQty->qty >= $request->qty_to_mark_delivers[$key]) {

                    $estimate_product_delivery_status =  EstimateProductDeliveryStatus::create([
                        'user_id' => Auth::id(),
                        'estimate_product_list_id' =>  $estimate_product_list_id,
                        'qty' => $request->qty_to_mark_delivers[$key],
                        'delivery_status' => 'ITEM DELIVERED',
                        'date_time' => Carbon::now()->format('Y-m-d H:i:s'),
                    ]);

                    $estimate_product_delivery_status_ids[] = $estimate_product_delivery_status->id;
                    $estimate_data =   Estimate::where('id', $request->estimate_id)->first();
                    $estimate_numbers[] = $estimate_data->estimate_no;


                    $StockQty = StockQty::where('branch_id', Auth::user()->branch_id)
                        ->where('product_id', $request->product_ids[$key])
                        ->first();
                    $old_stock_qty = $StockQty->qty;

                    $new_mark_deliver_qty = $request->qty_to_mark_delivers[$key];
                    $new_stock_qty = ($old_stock_qty - $new_mark_deliver_qty);
                    $stock = StockQty::where('product_id', $request->product_ids[$key])
                        ->where('branch_id', Auth::user()->branch_id)
                        ->update(["qty" => $new_stock_qty,]);



                    //status update code*************************************************
                    $estimate_product_list = EstimateProductList::where('id', $estimate_product_list_id)->first();
                    $total_estimate_qty_required = $estimate_product_list->qty;
                    $estimate_id = $estimate_product_list->estimate_id;
                    $estimate_product_lists = EstimateProductList::where('estimate_id', $estimate_id)->get();

                    $estimate_product_lists_id = [];
                    foreach ($estimate_product_lists as $estimate_product_list) {
                        $estimate_product_lists_id[] = $estimate_product_list->id;
                    }
                    $estimate_product_lists_sum = $estimate_product_lists->sum('qty');


                    $estimate_product_delivery_statuses = EstimateProductDeliveryStatus::whereIn('estimate_product_list_id', $estimate_product_lists_id)->get();
                    $estimate_product_delivery_statuses_sum = $estimate_product_delivery_statuses->sum('qty');
                    if ($estimate_product_lists_sum == $estimate_product_delivery_statuses_sum) {

                        Estimate::where('id', $estimate_id)
                            ->update([
                                'delivery_status' => 'DELIVERED',
                                'delivered_date' => Carbon::now(),
                                'delivered_by' => Auth::user()->id,
                            ]);
                    }

                    //for single estimate_product_list
                    $estimate_product_delivery_statuses2 = EstimateProductDeliveryStatus::where('estimate_product_list_id', $estimate_product_list_id)->get();
                    $estimate_product_delivery_statuses_sum2 = $estimate_product_delivery_statuses2->sum('qty');

                    $estimate_product_list_qty = $estimate_product_list->qty;

                    if ($estimate_product_delivery_statuses_sum2 == $total_estimate_qty_required) {

                        EstimateProductList::where('id', $estimate_product_list_id)
                            ->update([
                                'delivery_status' => 'DELIVERED',
                            ]);
                    }
                    //for single estimate_product_list
                    //status update code*************************************************



                    //status update code delivery_status_ready_product*************************************************
                    $estimate_product_list = EstimateProductList::where('id', $estimate_product_list_id)
                        ->first();
                    if (!empty($estimate_product_list)) {

                        $estimate_id = $estimate_product_list->estimate_id;
                        $estimate_product_lists = EstimateProductList::where('estimate_id', $estimate_id)
                            ->where('product_type', 'READY PRODUCT')
                            ->get();

                        $estimate_product_lists_id = [];
                        foreach ($estimate_product_lists as $estimate_product_list) {
                            $estimate_product_lists_id[] = $estimate_product_list->id;
                        }
                        $estimate_product_lists_sum = $estimate_product_lists->sum('qty');
                        $estimate_product_delivery_statuses = EstimateProductDeliveryStatus::whereIn('estimate_product_list_id', $estimate_product_lists_id)->get();
                        $estimate_product_delivery_statuses_sum = $estimate_product_delivery_statuses->sum('qty');

                        if ($estimate_product_lists_sum == $estimate_product_delivery_statuses_sum) {
                            Estimate::where('id', $estimate_id)
                                ->update([
                                    'delivery_status_ready_product' => 'DELIVERED',
                                ]);
                        }
                    }
                    //status update code delivery_status_ready_product*************************************************


                    //status update code delivery_status_ready_product*************************************************
                    $estimate_product_list = EstimateProductList::where('id', $estimate_product_list_id)
                        ->first();

                    if (!empty($estimate_product_list)) {

                        $estimate_id = $estimate_product_list->estimate_id;
                        $estimate_product_lists = EstimateProductList::where('estimate_id', $estimate_id)
                            ->where('product_type', 'ORDER TO MAKE')
                            ->get();
                        $estimate_product_lists_id = [];
                        foreach ($estimate_product_lists as $estimate_product_list) {
                            $estimate_product_lists_id[] = $estimate_product_list->id;
                        }
                        $estimate_product_lists_sum = $estimate_product_lists->sum('qty');
                        $estimate_product_delivery_statuses = EstimateProductDeliveryStatus::whereIn('estimate_product_list_id', $estimate_product_lists_id)->get();
                        $estimate_product_delivery_statuses_sum = $estimate_product_delivery_statuses->sum('qty');
                        if ($estimate_product_lists_sum == $estimate_product_delivery_statuses_sum) {
                            Estimate::where('id', $estimate_id)
                                ->update([
                                    'delivery_status_order_to_make' => 'DELIVERED',
                                ]);
                        }
                    }
                    //status update code delivery_status_ready_product*************************************************


                }
            }
        }







        //delivery challan print************************************************************************************************

        $array_estimate_numbers = $estimate_numbers;
        if (sizeof($array_estimate_numbers) < 1) {
            return redirect()->back()->with(['error' => 'Stock not found']);
        }


        $allValuesAreTheSame = (count(array_unique($array_estimate_numbers)) === 1);
        if (!$allValuesAreTheSame) {
            return redirect()->back()->with(['error' => 'Please select products from same estimates']);
        }
        $array_estimate_product_delivery_status_ids = array_map('intval', $estimate_product_delivery_status_ids);
        $estimate_product_list_ids = EstimateProductDeliveryStatus::whereIn('id', $array_estimate_product_delivery_status_ids)->get()->pluck('estimate_product_list_id');
        $estimate_product_lists = EstimateProductList::whereIn('id', $estimate_product_list_ids)->get();
        $estimate_id = EstimateProductList::whereIn('id', $estimate_product_list_ids)->take(1)->get()->pluck('estimate_id');
        $estimate_id = $estimate_id[0];
        $estimate = Estimate::find($estimate_id);
        $current_branch = Branch::where('id', Auth::user()->branch_id)->first();
        $estimate_product_list_delivery_status_lists = EstimateProductDeliveryStatus::whereIn('id', $array_estimate_product_delivery_status_ids)->get();
        return view('backend.branch.modules.delivery.delivery-challan-print', compact('estimate', 'current_branch', 'estimate_product_lists', 'estimate_product_list_delivery_status_lists'));
        //delivery challan print************************************************************************************************

        //return redirect()->route('dues-delivery-list-new.index')->with('success', 'Products Mark deliver Successfully');
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
