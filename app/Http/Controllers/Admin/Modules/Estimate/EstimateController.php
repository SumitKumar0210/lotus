<?php

namespace App\Http\Controllers\Admin\Modules\Estimate;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use App\Models\EstimatePaymentList;
use App\Models\EstimateProductDeliveryStatus;
use App\Models\EstimateProductList;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin.modules.estimates.estimate-lists');
    }


    public function getEstimateList(Request $request)
    {
        if ($request->ajax()) {
           
            $data = Estimate::with('EstimateProductLists', 'EstimatePaymentLists')
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->latest();

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row->user->branch->branch_name;
                })
                ->addColumn('total_paid', function ($row) {
                    return $row->EstimatePaymentLists->sum('total_paid');
                })
                ->addColumn('discount_per', function ($row) {
                    $total = 0;
                    $dis = 0;
                    $tot = 0;
                    $dis = $row->discount_value;
                    $tot = $row->sub_total;
                    if ($dis > 0 && $tot > 0) {
                        $total = $dis / $tot;
                    }
                    return round($total, 2);
                })
                ->addColumn('action', function ($row) {

                    if ($row->estimate_status == 'ESTIMATE CREATED' && $row->delivery_status == 'NOT DELIVERED' && $row->is_admin_approved == 'NO') {
                        return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                            <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate.show', $row->id) . '"><i class="fe fe-edit text-success"></i> View</a>
                                <a class="dropdown-item" href="' . route('estimate.edit', $row->id) . '"><i class="fe fe-edit text-primary"></i> Edit</a>
                                <a data-id="' . $row->id . '" class="dropdown-item cancelProduct" href="javascript:void(0)"><i class="fa fa-times"></i> Cancel </a>
                               
                                </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                    } else if ($row->estimate_status == 'ESTIMATE CREATED' && $row->delivery_status == 'NOT DELIVERED') {
                        return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                            <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate.show', $row->id) . '"><i class="fe fe-edit text-success"></i> View</a>
                                <a class="dropdown-item" href="' . route('estimate.edit', $row->id) . '"><i class="fe fe-edit text-primary"></i> Edit</a>
                                <a data-id="' . $row->id . '" class="dropdown-item cancelProduct" href="javascript:void(0)"><i class="fa fa-times"></i> Cancel </a>
                                
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
                                <a class="dropdown-item" href="' . route('estimate.edit', $row->id) . '"><i class="fe fe-edit text-primary"></i> Edit</a>
                                <a data-id="' . $row->id . '" class="dropdown-item cancelProduct" href="javascript:void(0)"><i class="fa fa-times"></i> Cancel </a>
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


    public function getEstimateListOrderToMakeIndex()
    {
        return view('backend.admin.modules.estimates.estimate-lists-order-to-make');
    }


    public function getEstimateListOrderToMake(Request $request)
    {

        if ($request->ajax()) {

            $estimates_id = Estimate::with('EstimateProductLists', 'EstimatePaymentLists')
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->latest()
                ->pluck('id');

            $data2 = EstimateProductList::whereIn('estimate_id', $estimates_id)
                ->where('product_type', 'ORDER TO MAKE')
                ->pluck('estimate_id');

            $data3 = Estimate::with('EstimateProductLists', 'EstimatePaymentLists')
                ->whereIn('id', $data2)
                ->latest()
                ->get();

            return Datatables::of($data3)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row->user->branch->branch_name;
                })
                ->addColumn('total_paid', function ($row) {
                    return $row->EstimatePaymentLists->sum('total_paid');
                })
                ->addColumn('action', function ($row) {

                    $estimate_product_lists = EstimateProductList::where('estimate_id', $row->id)->get();
                    $estimate_product_list_ids = [];
                    foreach ($estimate_product_lists as $estimate_product_list) {
                        $estimate_product_list_ids[] = $estimate_product_list->id;
                    }

                    $estimate_product_delivery_statuses = EstimateProductDeliveryStatus::whereIn('estimate_product_list_id', $estimate_product_list_ids)
                        ->get();
                    $count = count($estimate_product_delivery_statuses);

                    if ($row->is_admin_approved == 'NO') {
                        return '<nav class="nav">
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate.show', $row->id) . '"><i class="fe fe-edit text-success"></i> View</a>
                            <a class="dropdown-item" href="' . route('estimate.edit', $row->id) . '"><i class="fe fe-edit text-primary"></i> Edit</a>
                            <a data-id="' . $row->id . '" class="dropdown-item dueApproval" href="javascript:void(0)"><i class="fa fa-user text-danger"></i> Approve Due Estimate </a>
                           
                        </div>
                        <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                    </nav>';
                    } else if ($count > 0 || $row->delivery_status == 'DELIVERED') {
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
                                <a class="dropdown-item" href="' . route('estimate.edit', $row->id) . '"><i class="fe fe-edit text-primary"></i> Edit</a>
                                
                                <!--<a data-id="' . $row->id . '" class="dropdown-item deleteProduct" href="javascript:void(0)"><i class="far fa-trash-alt text-danger"></i> Cancel </a>-->
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $estimate = Estimate::with('EstimateProductLists', 'EstimatePaymentLists', 'user.branch')->find($id);
        $paid_in_cash = 0;
        $paid_in_bank = 0;
        $total_paid = 0;
        foreach ($estimate->EstimatePaymentLists as $key => $EstimatePaymentList) {
            $paid_in_cash += $EstimatePaymentList->paid_in_cash;
            $paid_in_bank += $EstimatePaymentList->paid_in_bank;
            $total_paid += $EstimatePaymentList->total_paid;
        }
        return view('backend.admin.modules.estimates.estimate-view', compact('estimate', 'paid_in_cash', 'paid_in_bank', 'total_paid'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$products = Product::latest()->get();
        $products = Product::where('status', 'Active')->latest()->get();
        $estimate = Estimate::with('EstimateProductLists', 'EstimatePaymentLists', 'user.branch')->find($id);
        return view('backend.admin.modules.estimates.estimate-edit', compact('estimate', 'products'));
    }


    public function getEstimateProductsReadyList($id)
    {
        //$estimate = EstimateProductList::where('estimate_id',$id)->where('product_type','READY PRODUCT')->get();
        $estimate = EstimateProductList::where('estimate_id', $id)->get();
        if (empty($estimate)) {
            return response()->json(['error' => 'no data found'], 200);
        }
        return response()->json(['success' => $estimate], 200);
    }


    public function getReadyProductDetail($product_id)
    {
        $product = Product::with('brand', 'category')->find($product_id);
        if (!empty($product)) {
            return response()->json($product);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    /** public function update(Request $request, $id)
    {
        //return response()->json($request->all());
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'estimate_id' => ['required'],
                'remarks' => ['nullable', 'max:255'],
                'sub_total' => ['required', 'numeric'],
                'discount_percent' => ['nullable', 'numeric'],
                'discount_value' => ['nullable', 'numeric'],
                'freight_charge' => ['nullable', 'numeric'],
                'misc_charge' => ['nullable', 'numeric'],
                'grand_total' => ['required', 'numeric'],
                'dues_amount' => ['required', 'numeric'],
                'paid_in_cash' => ['nullable', 'numeric'],
                'paid_in_bank' => ['nullable', 'numeric'],
                'total_paid' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }


            $estimate = Estimate::find($id);
            $estimate->grand_total = $request->grand_total;
            $dues_amount = $estimate->dues_amount;
            if ($dues_amount < 0 || $dues_amount == 0) {
                $status = 'PAYMENT DONE';
            } else {
                $status = 'PAYMENT DUE';
            }
            $estimate->dues_amount = $request->dues_amount;
            $estimate->payment_status = $status;
            $estimate->sub_total = $request->sub_total;
            $estimate->save();


            if ($estimate) {

                EstimateProductList::where('estimate_id', $estimate->id)->delete();
                foreach ($request->products as $key => $product) {

                    $estimate_product_lists = EstimateProductList::create([
                        "estimate_id" => $estimate->id,
                        "product_id" => $product['product_id'],
                        "product_type" => $product['product_type'],
                        "product_name" => $product['product_name'],
                        "product_code" => $product['product_code'],
                        "color" => $product['color'],
                        "size" => $product['size'],
                        "qty" => $product['qty'],
                        "mrp" => $product['maximum_retail_price'],
                        "amount" => $product['amount'],
                    ]);
                }

                if ($estimate_product_lists) {
                    EstimatePaymentList::where('estimate_id', $estimate->id)->delete();
                    $estimate_payment_lists = EstimatePaymentList::create([
                        "estimate_id" => $estimate->id,
                        "date_time" => Carbon::now()->format('d-m-Y'),
                        "paid_in_cash" => $request->paid_in_cash,
                        "paid_in_bank" => $request->paid_in_bank,
                        "total_paid" => $request->total_paid,
                    ]);

                    if ($estimate_payment_lists) {
                        $response = response()->json(['success' => 'Estimate updated successfully'], 200);
                    } else {
                        $response = response()->json(['errors_success' => 'Error in creating Estimate, Please try again'], 200);
                    }
                } else {
                    $response = response()->json(['errors_success' => 'Error in creating Estimate, Please try again'], 200);
                }
            } else {
                $response = response()->json(['errors_success' => 'Error in creating Estimate, Please try again'], 200);
            }
            return $response;
        }
    }
     */






    public function update(Request $request, $id)
    {
        //return $request->all();
        // dd($request->all());
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'estimate_id' => ['required'],
                'remarks' => ['nullable', 'max:255'],
                'sub_total' => ['required', 'numeric'],
                'discount_percent' => ['nullable', 'numeric'],
                'discount_value' => ['nullable', 'numeric'],
                'freight_charge' => ['nullable', 'numeric'],
                'misc_charge' => ['nullable', 'numeric'],
                'grand_total' => ['required', 'numeric'],
                'dues_amount' => ['required', 'numeric'],
                'paid_in_cash' => ['nullable', 'numeric'],
                'paid_in_bank' => ['nullable', 'numeric'],
                'total_paid' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }


            $estimate = Estimate::find($id);
            $estimate->grand_total = $request->grand_total;
            // $dues_amount = $estimate->dues_amount;
            $dues_amount = $request->dues_amount;
            if ($dues_amount < 0 || $dues_amount == 0) {
                $status = 'PAYMENT DONE';
            } else {
                $status = 'PAYMENT DUE';
            }

            //return response()->json($status);


            $estimate->dues_amount = $request->dues_amount;
            $estimate->payment_status = $status;
            $estimate->sub_total = $request->sub_total;
            $estimate->settlement_amount = $request->settlement_amount;
            $estimate->save();





            if ($estimate) {

                EstimateProductList::where('estimate_id', $estimate->id)->where('delivery_status', 'NOT DELIVERED')->delete();
                foreach ($request->products as $key => $product) {
                    if ($product['status'] != 'DELIVERED') {
                        $estimate_product_lists = EstimateProductList::create([
                            "estimate_id" => $estimate->id,
                            "product_id" => $product['product_id'],
                            "product_type" => $product['product_type'],
                            "product_name" => $product['product_name'],
                            "product_code" => $product['product_code'],
                            "color" => $product['color'],
                            "size" => $product['size'],
                            "qty" => $product['qty'],
                            "mrp" => $product['maximum_retail_price'],
                            "amount" => $product['amount'],
                        ]);
                    }
                }

                if ($estimate_product_lists) {
                    EstimatePaymentList::where('estimate_id', $estimate->id)->delete();
                    $estimate_payment_lists = EstimatePaymentList::create([
                        "estimate_id" => $estimate->id,
                        "date_time" => Carbon::now()->format('d-m-Y'),
                        "paid_in_cash" => $request->paid_in_cash,
                        "paid_in_bank" => $request->paid_in_bank,
                        "total_paid" => $request->total_paid,
                    ]);



                    if ($request->settlement_amount > 0) {
                        EstimatePaymentList::create([
                            "estimate_id" => $estimate->id,
                            "paid_in_cash" => $request->settlement_amount,
                            "total_paid" => $request->settlement_amount,
                            "is_settled" => "YES",
                            "date_time" => Carbon::now()->format('d-m-Y'),
                        ]);
                    }


                    if ($estimate_payment_lists) {
                        $response = response()->json(['success' => 'Estimate updated successfully'], 200);
                    } else {
                        $response = response()->json(['errors_success' => 'Error in creating Estimate, Please try again'], 200);
                    }
                } else {
                    $response = response()->json(['errors_success' => 'Error in creating Estimate, Please try again'], 200);
                }
            } else {
                $response = response()->json(['errors_success' => 'Error in creating Estimate, Please try again'], 200);
            }
            return $response;
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


    public function cancelEstimate($id)
    {

        $estimate = Estimate::where('id', $id)
            //->where('payment_status', 'PAYMENT DUE')
            ->where('delivery_status', 'NOT DELIVERED')
            ->update([
                'estimate_status' => 'ESTIMATE CANCELLED'
            ]);
        if ($estimate) {

            return response()->json(['success' => 'Estimate Cancelled Successfully']);
        }
        return response()->json(['error' => 'Error in cancelling Estimate, please try again']);
    }


    //new customize product code************************
    public function checkCustomizeProductIsAddedOrNotAdmin(Request $request)
    {
        $old_product = Product::find($request->product_id);
        if ($old_product->color_code == $request->color && $old_product->size == $request->size) {
            return response()->json([
                'error' => 'Please Modify',
            ]);
        }

        $products = Product::where('product_code', $request->product_code)
            ->where('color_code', $request->color)
            ->where('size', $request->size)
            ->get();

        if (sizeof($products) > 0) {
            return response()->json([
                'success' => 'Product Exists',
                'data' => [],
            ]);
        } else {


            //product name
            $prefix = $old_product['product_name'] . ' -ORD';
            $last_product = Product::withTrashed()->latest()->first();
            if (!empty($last_product)) {
                $last_product_name_prefix = $last_product->product_name;
                preg_match_all('!\d+!', $last_product_name_prefix, $matches);
                $num = end($matches[0]);
                $product_name = $prefix . ($num + 1);
            } else {
                $product_name = $prefix . '1';
            }
            //product name


            //product code
            $prefix2 = $old_product['product_code'] . ' -ORD';
            $last_product2 = Product::withTrashed()->latest()->first();
            if (!empty($last_product2)) {
                $last_product_code_prefix2 = $last_product2->product_code;
                preg_match_all('!\d+!', $last_product_code_prefix2, $matches2);
                $num2 = end($matches2[0]);
                $product_code = $prefix2 . ($num2 + 1);
            } else {
                $product_code = $prefix2 . '1';
            }
            //product code


            $product = new Product();
            $product->product_code = $product_code;
            $product->color_code = $request->color;
            $product->size = $request->size;
            $product->brand_id = $old_product->brand_id;
            $product->category_id = $old_product->category_id;
            $product->product_type = $old_product->product_type;
            $product->product_name = $product_name;
            $product->description = $old_product->description;
            $product->maximum_retail_price = $old_product->maximum_retail_price;
            $product->minimum_stock_quantity = $old_product->minimum_stock_quantity;
            $product->save();

            return response()->json([
                'success' => 'Product Created',
                'data' => $product,
            ]);
        }
    }
    //new customize product code************************



    public function applyForDueApproval(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'estimate_id' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $estimate_update = Estimate::where('id', $request->estimate_id)->update([
                'is_admin_approved' => "YES",
            ]);

            if ($estimate_update) {
                $response = response()->json(['success' => 'Estimate waiting for approval'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Something went wrong, please try again'], 200);
            }
            return $response;
        }
    }
}
