<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

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


    public function topFiveBranchesList(Request $request)
    {

        if ($request->ajax()) {

            $startDate = Carbon::now();
            $firstDay = $startDate->firstOfMonth()->format('Y-m-d H:i:s');
            $data = Estimate::where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->whereBetween('created_at', [$firstDay, Carbon::now()])
                //->groupBy('branch_id')
                //->take(5)
                ->get();

            $branch_id = [];
            foreach ($data as $d) {
                $branch_id[$d->branch_id] = $d->EstimatePaymentLists->sum('total_paid');
            }
            arsort($branch_id);

            $branch_id_keys = [];
            //$branch_values = [];

            foreach ($branch_id as $key => $val) {
                $branch_id_keys[] = $key;
                //$branch_values[] = $val;
            }

            $data = Estimate::whereIn('branch_id', $branch_id_keys)
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->whereBetween('created_at', [$firstDay, Carbon::now()])
                ->groupBy('branch_id');
                //->take(5)
                //->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row->branch->branch_name;
                })
                ->addColumn('sale', function ($row) {
                    return $row->EstimatePaymentLists->sum('total_paid');
                })
//                ->addColumn('discount', function ($row) {
//
//                    $discount_value = Estimate::where('branch_id', $row->branch_id)->pluck('discount_value');
//                    $all_discount_value = $discount_value->sum('discount_value');
//
//                    $sub_total = Estimate::where('branch_id', $row->branch_id)->pluck('sub_total');
//                    $all_sub_total = $sub_total->sum('sub_total');
//
//                    if (!empty($all_discount_value) && !empty($all_sub_total)) {
//                        $discount_value = ($all_discount_value / $all_sub_total) * 100;
//                    } else {
//                        $discount_value = 0;
//                    }
//
//                    return $discount_value;
//                })
                ->addColumn('dues', function ($row) {

                    $estimate = Estimate::where('payment_status', 'PAYMENT DUE')
                        ->where('branch_id', $row->branch_id)
                        ->get();
                    $total_dues = $estimate->sum('dues_amount');

                    return $total_dues;
                })
                ->make(true);
        }
    }


    public function topFiveQuarterBranchesList(Request $request)
    {

        if ($request->ajax()) {

            // $date = Carbon::now('-3 months');
            // $firstOfQuarter = $date->firstOfQuarter();

            // $date2 = Carbon::now('-3 months');
            // $lastOfQuarter = $date2->lastOfQuarter();


            $date = Carbon::now()->subMonths(3);
            $firstOfQuarter = $date->firstOfQuarter();

            $date2 = Carbon::now()->subMonths(3);
            $lastOfQuarter = $date2->lastOfQuarter();

            $data = Estimate::where('payment_status', 'PAYMENT DONE')
                ->whereBetween('created_at', [$firstOfQuarter, $lastOfQuarter])
                //->groupBy('branch_id')
                // ->orderBy('id', 'desc')
                //->take(5)
                ->get();


            $branch_id = [];
            foreach ($data as $d) {
                $branch_id[$d->branch_id] = $d->EstimatePaymentLists->sum('total_paid');
            }
            arsort($branch_id);
            $branch_id_keys = [];
            foreach ($branch_id as $key => $val) {
                $branch_id_keys[] = $key;
            }

            $data = Estimate::whereIn('branch_id', $branch_id_keys)
                ->where('payment_status', 'PAYMENT DONE')
                ->whereBetween('created_at', [$firstOfQuarter, $lastOfQuarter])
                ->groupBy('branch_id');
                //->take(5)
               // ->get();


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row->branch->branch_name;
                })
                ->addColumn('sale', function ($row) {
                    return $row->EstimatePaymentLists->sum('total_paid');
                })
