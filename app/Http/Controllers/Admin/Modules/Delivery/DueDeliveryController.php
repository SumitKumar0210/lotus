<?php

namespace App\Http\Controllers\Admin\Modules\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use App\Models\EstimateProductDeliveryStatus;
use App\Models\EstimateProductList;
use App\Models\StockQty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DueDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin.modules.delivery.dues-delivery');
    }


    public function getDueDeliveryList(Request $request)
    {

        if ($request->ajax()) {

            $estimate = Estimate::where('payment_status', 'PAYMENT DONE')
                ->where('delivery_status', 'NOT DELIVERED')
                ->where('delivery_status_ready_product', 'NOT DELIVERED')
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->latest()
                ->pluck('id');

            $data = EstimateProductList::where('delivery_status', 'NOT DELIVERED')
                ->whereIn('estimate_id', $estimate)
                ->where('product_type', 'READY PRODUCT')
                ->latest();
                // ->get();

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('estimate_no', function ($row) {
                    return optional($row->Estimate)->estimate_no;
                })
                ->addColumn('branch_name', function ($row) {
                    return optional($row->Estimate->user->branch)->branch_name;
                })
                ->addColumn('customer', function ($row) {
                    return optional($row->Estimate)->client_name;
                })
                ->addColumn('mobile', function ($row) {
                    return optional($row->Estimate)->client_mobile;
                })
                ->addColumn('address', function ($row) {
                    return optional($row->Estimate)->client_address;
                })
                ->addColumn('expected_delivery_date', function ($row) {
                    return optional($row->Estimate)->expected_delivery_date;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->addColumn('qty', function ($row) {
                    return $row->qty;
                })
                ->addColumn('qty_undelivered', function ($row) {
                    $EstimateProductDeliveryStatus = EstimateProductDeliveryStatus::where('estimate_product_list_id', $row->id)->get();
                    $EstimateProductDeliveryStatus_qty = $EstimateProductDeliveryStatus->sum('qty');
                    $new_undelivered_quantity = ($row->qty - $EstimateProductDeliveryStatus_qty ?? 0);
                    return $new_undelivered_quantity;
                })
                ->addColumn('action', function ($row) {
                    $EstimateProductDeliveryStatus = EstimateProductDeliveryStatus::where('estimate_product_list_id', $row->id)->get();
                    $EstimateProductDeliveryStatus_qty = $EstimateProductDeliveryStatus->sum('qty');
                    $new_undelivered_quantity = ($row->qty - $EstimateProductDeliveryStatus_qty);

                    $EstimateProductList = EstimateProductList::where('id', $row->id)->first();

                    $branch_id = $EstimateProductList->estimate->user->branch_id;
                    $StockQty = StockQty::where('branch_id', $branch_id)
                        ->where('product_id', $row->product_id)
                        ->first();
                    $new_stock_qty = $StockQty->qty ?? 0;
                    //need to verify this

                    $estimate_product_delivery_statuses = EstimateProductDeliveryStatus::where('estimate_product_list_id', $row->id)->get();
                    $quantity_already_delivered = $estimate_product_delivery_statuses->sum('qty');


                    return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-product_id="' . $row->product_id . '"  data-id="' . $row->id . '"  data-branch_id="' . $branch_id . '"    class="dropdown-item" href="' . route('due-delivery.show', $row->id) . '"><i class="fe fe-edit text-primary"></i> View</a>
                                <a class="dropdown-item" href="javascript:void(0);"><label class="ckbox"><input type="checkbox" data-product_id="' . $row->product_id . '"   data-branch_id="' . $branch_id . '"  data-product_stock_qty="' . $new_stock_qty . '" data-qty="' . $row->qty . '"  data-id="' . $row->id . '" data-new_undelivered_quantity="' . $new_undelivered_quantity . '"  data-quantity_already_delivered="' . $quantity_already_delivered . '" class="conformDelivery"><span>Confirm</span></label></a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                })
                ->rawColumns(['qty', 'action'])
                ->filterColumn('estimate_no', function ($query, $keyword) {
                        $query->whereHas('Estimate', fn($q) => $q->where('estimate_no', 'like', "%{$keyword}%"));
                    })
                ->filterColumn('branch_name', function ($query, $keyword) {
                    $query->whereHas('Estimate.user.branch', fn($q) => $q->where('branch_name', 'like', "%{$keyword}%"));
                })
                ->filterColumn('customer', function ($query, $keyword) {
                    $query->whereHas('Estimate', fn($q) => $q->where('client_name', 'like', "%{$keyword}%"));
                })
                ->filterColumn('mobile', function ($query, $keyword) {
                    $query->whereHas('Estimate', fn($q) => $q->where('client_mobile', 'like', "%{$keyword}%"));
                })
                ->filterColumn('address', function ($query, $keyword) {
                    $query->whereHas('Estimate', fn($q) => $q->where('client_address', 'like', "%{$keyword}%"));
                })
                ->filterColumn('expected_delivery_date', function ($query, $keyword) {
                        $query->whereHas('Estimate', fn($q) => $q->where('expected_delivery_date', 'like', "%{$keyword}%"));
                    })
                // ->filterColumn('remarks', function ($query, $keyword) {
                //     $query->where('remarks', 'like', "%{$keyword}%");
                // })
                ->filterColumn('qty', function ($query, $keyword) {
                    $query->where('qty', 'like', "%{$keyword}%");
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'product_mark_delivered_qty' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }


            $estimate_product_delivery_status = new EstimateProductDeliveryStatus();
            $estimate_product_delivery_status->estimate_product_list_id = $request->estimate_product_list_id;
            $estimate_product_delivery_status->qty = $request->product_mark_delivered_qty;
            $estimate_product_delivery_status->delivery_status = 'ITEM DELIVERED';
            $estimate_product_delivery_status->date_time = Carbon::now()->format('Y-m-d H:i:s');
            $estimate_product_delivery_status->user_id = Auth::id();


            if ($estimate_product_delivery_status->save()) {

                $StockQty = StockQty::where('branch_id', $request->branch_id)
                    ->where('product_id', $request->product_id)
                    ->first();
                $old_stock_qty = $StockQty->qty;
                $new_mark_deliver_qty = $request->product_mark_delivered_qty;

                $new_stock_qty = ($old_stock_qty - $new_mark_deliver_qty);


                $stock = StockQty::where('product_id', $request->product_id)
                    ->where('branch_id', $request->branch_id)
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
                $estimate_product_list = EstimateProductList::where('id', $request->estimate_product_list_id)->first();
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
                //status update code delivery_status_ready_product*************************************************











                if ($stock) {
                    $response = response()->json(['success' => 'Product Mark deliver Successfully'], 200);

                } else {
                    $response = response()->json(['errors_success' => 'Error in marking deliver Product, please try again'], 200);
                }
            }

            else {
                $response = response()->json(['errors_success' => 'Error in marking delivered, please try again'], 200);
            }
            return $response;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($id)
    {
        $estimate = EstimateProductList::with('Estimate', 'Estimate.EstimatePaymentLists')->find($id);
        return view('backend.admin.modules.delivery.due-delivery-view', compact('estimate'));
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
