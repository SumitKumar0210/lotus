<?php

namespace App\Http\Controllers\Branch\Modules\Estimate;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Estimate;
use App\Models\EstimatePaymentList;
use App\Models\EstimateProductDeliveryStatus;
use App\Models\EstimateProductList;
use App\Models\Product;
use App\Models\StockQty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EstimateListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.branch.modules.estimates.estimate-lists');
    }


    public function getEstimateList(Request $request)
    {
        if ($request->ajax()) {
            $data = Estimate::with('EstimateProductLists', 'EstimatePaymentLists')
                ->where('branch_id', Auth::user()->branch->id)
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->latest()
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row->user->branch->branch_name;
                })
                ->addColumn('product_name', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->product_name . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })
                ->addColumn('product_code', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->product_code . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })
                ->addColumn('color', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->color . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })
                ->addColumn('size', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->size . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })
                ->addColumn('quantity', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->qty . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })
                ->addColumn('action', function ($row) {


                    if ($row->estimate_status == 'ESTIMATE CREATED' && $row->payment_status == 'PAYMENT DUE' && $row->delivery_status == 'NOT DELIVERED') {
                        return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate-list.show', $row->id) . '"><i class="fe fe-edit text-primary"></i> View</a>
                               <a data-id="' . $row->id . '" class="dropdown-item printProduct" href="' . route('branch.estimateList.estimatePrint', $row->id) . '" target="_blank"><i class="far fa fa-print "></i> Print </a>
                               <a data-id="' . $row->id . '" class="dropdown-item cancelProduct" href="javascript:void(0)"><i class="fa fa-times"></i> Cancel </a>
                                <a data-id="' . $row->id . '" class="dropdown-item deleteProduct" href="javascript:void(0)"><i class="far fa-trash-alt text-danger"></i> Delete </a>
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
                                <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate-list.show', $row->id) . '"><i class="fe fe-edit text-primary"></i> View</a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                        } else {
                            return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate-list.show', $row->id) . '"><i class="fe fe-edit text-primary"></i> View</a>
                                <a data-id="' . $row->id . '" class="dropdown-item cancelProduct" href="javascript:void(0)"><i class="fa fa-times"></i> Cancel </a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                        }
                    }
                })
                ->rawColumns(['product_name', 'product_code', 'color', 'size', 'quantity', 'action'])
                ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $products = Product::where('status', 'Active')->latest()->get();
        $current_branch = Branch::where('id', Auth::user()->branch_id)->first();
        $print_slug = strtoupper($current_branch->print_slug);

        //create estimate_no
        $carbon = Carbon::now();
        $current_year = $carbon->format('y');
        $next_year = $carbon->addYear()->format('y');
        $prefix = $print_slug . '_' . $current_year . '-' . $next_year . '_';


        $last_estimate = Estimate::withTrashed()
            //->where('estimate_no', 'like', '%' . $print_slug)
            ->where(\DB::raw('substr(estimate_no, 1, 3)'), '=', $print_slug)
            ->latest()
            ->first();

        //dd($last_estimate);

        if (!empty($last_estimate)) {
            $last_estimate_prefix = $last_estimate->estimate_no;
            $num = substr($last_estimate_prefix, 10);
            $estimate_no = $prefix . ($num + 1);
        } else {
            $estimate_no = $prefix . '1';
        }
        //create estimate_no


        //dd($estimate_no);

        return view('backend.branch.modules.estimates.create-estimate', compact('products', 'estimate_no'));
    }


    public function getReadyProductDetail($product_id)
    {
        //        $product = Product::with('brand', 'category')->find($product_id);
        //        if (!empty($product)) {
        //            return response()->json($product);
        //        }

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
                'client_mobile' => ['required', 'numeric', 'digits:10'],
                'client_name' => ['required', 'max:255'],
                'client_address' => ['required', 'max:255'],
                'estimate_no' => ['required', 'unique:estimates'],
                'estimate_date' => ['required', 'max:255'],
                'expected_delivery_date' => ['required', 'max:255'],
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
                'sale_by' => ['required', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }


            $current_branch = Branch::where('id', Auth::user()->branch_id)->first();
            $print_slug = strtoupper($current_branch->print_slug);

            //again create estimate_no for safety
            $carbon = Carbon::now();
            $current_year = $carbon->format('y');
            $next_year = $carbon->addYear()->format('y');
            $prefix = $print_slug . '_' . $current_year . '-' . $next_year . '_';


            $last_estimate = Estimate::withTrashed()
                ->where(\DB::raw('substr(estimate_no, 1, 3)'), '=', $print_slug)
                ->latest()
                ->first();


            if (!empty($last_estimate)) {
                $last_estimate_prefix = $last_estimate->estimate_no;
                $num = substr($last_estimate_prefix, 10);
                $estimate_no = $prefix . ($num + 1);
            } else {
                $estimate_no = $prefix . '1';
            }
            //again create estimate_no for safety


            $estimate = new Estimate();
            $estimate->user_id = Auth::id();
            $estimate->branch_id = Auth::user()->branch_id;
            $estimate->client_mobile = $request->client_mobile;
            $estimate->client_name = $request->client_name;
            $estimate->client_address = $request->client_address;
            $estimate->client_email = $request->client_email;
            $estimate->estimate_no = $estimate_no;
            $estimate->estimate_date = $request->estimate_date;
            $estimate->expected_delivery_date = $request->expected_delivery_date;
            $estimate->remarks = $request->remarks;
            $estimate->sub_total = $request->sub_total;
            $estimate->discount_percent = $request->discount_percent ?? 0;
            $estimate->discount_value = $request->discount_value;
            $estimate->freight_charge = $request->freight_charge ?? 0;
            $estimate->misc_charge = $request->misc_charge ?? 0;
            $estimate->grand_total = $request->grand_total;
            $estimate->dues_amount = $request->dues_amount;
            $estimate->sale_by = $request->sale_by;


            $dues_amount = $estimate->dues_amount;
            if ($dues_amount < 0 || $dues_amount == 0) {
                $payment_status = 'PAYMENT DONE';
            } else {
                $payment_status = 'PAYMENT DUE';
            }
            $estimate->payment_status = $payment_status;
            $estimate->estimate_status = 'ESTIMATE CREATED';
            $estimate->delivery_status = 'NOT DELIVERED';

            $estimate->delivery_status_ready_product = 'NOT DELIVERED';
            $estimate->delivery_status_order_to_make = 'NOT DELIVERED';
            $estimate->save();


            if ($estimate) {

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

                    $estimate_payment_lists = EstimatePaymentList::create([
                        "estimate_id" => $estimate->id,
                        "date_time" => Carbon::now()->format('d-m-Y'),
                        "paid_in_cash" => $request->paid_in_cash,
                        "paid_in_bank" => $request->paid_in_bank,
                        "total_paid" => $request->total_paid,
                    ]);

                    if ($estimate_payment_lists) {


                        //                        //create order to make product********************************
                        //                        foreach ($request->products as $key => $product) {
                        //
                        //
                        //                            if ($product['product_type'] == 'ORDER TO MAKE') {
                        //
                        //                                //product name
                        //                                $prefix = $product['product_name'] . ' -ORD';
                        //                                $last_product = Product::withTrashed()->latest()->first();
                        //                                if (!empty($last_product)) {
                        //                                    $last_product_name_prefix = $last_product->product_name;
                        //                                    preg_match_all('!\d+!', $last_product_name_prefix, $matches);
                        //                                    $num = end($matches[0]);
                        //                                    $product_name = $prefix . ($num + 1);
                        //                                } else {
                        //                                    $product_name = $prefix . '1';
                        //                                }
                        //                                //product name
                        //
                        //
                        //                                //product code
                        //                                $prefix2 = $product['product_code'] . ' -ORD';
                        //                                $last_product2 = Product::withTrashed()->latest()->first();
                        //                                if (!empty($last_product2)) {
                        //                                    $last_product_code_prefix2 = $last_product2->product_code;
                        //                                    preg_match_all('!\d+!', $last_product_code_prefix2, $matches2);
                        //                                    $num2 = end($matches2[0]);
                        //                                    $product_code = $prefix2 . ($num2 + 1);
                        //                                } else {
                        //                                    $product_code = $prefix2 . '1';
                        //                                }
                        //                                //product code
                        //
                        //
                        //                                $product_check = Product::whereIn('product_name', [$product['product_name'], $product_name])
                        //                                    ->whereIn('product_code', [$product['product_code'], $product_code])
                        //                                    ->where('color_code', $product['color'])
                        //                                    ->where('size', $product['size'])
                        //                                    ->where('product_type', 'ORDER TO MAKE')
                        //                                    ->get();
                        //
                        //                                // $product_check = Product::where('color_code',$product['color'])
                        //                                //     ->where('size',$product['size'])
                        //                                //     ->get();
                        //
                        //
                        //                                if (count($product_check) < 1) {
                        //
                        //                                    //find product details
                        //                                    $product_first = Product::where('id', $product['product_id'])->first();
                        //                                    //find product details
                        //
                        //                                    $product2 = new Product();
                        //                                    $product2->product_name = $product_name;
                        //                                    $product2->product_code = $product_code;
                        //                                    $product2->product_type = $product['product_type'];
                        //                                    $product2->brand_id = $product_first->brand_id;
                        //                                    $product2->category_id = $product_first->category_id;
                        //                                    $product2->description = null;
                        //                                    $product2->color_code = $product['color'];
                        //                                    $product2->size = $product['size'];
                        //                                    $product2->maximum_retail_price = $product['maximum_retail_price'];
                        //                                    $product2->minimum_stock_quantity = 1;
                        //                                    $product2->save();
                        //
                        //                                }
                        //                            }
                        //                        }
                        //                        //create order to make product***************************


                        $response = response()->json(['success' => 'Estimate created successfully', 'estimate' => $estimate], 200);
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
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

        return view('backend.branch.modules.estimates.estimate-view', compact('estimate', 'paid_in_cash', 'paid_in_bank', 'total_paid'));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $estimate = Estimate::with('EstimateProductLists', 'EstimatePaymentLists')->find($id);
        if (!empty($estimate)) {
            if ($estimate->estimate_status == 'ESTIMATE CREATED' && $estimate->payment_status == 'PAYMENT DUE' && $estimate->delivery_status == 'NOT DELIVERED') {
                $estimate->EstimateProductLists->each->delete();
                $estimate->EstimatePaymentLists->each->delete();
                $estimate->delete();
                return response()->json(['success' => 'Estimate deleted successfully']);
            }
        }
    }


    public function getClientDetails(Request $request)
    {

        $mobile_number = $request->client_mobile;

        $estimate = Estimate::where('client_mobile', $mobile_number)->latest()->first();
        if (!empty($estimate)) {
            return response()->json(['success' => $estimate]);
        } else {
            return response()->json(['errors_success' => 'errors_success']);
        }
    }


    public function estimatePrint(Request $request, $id)
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

        $current_branch = Branch::where('id', Auth::user()->branch_id)->first();

        return view('backend.branch.modules.estimates.estimate-print', compact('estimate', 'paid_in_cash', 'paid_in_bank', 'total_paid', 'current_branch'));
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
    public function checkCustomizeProductIsAddedOrNot(Request $request)
    {

        //return response()->json($request->all());

        $old_product = Product::find($request->product_id);
        if (
            $old_product->color_code == $request->color && $old_product->size == $request->size
            && $old_product->product_code == $request->product_code
        ) {
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


            // $check_product_code = Product::where('product_code', $request->product_code)->get();
            // if (sizeof($check_product_code) > 0) {
            //     return response()->json([
            //         'error' => 'Model no already exists',
            //     ]);
            // }

            //product name
            // $prefix = $old_product['product_name'] . ' -ORD';
            $prefix = $request->product_name;
            $prefix = $prefix . ' -ORD';
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
            //$prefix2 = $old_product['product_code'] . ' -ORD';
            $prefix2 = $request->product_code;
            $prefix2 = $prefix2 . ' -ORD';
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








            $special_characters = [':', '-', '/', '%', '#', '&', '@', '$', '*', ' ', ')', '(', '!', '^', '-', '+', '_', '=', '{', '}', '[', ']', '', '<', '>' . '?'];
            $product_code_search = str_replace($special_characters, '', $product_code);
            $product_name_search = str_replace($special_characters, '', $product_name);

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
            $product->status = 'Active';
            $product->product_name_search = $product_name_search;
            $product->product_code_search = $product_code_search;
            $product->save();


            return response()->json([
                'success' => 'Product Created',
                'data' => $product,
            ]);
        }
    }

    //new customize product code************************


    public function setPaymentTypeInSession(Request $request)
    {
        $payment_type = $request->payment_type;
        $request->session()->flash('payment_type', $payment_type);
        //session(['payment_type' => $payment_type]);
        $payment_type_current_session_value = session('payment_type');
        return response()->json(['success' => $payment_type_current_session_value]);
    }
}
