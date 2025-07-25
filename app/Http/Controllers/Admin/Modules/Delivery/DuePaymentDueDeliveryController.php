<?php

namespace App\Http\Controllers\Admin\Modules\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use App\Models\EstimateProductDeliveryStatus;
use App\Models\EstimateProductList;
use App\Models\StockQty;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DuePaymentDueDeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin.modules.delivery.due-payment-due-delivery');
    }


    // public function getDuePaymentDueDeliveryList(Request $request)
    // {

    //     if ($request->ajax()) {

    //         $estimate = Estimate::where('payment_status', 'PAYMENT DUE')
    //             ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
    //             ->where('delivery_status', 'NOT DELIVERED')
    //             ->where('delivery_status_ready_product', 'NOT DELIVERED')
    //             ->where('delivery_status_order_to_make', 'NOT DELIVERED')
    //             ->latest()
    //             ->pluck('id');

    //         $data = EstimateProductList::where('delivery_status', 'NOT DELIVERED')
    //             ->whereIn('estimate_id', $estimate)
    //             ->latest()
    //             ->get();

    //         return Datatables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('estimate_no', function ($row) {
    //                 return $row->Estimate->estimate_no;
    //             })
    //             ->addColumn('branch_name', function ($row) {
    //                 return $row->Estimate->user->branch->branch_name;
    //             })
    //             ->addColumn('customer', function ($row) {
    //                 return $row->Estimate->client_name;
    //             })
    //             ->addColumn('mobile', function ($row) {
    //                 return $row->Estimate->client_mobile;
    //             })
    //             ->addColumn('address', function ($row) {
    //                 return $row->Estimate->client_address;
    //             })
    //             ->addColumn('expected_delivery_date', function ($row) {
    //                 return $row->Estimate->expected_delivery_date;
    //             })
    //             ->addColumn('remarks', function ($row) {
    //                 return $row->remarks;
    //             })
    //             ->addColumn('qty', function ($row) {
    //                 return $row->qty;
    //             })
    //             ->addColumn('qty_undelivered', function ($row) {
    //                 $EstimateProductDeliveryStatus = EstimateProductDeliveryStatus::where('estimate_product_list_id', $row->id)->get();
    //                 $EstimateProductDeliveryStatus_qty = $EstimateProductDeliveryStatus->sum('qty');
    //                 $new_undelivered_quantity = ($row->qty - $EstimateProductDeliveryStatus_qty ?? 0);
    //                 return $new_undelivered_quantity;
    //             })
    //             ->addColumn('action', function ($row) {
    //                 $EstimateProductDeliveryStatus = EstimateProductDeliveryStatus::where('estimate_product_list_id', $row->id)->get();
    //                 $EstimateProductDeliveryStatus_qty = $EstimateProductDeliveryStatus->sum('qty');
    //                 $new_undelivered_quantity = ($row->qty - $EstimateProductDeliveryStatus_qty);

    //                 $EstimateProductList = EstimateProductList::where('id', $row->id)->first();

    //                 $branch_id = $EstimateProductList->estimate->user->branch_id;
    //                 $StockQty = StockQty::where('branch_id', $branch_id)
    //                     ->where('product_id', $row->product_id)
    //                     ->first();
    //                 $new_stock_qty = $StockQty->qty ?? 0;
    //                 //need to verify this

    //                 $estimate_product_delivery_statuses = EstimateProductDeliveryStatus::where('estimate_product_list_id', $row->id)->get();
    //                 $quantity_already_delivered = $estimate_product_delivery_statuses->sum('qty');


    //                 return '<nav class="nav">
    //                         <div class="dropdown-menu dropdown-menu-right shadow">
    //                             <a data-product_id="' . $row->product_id . '"  data-id="' . $row->id . '"  data-branch_id="' . $branch_id . '"    class="dropdown-item" href="' . route('due-delivery.show', $row->id) . '"><i class="fe fe-edit text-primary"></i> View</a>
    //                             <a class="dropdown-item" href="javascript:void(0);"><label class="ckbox"><input type="checkbox" data-product_id="' . $row->product_id . '"   data-branch_id="' . $branch_id . '"  data-product_stock_qty="' . $new_stock_qty . '" data-qty="' . $row->qty . '"  data-id="' . $row->id . '" data-new_undelivered_quantity="' . $new_undelivered_quantity . '"  data-quantity_already_delivered="' . $quantity_already_delivered . '" class="conformDelivery"><span>Confirm</span></label></a>
    //                         </div>
    //                         <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
    //                     </nav>';
    //             })
    //             ->rawColumns(['qty', 'action'])
    //             ->make(true);
    //     }
    // }



    public function getDuePaymentDueDeliveryList(Request $request)
    {
        if ($request->ajax()) {
            // $data = Estimate::where('payment_status', 'PAYMENT DUE')
            //     ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
            //     ->where('delivery_status', 'NOT DELIVERED')
            //     ->where('delivery_status_ready_product', 'NOT DELIVERED')
            //     ->where('delivery_status_order_to_make', 'NOT DELIVERED')
            //     ->latest()
            //     ->get();

            $data = Estimate::where('is_admin_approved', 'NO')
                ->latest()
                ->get();



            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row->user->branch->branch_name;
                })
                ->addColumn('total_paid', function ($row) {
                    return $row->EstimatePaymentLists->sum('total_paid');
                })
                ->addColumn('action', function ($row) {

                    if ($row->estimate_status == 'ESTIMATE CREATED' && $row->payment_status == 'PAYMENT DUE' && $row->delivery_status == 'NOT DELIVERED' && $row->is_admin_approved == 'NO') {
                        return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                            <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate.show', $row->id) . '"><i class="fe fe-edit text-success"></i> View</a>
                   
                                <a data-id="' . $row->id . '" class="dropdown-item dueApproval" href="javascript:void(0)"><i class="fa fa-user text-danger"></i> Approve Due Estimate </a>
                                </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                    } else if ($row->estimate_status == 'ESTIMATE CREATED' && $row->payment_status == 'PAYMENT DUE' && $row->delivery_status == 'NOT DELIVERED') {
                        return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                            <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate.show', $row->id) . '"><i class="fe fe-edit text-success"></i> View</a>

    
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                    } else {

                        $estimate_product_list_id = EstimateProductList::where('estimate_id', $row->id)->pluck('id');
                        $estimate_product_delivery_statuses = EstimateProductDeliveryStatus::where('delivery_status', 'ITEM DELIVERED')
                            ->whereIn('estimate_product_list_id', $estimate_product_list_id)
                            ->get();
                        $estimate_product_delivery_statuses_count = $estimate_product_delivery_statuses->count();


                        if ($estimate_product_delivery_statuses_count > 0 || $row->delivery_status == 'DELIVERED') {
                            return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate.show', $row->id) . '"><i class="fe fe-edit text-success"></i> View</a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                        } else {
                            return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate.show', $row->id) . '"><i class="fe fe-edit text-success"></i> View</a>

                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                        }
                    }
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
