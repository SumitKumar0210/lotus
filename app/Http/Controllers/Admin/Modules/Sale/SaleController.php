<?php

namespace App\Http\Controllers\Admin\Modules\Sale;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Estimate;
use App\Models\EstimateProductList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $branches = Branch::latest()->get();
        return view('backend.admin.modules.sale.sale-list', compact('branches'));
    }





    // public function getSaleList(Request $request)
    // {
    //     if ($request->ajax()) {



    //         $usersQuery2 = EstimateProductList::query();

    //         if (!empty($_GET["date_from"]) && !empty($_GET["date_to"]) && !empty($_GET["branch_id"])) {
    //             $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
    //             $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');

    //             $estimate = Estimate::
    //                 //where('payment_status', 'PAYMENT DONE')
    //                 where('branch_id', $_GET["branch_id"])
    //                 ->latest()
    //                 ->pluck('id');

    //             $usersQuery2->where('delivery_status', 'DELIVERED')
    //                 ->whereIn('estimate_id', $estimate)
    //                 ->whereBetween('updated_at', [$date_from, $date_to]);



    //             //extra code*****************************************************
    //             $usersQuery3 = Estimate::query();
    //             $data3 = $usersQuery3->whereBetween('updated_at', [$date_from, $date_to])
    //                 ->where('branch_id', $_GET["branch_id"])
    //                 ->get();
    //             $total_sale = $data3->sum('grand_total');


    //             $usersQuery4 = Estimate::query();
    //             $data4 = $usersQuery4->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
    //                 ->whereBetween('updated_at', [$date_from, $date_to])
    //                 ->where('branch_id', $_GET["branch_id"])
    //                 ->get();
    //             $total_discount_value = $data4->sum('discount_value');


    //             $usersQuery5 = Estimate::query();
    //             $data5 = $usersQuery5->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
    //                 ->whereBetween('updated_at', [$date_from, $date_to])
    //                 ->where('branch_id', $_GET["branch_id"])
    //                 ->get();
    //             $total_discount_percent = ($total_discount_value / $total_sale) * 100;



    //             $usersQuery6 = Estimate::query();
    //             $data6 = $usersQuery6->where('estimate_status', 'ESTIMATE CANCELLED')
    //                 ->whereBetween('updated_at', [$date_from, $date_to])
    //                 ->where('branch_id', $_GET["branch_id"])
    //                 ->get();
    //             $total_cancelled_bill = count($data6);
    //             //extra code*****************************************************




    //         } else {

    //             $estimate = Estimate::where('payment_status', 'PAYMENT DONE')
    //                 ->latest()
    //                 ->pluck('id');

    //             $usersQuery2->where('delivery_status', 'DELIVERED')
    //                 ->whereIn('estimate_id', $estimate);


    //             //extra code*****************************************************
    //             $usersQuery3 = Estimate::query();
    //             $data3 = $usersQuery3->get();
    //             $total_sale = $data3->sum('grand_total');


    //             $usersQuery4 = Estimate::query();
    //             $data4 = $usersQuery4->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
    //                 ->get();
    //             $total_discount_value = $data4->sum('discount_value');


    //             $usersQuery5 = Estimate::query();
    //             $data5 = $usersQuery5->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
    //                 ->get();

    //             $total_discount_percent = ($total_discount_value / $total_sale) * 100;


    //             $usersQuery6 = Estimate::query();
    //             $data6 = $usersQuery6->where('estimate_status', 'ESTIMATE CANCELLED')
    //                 ->get();
    //             $total_cancelled_bill = count($data6);
    //             //extra code*****************************************************


    //         }

    //         $data = $usersQuery2->groupBy('estimate_id')->latest()->get();

    //         return Datatables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('estimate_no', function ($row) {
    //                 return $row->Estimate->estimate_no;
    //             })
    //             ->addColumn('branch_name', function ($row) {
    //                 return $row->Estimate->user->branch->branch_name;
    //             })
    //             ->addColumn('date', function ($row) {
    //                 return Carbon::parse($row->updated_at)->format('m-d-Y');
    //             })
    //             ->addColumn('client_name_and_mobile', function ($row) {
    //                 return $row->Estimate->client_name . ' ' . $row->Estimate->client_mobile;
    //             })
    //             ->addColumn('discount', function ($row) {
    //                 return $row->Estimate->discount_value;
    //             })
    //             ->addColumn('grand_total', function ($row) {
    //                 return $row->Estimate->grand_total;
    //             })
    //             ->addColumn('dues_amount', function ($row) {
    //                 return $row->Estimate->dues_amount;
    //             })
    //             ->addColumn('action', function ($row) {
    //                 return '<nav class="nav">
    //                         <div class="dropdown-menu dropdown-menu-right shadow">
    //                             <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('admin.sale.printSale', $row->estimate_id) . '" target="_blank"><i class="far fa fa-print text-primary"></i> Print</a>
    //                         </div>
    //                         <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
    //                     </nav>';
    //             })
    //             ->rawColumns(['action'])
    //             ->with([
    //                 'total_sale' => $total_sale,
    //                 'total_discount_value' => $total_discount_value,
    //                 'total_discount_percent' => $total_discount_percent,
    //                 'total_cancelled_bill' => $total_cancelled_bill,
    //             ])
    //             ->make(true);
    //     }
    // }













    public function getSaleList(Request $request)
    {
      
        if ($request->ajax()) {

            $usersQuery2 = Estimate::query();

            if (!empty($_GET["date_from"]) && !empty($_GET["date_to"]) && !empty($_GET["branch_id"])) {

                $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
                $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');

                $usersQuery2->where('branch_id', $_GET["branch_id"])
                    ->whereBetween('created_at', [$date_from, $date_to]);


                //extra code*****************************************************
                $usersQuery3 = Estimate::query();
                $data3 = $usersQuery3->whereBetween('updated_at', [$date_from, $date_to])
                    ->where('branch_id', $_GET["branch_id"])
                    ->get();
                $total_sale = $data3->sum('grand_total');


                $usersQuery4 = Estimate::query();
                $data4 = $usersQuery4->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                    ->whereBetween('updated_at', [$date_from, $date_to])
                    ->where('branch_id', $_GET["branch_id"])
                    ->get();
                $total_discount_value = $data4->sum('discount_value');

                

                $usersQuery5 = Estimate::query();
                // $data5 = $usersQuery5->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                //     ->whereBetween('updated_at', [$date_from, $date_to])
                //     ->where('branch_id', $_GET["branch_id"])
                //     ->get();
                // $total_discount_percent = ($total_discount_value / $total_sale) * 100;
                
                $total_discount_percent = $total_sale != 0
                    ? ($total_discount_value / $total_sale) * 100
                    : 0;
                



                $usersQuery6 = Estimate::query();
                $data6 = $usersQuery6->where('estimate_status', 'ESTIMATE CANCELLED')
                    ->whereBetween('updated_at', [$date_from, $date_to])
                    ->where('branch_id', $_GET["branch_id"])
                    ->get();
                $total_cancelled_bill = count($data6);
                //extra code*****************************************************




            } else {


                //extra code*****************************************************
                $usersQuery3 = Estimate::query();
                $data3 = $usersQuery3->get();
                $total_sale = $data3->sum('grand_total');


                $usersQuery4 = Estimate::query();
                $data4 = $usersQuery4->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                    ->get();
                $total_discount_value = $data4->sum('discount_value');


                $usersQuery5 = Estimate::query();
                $data5 = $usersQuery5->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                    ->get();

                $total_discount_percent = ($total_discount_value / $total_sale) * 100;


                $usersQuery6 = Estimate::query();
                $data6 = $usersQuery6->where('estimate_status', 'ESTIMATE CANCELLED')
                    ->get();
                $total_cancelled_bill = count($data6);
                //extra code*****************************************************

            }

            

            $data = $usersQuery2->latest();
            // ->get();
          

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('estimate_no', function ($row) {
                    return $row->estimate_no;
                })
                ->addColumn('branch_name', function ($row) {
                    return optional($row->user?->branch)->branch_name ?? '';
                })


                ->addColumn('status', function ($row) {
                    if($row->estimate_status == "ESTIMATE CREATED"){
                    
                        if($row->delivery_status == "DELIVERED"){
                                
                        $EstimateProductListsCount =  EstimateProductList::where('estimate_id',$row->id)
                        ->where('is_sale_returned' ,'ITEM SALE RETURNED')
                        ->count();

                        if($EstimateProductListsCount > 0){
                                return  "ITEM SALE RETURNED";
                            }else{
                                return "DELIVERED";
                            }
                        }
                        elseif($row->deliver_status == "NOT DELIVERED"){
                            return $row->deliver_status;
                        }
                        else{
                            
                            // return "DELIVERED";
                            return $row->estimate_status;

                        }

                    }
                    
                    elseif($row->estimate_status == "ESTIMATE CANCELLED"){
                            return $row->estimate_status;
                    }else{
                        return null;
                    }
                })




                // /////////////////////////////////////////
                ->addColumn('date', function ($row) {
                    return Carbon::parse($row->updated_at)->format('m-d-Y');
                })
                ->addColumn('client_name_and_mobile', function ($row) {
                    return $row->client_name . ' ' . $row->client_mobile;
                })
                ->addColumn('discount', function ($row) {
                    return $row->discount_value;
                })
                ->addColumn('grand_total', function ($row) {
                    return $row->grand_total;
                })
                ->addColumn('dues_amount', function ($row) {
                    return $row->dues_amount;
                })
 
                ->addColumn('product', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                    
                       //$options .= '<li>' . $EstimateProductList->product_code . '<br>' .$EstimateProductList->Product->category_details->category_name.

                       $options .= '<li>' . $EstimateProductList->product_code . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                    //return 123;
                })


                ->addColumn('qty', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->qty . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })

                ->addColumn('mrp', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->mrp . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })

                ->addColumn('amount', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->amount . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })

                ->addColumn('action', function ($row) {
                    return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('admin.sale.printSale', $row->id) . '" target="_blank"><i class="far fa fa-print text-primary"></i> Print</a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                })
                ->rawColumns(['action', 'product', 'mrp', 'amount','qty'])
                ->with([
                    'total_sale' => $total_sale,
                    'total_discount_value' => $total_discount_value,
                    'total_discount_percent' => $total_discount_percent,
                    'total_cancelled_bill' => $total_cancelled_bill,
                ])
                ->filterColumn('estimate_status', function ($query, $keyword) {
                    $query->where('estimate_status', 'like', "%{$keyword}%");
                })
                ->filterColumn('delivery_status', function ($query, $keyword) {
                    $query->where('delivery_status', 'like', "%{$keyword}%");
                })
                ->filterColumn('client_name_and_mobile', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('client_name', 'like', "%{$keyword}%")
                          ->orWhere('client_mobile', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('estimate_no', function ($query, $keyword) {
                    $query->where('estimate_no', 'like', "%{$keyword}%");
                })
                ->filterColumn('branch_name', function ($query, $keyword) {
                    $query->whereHas('user.branch', function ($q) use ($keyword) {
                        $q->where('branch_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('date', function ($query, $keyword) {
                    $date = date('Y-m-d', strtotime($keyword)); 
                    if ($date && strtotime($keyword)) {
                        $query->whereDate('updated_at', $date);
                    }
                })
                ->filterColumn('discount', function ($query, $keyword) {
                    $query->where('discount_value', 'like', "%{$keyword}%");
                })
                ->filterColumn('grand_total', function ($query, $keyword) {
                    $query->where('grand_total', 'like', "%{$keyword}%");
                })
                // ->filterColumn('dues_amount', function ($query, $keyword) {
                //     $query->where('dues_amount', 'like', "%{$keyword}%");
                // })
                ->make(true);
        }
    }















    public function printSale(Request $request, $id)
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

        $current_branch = Branch::where('id', $estimate->branch_id)->first();
        return view('backend.admin.modules.sale.sale-print', compact('estimate', 'paid_in_cash', 'paid_in_bank', 'total_paid', 'current_branch'));
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
}
