<?php

namespace App\Http\Controllers\Admin\Modules\Reports;

use App\Http\Controllers\Controller;
use App\Models\EstimateProductList;
use App\Models\Product;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductWiseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('backend.admin.modules.reports.product-wise-report', compact('products'));
    }


    public function getProductWiseReportList(Request $request)
    {
        if ($request->ajax()) {

            $usersQuery = Stock::query();

            if (!empty($_GET["date_from"]) && !empty($_GET["date_to"]) && !empty($_GET["product_id"])) {

                $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
                $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');
                $usersQuery->where('product_id', $_GET["product_id"])
                    ->whereBetween('updated_at', [$date_from, $date_to]);
                $data = $usersQuery->latest()->get();
            } else if (!empty($_GET["date_from"]) && !empty($_GET["date_to"])) {

                $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
                $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');
                $usersQuery->whereBetween('updated_at', [$date_from, $date_to]);
                $data = $usersQuery->latest()->get();
            } else {

                $data = [];
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return Carbon::parse($row->date)->format('d-m-Y');
                })
                ->addColumn('category', function ($row) {
                    return $row->Product->category->category_name ?? '';
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->Estimate->user->branch->branch_name ?? '';
                })
                ->addColumn('brand_name', function ($row) {
                    return $row->Product->brand->brand_name ?? '';
                })
                ->addColumn('product_name', function ($row) {
                    return $row->Product->product_name ?? '';
                })
                ->addColumn('product_code', function ($row) {
                    return $row->Product->product_code ?? '';
                })
                ->addColumn('branch_in', function ($row) {
                    return $row->branchTo->branch_name ?? '';
                })
                ->addColumn('branch_out', function ($row) {
                    return $row->fromTwo->branch_name ?? '';
                })
                ->addColumn('in', function ($row) {
                    if ($row->status == "OUT STOCK") {
                        return $row->qty;
                    } else {
                        return '';
                    }
                })
                ->addColumn('out', function ($row) {
                    if ($row->status == "IN STOCK") {
                        return $row->qty;
                    } else {
                        return '';
                    }
                })
                ->addColumn('reason', function ($row) {
                    return $row->reason ?? '';
                })

                // ->addColumn('category', function ($row) {
                //     return $row->Product->category->category_name ?? '';
                // })
                // ->addColumn('color', function ($row) {
                //     return $row->Product->color_code ?? '';
                // })
                // ->addColumn('size', function ($row) {
                //     return $row->Product->size ?? '';
                // })
                // ->addColumn('from', function ($row) {
                //     if ($row->reason == 'PURCHASE') {
                //         return 'Admin';
                //     } else {
                //         return $row->fromTwo->branch_name ?? '';
                //     }
                // })
                // ->addColumn('to', function ($row) {
                //     return $row->branchTo->branch_name ?? '';
                // })
                // ->addColumn('returned_qty', function ($row) {
                //     if ($row->reason == 'PURCHASE') {
                //         return '';
                //     } else {
                //         return $row->qty - $row->accepted_qty ?? '';
                //     }
                // })
                // ->addColumn('branch_user_in', function ($row) {
                //     return $row->created_by->name ?? '';
                // })
                // ->addColumn('branch_user_out', function ($row) {
                //     if ($row->reason == 'PURCHASE') {
                //         return '';
                //     } else {
                //         return $row->branchUserOut->name ?? '';
                //     }
                // })
                // ->addColumn('branch_return_user', function ($row) {
                //     if ($row->is_returned == 'RETURNED') {
                //         return $row->branchReturnUser->name ?? '';
                //     } else {
                //         return '';
                //     }
                // })
                // ->addColumn('branch_transfered_return_branch_in', function ($row) {
                //     if ($row->is_returned == 'RETURNED') {
                //         return $row->branch_transfered_return_branch_in->branch_name ?? '';
                //     } else {
                //         return '';
                //     }
                // })
                // ->addColumn('created_at', function ($row) {
                //     $created_at = Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                //     return $created_at;
                // })
                ->make(true);
        }
    }


    public function getEstimateListProductReport(Request $request)
    {
        if ($request->ajax()) {

            $usersQuery = EstimateProductList::query();
            if (!empty($_GET["date_from"]) && !empty($_GET["date_to"]) && !empty($_GET["product_id"])) {

                $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
                $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');
                $usersQuery
                    ->where('product_id', $_GET["product_id"])
                    ->whereBetween('updated_at', [$date_from, $date_to]);
                $data = $usersQuery->latest()->get();
            } else if (!empty($_GET["date_from"]) && !empty($_GET["date_to"])) {

                $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
                $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');
                $usersQuery->whereBetween('updated_at', [$date_from, $date_to]);
                $data = $usersQuery->latest()->get();
            } else {

                $data = [];
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d-m-Y');
                })
                ->addColumn('category', function ($row) {
                    return $row->Product->category->category_name ?? '';
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->Estimate->user->branch->branch_name ?? '';
                })
                ->addColumn('product_name', function ($row) {
                    return $row->Product_name ?? '';
                })
                ->addColumn('product_code', function ($row) {
                    return $row->Product->product_code ?? '';
                })
                ->addColumn('branch_in', function ($row) {
                    return $row->Estimate->user->branch->branch_name ?? '';
                })
                ->addColumn('branch_out', function ($row) {
                    return '';
                })
                ->addColumn('in', function ($row) {
                    return $row->qty;
                })
                ->addColumn('out', function ($row) {
                    $row->EstimateProductDeliveryStatus->sum('qty');
                })
                ->addColumn('brand_name', function ($row) {
                    return $row->Product->brand->brand_name ?? '';
                })
                ->addColumn('reason', function ($row) {
                    return $row->delivery_status ?? '';
                })
                // ->addColumn('color', function ($row) {
                //     return $row->color_code;
                // })
                // ->addColumn('size', function ($row) {
                //     return $row->size;
                // })
                // ->addColumn('quantity', function ($row) {
                //     return $row->qty;
                // })
                // ->addColumn('action', function ($row) {
                // })
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
