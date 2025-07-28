<?php

namespace App\Http\Controllers\Branch\Modules\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Estimate;
use App\Models\EstimateProductDeliveryStatus;
use App\Models\EstimateProductList;
use App\Models\StockQty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DeliveredListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.branch.modules.delivery.delivered');
    }


    public function getDeliveredList(Request $request)
    {
        if ($request->ajax()) {
            $branch_id = Auth::user()->branch_id;
            $estimate_ids = Estimate::where('branch_id', $branch_id)->pluck('id');
            $estimate_product_list_ids = EstimateProductList::whereIn('estimate_id', $estimate_ids)->pluck('id');

            $data = EstimateProductDeliveryStatus::with('ProductList')
                ->whereIn('estimate_product_list_id', $estimate_product_list_ids)
                ->where('delivery_status', 'ITEM DELIVERED')
                ->latest();
                // ->get();

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('estimate_no', function ($row) {
                    return optional($row->ProductList->Estimate)->estimate_no ?? '';
                })
                ->addColumn('branch_name', function ($row) {
                    return optional($row->ProductList->Estimate->user->branch)->branch_name ?? '';
                })
                ->addColumn('customer_details', function ($row) {
                    return optional($row->ProductList->Estimate)->client_name . ' ' . optional($row->ProductList->Estimate)->client_mobile . ' ' . optional($row->ProductList->Estimate)->client_address;
                })
                //                ->addColumn('client_mobile', function ($row) {
                //                    return $row->ProductList->Estimate->client_mobile;
                //                })
                //                ->addColumn('client_address', function ($row) {
                //                    return $row->ProductList->Estimate->client_address;
                //                })
                ->addColumn('delivery_date', function ($row) {
                    return $row->date_time;
                })
                ->addColumn('product_name', function ($row) {
                    return optional($row->ProductList)->product_name ?? '';
                })
                ->addColumn('product_code', function ($row) {
                    return optional($row->ProductList)->product_code . '<br>' . optional($row->ProductList)->color . '<br>' . optional($row->ProductList)->size;
                })
                ->addColumn('quantity', function ($row) {
                    return $row->qty;
                })
                ->addColumn('remarks', function ($row) {
                    return optional($row->ProductList->Estimate)->remarks ?? '';
                })
                ->addColumn('product_type', function ($row) {
                    return optional($row->ProductList)->product_type ?? '';
                })
                ->addColumn('created_by', function ($row) {
                    return optional($row->ProductList->Estimate->user)->name ?? '';
                })
                ->addColumn('delivered_by', function ($row) {
                    return optional($row->user)->name ?? '';
                })
                ->addColumn('action', function ($row) {
                    
                    $delivery_Status = $row->ProductList->delivery_status;

                    if ($row->ProductList->is_sale_returned == 'ITEM SALE RETURNED') {
                        return '';
                    } else if($delivery_Status == "DELIVERED") {
                        return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">

                           <a class="dropdown-item " href="javascript:void(0);">
                           <label class="ckbox">
                           <input type="checkbox" data-qty="' . $row->ProductList->qty . '"  data-id="' . $row->ProductList->id . '" data-product_id="' . $row->ProductList->product_id . '"    data-estimate_id="' . $row->ProductList->estimate_id . '"  class="conformDelivery">
                           <span>Sale Return</span>
                           </label>
                           </a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                    }else{
                        return 'Partially Delivered';
                    }
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" data-id="' . $row->id . '" data-estimate_numbers="' . $row->ProductList->Estimate->estimate_no . '"  name="someCheckbox[]" class="someCheckbox" />';
                })
                ->rawColumns(['action', 'checkbox', 'product_code'])
                ->filterColumn('estimate_no', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate', function ($q) use ($keyword) {
                        $q->where('estimate_no', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('branch_name', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate.user.branch', function ($q) use ($keyword) {
                        $q->where('branch_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('customer_details', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate', function ($q) use ($keyword) {
                        $q->where(function ($query) use ($keyword) {
                            $query->where('client_name', 'like', "%{$keyword}%")    
                                ->orWhere('client_mobile', 'like', "%{$keyword}%")
                                ->orWhere('client_address', 'like', "%{$keyword}%");
                        });
                    });
                })
                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('ProductList', function ($q) use ($keyword) {
                        $q->where('product_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('product_code', function ($query, $keyword) {
                    $query->whereHas('ProductList', function ($q) use ($keyword) {
                        $q->where(function ($query) use ($keyword) {
                            $query->where('product_code', 'like', "%{$keyword}%")
                                ->orWhere('color', 'like', "%{$keyword}%")
                                ->orWhere('size', 'like', "%{$keyword}%");
                        });
                    });
                })
                ->filterColumn('product_type', function ($query, $keyword) {
                    $query->whereHas('ProductList', function ($q) use ($keyword) {
                        $q->where('product_type', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('created_by', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate.user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('delivered_by', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('remarks', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate', function ($q) use ($keyword) {
                        $q->where('remarks', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('delivery_date', function ($query, $keyword) {
                    $query->where('date_time', 'like', "%{$keyword}%");
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
                'qty_to_sale_return' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $estimate_product_list = EstimateProductList::find($request->estimate_product_list_id);
            $estimate_product_list->is_sale_returned = 'ITEM SALE RETURNED';
            $estimate_product_list->sale_returned_qty = $request->qty_to_sale_return;

            if ($estimate_product_list->save()) {

                //stock update
                $StockQty = StockQty::where('branch_id', Auth::user()->branch_id)
                    ->where('product_id', $request->product_id)
                    ->first();
                $old_stock_qty = $StockQty->qty;
                $qty_to_sale_return = $request->qty_to_sale_return;

                $new_stock_qty = ($old_stock_qty + $qty_to_sale_return);

                $stock = StockQty::where('product_id', $request->product_id)
                    ->where('branch_id', Auth::user()->branch_id)
                    ->update(["qty" => $new_stock_qty,]);
                //stock update

                if ($stock) {
                    $response = response()->json(['success' => 'Sale returned Successfully'], 200);
                } else {
                    $response = response()->json(['errors_success' => 'Error in sale returning Product, please try again'], 200);
                }
            } else {
                $response = response()->json(['errors_success' => 'Error in sale returning Product, please try again'], 200);
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


    public function printChallanBulk(Request $request)
    {

        //dd($request->all());
        $request->validate([
            'estimate_product_delivery_status_ids' => 'required',
            'estimate_numbers' => 'required',
        ]);

        $array_estimate_numbers = explode(',', $request->estimate_numbers);

        $allValuesAreTheSame = (count(array_unique($array_estimate_numbers)) === 1);
        if (!$allValuesAreTheSame) {
            return redirect()->back()->with(['error' => 'Please select products from same estimates']);
        }




        $array_estimate_product_delivery_status_ids = array_map('intval', explode(',', $request->estimate_product_delivery_status_ids));





        $estimate_product_list_ids = EstimateProductDeliveryStatus::whereIn('id', $array_estimate_product_delivery_status_ids)->get()->pluck('estimate_product_list_id');

        $estimate_product_lists = EstimateProductList::whereIn('id', $estimate_product_list_ids)->get();

        $estimate_id = EstimateProductList::whereIn('id', $estimate_product_list_ids)->take(1)->get()->pluck('estimate_id');
        $estimate_id = $estimate_id[0];


        $estimate = Estimate::find($estimate_id);

        $current_branch = Branch::where('id', Auth::user()->branch_id)->first();

        $estimate_product_list_delivery_status_lists = EstimateProductDeliveryStatus::whereIn('id', $array_estimate_product_delivery_status_ids)->get();

        //dd($estimate_product_list_delivery_status_lists);


        return view('backend.branch.modules.delivery.delivery-challan-print', compact('estimate', 'current_branch', 'estimate_product_lists', 'estimate_product_list_delivery_status_lists'));
    }
}
