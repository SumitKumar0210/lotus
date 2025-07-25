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
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class DuesDeliveryOTMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.branch.modules.delivery.dues-delivery-otm');
    }


    public function getDuesDeliveryListOTM(Request $request)
    {
        if ($request->ajax()) {
            $estimate = Estimate::where('payment_status', 'PAYMENT DONE')
                ->where('delivery_status', 'NOT DELIVERED')
                ->where('branch_id', Auth::user()->branch->id)
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->latest()
                ->pluck('id');


            $data = EstimateProductList::where('delivery_status', 'NOT DELIVERED')
                ->whereIn('estimate_id', $estimate)
                ->where('product_type', 'ORDER TO MAKE')
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
                ->addColumn('expected_delivery_date', function ($row) {
                    return $row->Estimate->expected_delivery_date;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->Estimate->remarks;
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
                    $estimate_product_delivery_status = EstimateProductDeliveryStatus::where('estimate_product_list_id', $row->id)->get();
                    $estimate_product_delivery_status_qty = $estimate_product_delivery_status->sum('qty');
                    $new_untouched_quantity = ($row->qty - $estimate_product_delivery_status_qty);
                    return $new_untouched_quantity;
                    //return $row->qty;
                })
                ->addColumn('product_type', function ($row) {
                    return $row->product_type;
                })
                ->addColumn('action', function ($row) {

                    $estimate_product_delivery_status = EstimateProductDeliveryStatus::where('estimate_product_list_id', $row->id)->get();
                    $estimate_product_delivery_status_qty = $estimate_product_delivery_status->sum('qty');
                    $new_untouched_quantity = ($row->qty - $estimate_product_delivery_status_qty);

                    $StockQty = StockQty::where('branch_id', Auth::user()->branch_id)
                        ->where('product_id', $row->product_id)
                        ->first();
                    $new_stock_qty = $StockQty->qty ?? 0;
                    //dj need to verify this ?? in future after issues occurred

                    $estimate_product_delivery_statuses = EstimateProductDeliveryStatus::where('estimate_product_list_id',$row->id)->get();
                    $quantity_already_delivered = $estimate_product_delivery_statuses->sum('qty');

                    return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('dues-delivery-list.show', $row->estimate_id) . '"><i class="fe fe-edit text-primary"></i> View</a>
                           <a class="dropdown-item " href="javascript:void(0);"><label class="ckbox"><input type="checkbox" data-product_stock_qty="' . $new_stock_qty . '" data-qty="' . $row->qty . '"  data-id="' . $row->id . '" data-product_id="' . $row->product_id . '"    data-estimate_id="' . $row->estimate_id . '"  data-untouched_qty="' . $new_untouched_quantity . '"  data-quantity_already_delivered="' . $quantity_already_delivered . '"        class="conformDelivery"><span>Confirm</span></label></a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'qty_to_mark_deliver' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $estimate_product_delivey_status = new EstimateProductDeliveryStatus();
            $estimate_product_delivey_status->user_id = Auth::id();
            $estimate_product_delivey_status->estimate_product_list_id = $request->estimate_product_list_id;
            $estimate_product_delivey_status->qty = $request->qty_to_mark_deliver;
            $estimate_product_delivey_status->delivery_status = 'ITEM DELIVERED';
            $estimate_product_delivey_status->date_time = Carbon::now()->format('Y-m-d H:i:s');

            if ($estimate_product_delivey_status->save()) {

                $StockQty = StockQty::where('branch_id', Auth::user()->branch_id)
                    ->where('product_id', $request->product_id)
                    ->first();
                $old_stock_qty = $StockQty->qty;
                $new_mark_deliver_qty = $request->qty_to_mark_deliver;

                $new_stock_qty = ($old_stock_qty - $new_mark_deliver_qty);


                $stock = StockQty::where('product_id', $request->product_id)
                    ->where('branch_id', Auth::user()->branch_id)
                    ->update(["qty" => $new_stock_qty,]);


                //status update code*************************************************
                $estimate_product_list = EstimateProductList::where('id', $request->estimate_product_list_id)->first();
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
                $estimate_product_delivery_statuses2 = EstimateProductDeliveryStatus::where('estimate_product_list_id', $request->estimate_product_list_id)->get();
                $estimate_product_delivery_statuses_sum2 = $estimate_product_delivery_statuses2->sum('qty');
                $estimate_product_list_qty = $estimate_product_list->qty;
                if ($estimate_product_delivery_statuses_sum2 == $estimate_product_list_qty) {
                    EstimateProductList::where('id', $request->estimate_product_list_id)
                        ->update([
                            'delivery_status' => 'DELIVERED',
                        ]);
                }
                //for single estimate_product_list
                //status update code*************************************************



                //status update code delivery_status_ready_product*************************************************
                $estimate_product_list = EstimateProductList::where('id', $request->estimate_product_list_id)
                    ->first();
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
                //status update code delivery_status_ready_product*************************************************


                if ($stock) {
                    $response = response()->json(['success' => 'Product Mark deliver Successfully'], 200);

                } else {
                    $response = response()->json(['errors_success' => 'Error in marking deliver Product, please try again'], 200);
                }
            } else {
                $response = response()->json(['errors_success' => 'Error in marking deliver Product, please try again'], 200);
            }
            return $response;
        }
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
