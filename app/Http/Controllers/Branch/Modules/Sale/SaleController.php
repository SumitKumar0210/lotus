<?php

namespace App\Http\Controllers\Branch\Modules\Sale;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Estimate;
use App\Models\EstimateProductList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.branch.modules.sale.sale-list');
    }



    // public function getSaleList(Request $request)
    // {
    //     if ($request->ajax()) {



    //         $usersQuery2 = EstimateProductList::query();

    //         if (!empty($_GET["date_from"]) && !empty($_GET["date_to"])) {

    //             $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
    //             $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');

    //             $estimate = Estimate::
    //                 //where('payment_status', 'PAYMENT DONE')
    //                 where('branch_id', Auth::user()->branch->id)
    //                 ->latest()
    //                 ->pluck('id');

    //             $usersQuery2->where('delivery_status', 'DELIVERED')
    //                 ->whereIn('estimate_id', $estimate)
    //                 ->whereBetween('updated_at', [$date_from, $date_to]);
    //         } else {

    //             $estimate = Estimate::where('payment_status', 'PAYMENT DONE')
    //                 ->where('branch_id', Auth::user()->branch->id)
    //                 ->latest()
    //                 ->pluck('id');

    //             $usersQuery2->where('delivery_status', 'DELIVERED')
    //                 ->whereIn('estimate_id', $estimate);
    //         }

    //         $data = $usersQuery2->latest()->get();

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

    //                         <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('branch.sale.printSale', $row->estimate_id) . '" target="_blank"><i class="far fa fa-print text-primary"></i> Print</a>


    //                         </div>
    //                         <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
    //                     </nav>';
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }
    // }








    public function getSaleList(Request $request)
    {
        if ($request->ajax()) {



            $usersQuery2 = Estimate::query();
            $branch_id = Auth::user()->branch_id;
            
            $usersQuery2->where('branch_id', $branch_id);
            
            
            if (!empty($_GET["date_from"]) && !empty($_GET["date_to"])) {

                $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
                $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');

                $usersQuery2->whereBetween('updated_at', [$date_from, $date_to]);
            }

            $data = $usersQuery2->latest()->get();

            return Datatables::of($data)


                ->addIndexColumn()
                ->addColumn('estimate_no', function ($row) {
                    return $row->estimate_no;
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->user->branch->branch_name;
                })
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

                // ->addColumn('product_name', function ($row) {
                //     $options = '<ul>';
                //     foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                //         $options .= '<li>' . $EstimateProductList->product_name . '</li>';
                //     }
                //     $options .= '</ul>';
                //     return $options;
                // })


                ->addColumn('product', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $category_name = $EstimateProductList->Product->category->category_name ?? '';
                    //    $options .= '<li>' . $EstimateProductList->Product_id->category_name . ' '. $EstimateProductList->product_code .  '</li>';
                       $options .= '<li>' . $EstimateProductList->product_code .' ' .$category_name.  '</li>';
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
                            <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('branch.sale.printSale', $row->id) . '" target="_blank"><i class="far fa fa-print text-primary"></i> Print</a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                })
                ->rawColumns(['action', 'product', 'mrp', 'amount','qty'])
                ->make(true);
        }
    }





    //
    //    public function getSaleList(Request $request)
    //    {
    //        if ($request->ajax()) {
    //
    //            $estimate = Estimate::where('estimate_status', 'ESTIMATE CREATED')
    //                ->where('branch_id', Auth::user()->branch->id)
    //                ->latest()
    //                ->pluck('id');
    //
    //            $data = EstimateProductList::whereIn('estimate_id', $estimate)
    //                ->latest()
    //                ->get();
    //
    //            return Datatables::of($data)
    //                ->addIndexColumn()
    //                ->addColumn('estimate_no', function ($row) {
    //                    return $row->Estimate->estimate_no;
    //                })
    //                ->addColumn('branch_name', function ($row) {
    //                    return $row->Estimate->user->branch->branch_name;
    //                })
    //                ->addColumn('client_name', function ($row) {
    //                    return $row->Estimate->client_name;
    //                })
    //                ->addColumn('client_mobile', function ($row) {
    //                    return $row->Estimate->client_mobile;
    //                })
    //                ->addColumn('client_address', function ($row) {
    //                    return $row->Estimate->client_address;
    //                })
    //                ->addColumn('delivery_date', function ($row) {
    //                    return $row->delivery_date;
    //                })
    //                ->addColumn('product_name', function ($row) {
    //                    return $row->product_name;
    //                })
    //                ->addColumn('product_code', function ($row) {
    //                    return $row->product_code;
    //                })
    //                ->addColumn('color', function ($row) {
    //                    return $row->color;
    //                })
    //                ->addColumn('size', function ($row) {
    //                    return $row->size;
    //                })
    //                ->addColumn('quantity', function ($row) {
    //                    return $row->qty;
    //                })
    //                ->addColumn('sale_returned_date', function ($row) {
    //                    return $row->updated_at;
    //                })
    //                ->addColumn('sale_returned_qty', function ($row) {
    //                    return $row->sale_returned_qty;
    //                })
    //                ->addColumn('action', function ($row) {
    //
    //                    return '<nav class="nav">
    //                            <div class="dropdown-menu dropdown-menu-right shadow">
    //                                <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('branch.sale.printSale', $row->estimate_id) . '" target="_blank"><i class="far fa fa-print text-primary"></i> Print</a>
    //                            </div>
    //                            <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
    //                        </nav>';
    //                })
    //                ->rawColumns(['action'])
    //                ->make(true);
    //        }
    //    }


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

        $current_branch = Branch::where('id', Auth::user()->branch_id)->first();
        return view('backend.branch.modules.sale.sale-print', compact('estimate', 'paid_in_cash', 'paid_in_bank', 'total_paid', 'current_branch'));
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