//                ->addColumn('discount', function ($row) {
//
//                    $discount_value = Estimate::where('branch_id', Auth::user()->branch_id)->pluck('discount_value');
//                    $all_discount_value = $discount_value->sum('discount_value');
//
//                    $sub_total = Estimate::where('branch_id', Auth::user()->branch_id)->pluck('sub_total');
//                    $all_sub_total = $sub_total->sum('sub_total');
//
//                    if (!empty($all_discount_value) && !empty($all_sub_total)) {
//                        $discount_value = ($all_discount_value / $all_sub_total) * 100;
//                    } else {
//                        $discount_value = 0;
//                    }
//
//                    return $discount_value;
//                })
                ->addColumn('dues', function ($row) {

                    $estimate = Estimate::where('payment_status', 'PAYMENT DUE')
                        ->where('branch_id', Auth::user()->branch_id)
                        ->get();
                    $total_dues = $estimate->sum('dues_amount');

                    return $total_dues;
                })
                ->make(true);
        }
    }


    public function topBranchesList(Request $request)
    {
        if ($request->ajax()) {
            $startDate = Carbon::now();
            $firstDay = $startDate->firstOfMonth()->format('Y-m-d H:i:s');
            $estimate_old = Estimate::with('EstimateProductLists', 'EstimatePaymentLists', 'user', 'branch')
                ->where('payment_status', 'PAYMENT DONE')
                ->whereBetween('created_at', [$firstDay, Carbon::now()])
                //->orderBy('id', 'desc')
                //->take(5)
                ->groupBy('branch_id')
                ->get();


            $branch_id = [];
            foreach ($estimate_old as $d) {
                $branch_id[$d->branch_id] = $d->EstimatePaymentLists->sum('total_paid');
            }
            arsort($branch_id);

            $branch_id_keys = [];
            foreach ($branch_id as $key => $val) {
                $branch_id_keys[] = $key;
            }


            $estimate_old = Estimate::with('EstimateProductLists', 'EstimatePaymentLists', 'user', 'branch')
                ->whereIn('branch_id', $branch_id_keys)
                ->where('payment_status', 'PAYMENT DONE')
                ->whereBetween('created_at', [$firstDay, Carbon::now()])
                //->orderBy('id', 'desc')
                //->take(5)
                ->groupBy('branch_id')
                ->get();
            //return response()->json($estimate_old);

            $branch_name = [];
            $sale = [];
            $discount = [];
            $dues = [];
            $grand_total = [];
            foreach ($estimate_old as $row) {

                //branch_name
                $branch_name[] = $row->branch->branch_name;
                //branch_name

                //sale
                $sale[] = $row->EstimatePaymentLists->sum('total_paid');
                //sale

                //discount
                $discount_value = Estimate::where('branch_id', $row->branch_id)->pluck('discount_value');
                $all_discount_value = $discount_value->sum('discount_value');

                $sub_total = Estimate::where('branch_id', $row->branch_id)->pluck('sub_total');
                $all_sub_total = $sub_total->sum('sub_total');

                if (!empty($all_discount_value) && !empty($all_sub_total)) {
                    $discount_value = ($all_discount_value / $all_sub_total) * 100;
                } else {
                    $discount_value = 0;
                }
                $discount[] = $discount_value;
                //discount


                //dues
                $estimate1 = Estimate::where('payment_status', 'PAYMENT DUE')
                    ->where('branch_id', $row->branch_id)
                    ->get();
                $total_dues = $estimate1->sum('dues_amount');
                $dues[] = $total_dues;
                //dues


                //grand_total
                $estimate2 = Estimate::where('branch_id', $row->branch_id)->get();
                $grand_total[] = $estimate2->sum('grand_total');
                //grand_total

            }


            return response()->json([
                'estimate' => $estimate_old,
                'branch_name' => $branch_name,
                'sale' => $sale,
                'discount' => $discount,
                'dues' => $dues,
                'grand_total' => $grand_total,
            ]);
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
    
    
    
        public function getAdminStockListSearch(Request $request)
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

    
}
