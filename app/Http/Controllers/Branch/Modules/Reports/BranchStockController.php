<?php

namespace App\Http\Controllers\Branch\Modules\Reports;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockQty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BranchStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.branch.modules.reports.branch-stock-report');
    }

    public function getBranchStockList(Request $request)
    {

        //2021-01-14 16:29:19
        if ($request->ajax()) {
            $usersQuery = Stock::query()
                ->with(['Product:id,product_name,product_code,color_code,size', 
                'Product.category:id,category_name', 
                'branchReturnUser:id,name',
                'branchUserOut:id,name'])
                ->where('status', 'IN STOCK')
                ->where('branch_in', Auth::user()->branch_id);
            // if (!empty($_GET["date_from"]) && !empty($_GET["date_to"]) && !empty($_GET["product_id"])) {

            //     $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
            //     $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');

            //     //2021-01-14 00:00:00
            //     //2021-01-14 23:59:59

            //     $usersQuery
            //         ->where('status', 'IN STOCK')
            //         ->where('branch_in', Auth::user()->branch_id)
            //         ->where('product_id', $_GET["product_id"])
            //         ->whereBetween('date', [$date_from, $date_to])
            //         ->latest();
            // } else {
            //     $usersQuery->where('status', 'IN STOCK')
            //         ->where('branch_in', Auth::user()->branch_id)
            //         ->latest();
            // }

            if($request->product_id) {
                $usersQuery->where('product_id', $request->product_id);
            }
            if ($request->date_from && $request->date_to) {
                $date_from = Carbon::parse($request->date_from)->format('Y-m-d 00:00:00');
                $date_to = Carbon::parse($request->date_to)->format('Y-m-d 23:59:59');

                $usersQuery->whereBetween('created_at', [$date_from, $date_to]);
            }


            $data = $usersQuery->groupBy('product_id');
            // ->get();
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('brand_name', function ($row) {
                    return optional($row->product->brand)->brand_name ?? '';
                })

                ->addColumn('product_name', function ($row) {
                    return optional($row->product)->product_name ?? '';
                })
                ->addColumn('product_code', function ($row) {
                    return optional($row->product)->product_code ?? '';
                })
                ->addColumn('category', function ($row) {
                    return optional($row->product->category)->category_name ?? '';
                })
                ->addColumn('color', function ($row) {
                    return optional($row->product)->color_code ?? '';
                })
                ->addColumn('size', function ($row) {
                    return optional($row->product)->size ?? '';
                })
                ->addColumn('opening_qty', function ($row) {

                    $branch_id = Auth::user()->branch_id;
                    $product_id = $row->product_id;

                    $stock_qty = StockQty::where('branch_id', $branch_id)
                        ->where('product_id', $product_id)
                        ->first();

                    if (!empty($stock_qty)) {
                        $product_starting_date = Carbon::parse($stock_qty->created_at)->startOfDay();
                        $product_previous_day = Carbon::now()->subDays('1')->endOfDay();



                        $stock_qty = StockQty::where('branch_id', $branch_id)
                            ->where('product_id', $product_id)
                            ->whereBetween('created_at', [$product_starting_date, $product_previous_day])
                            ->get();

                        $stock_qty = $stock_qty->sum('qty');
                        return $stock_qty;
                    } else {
                        return 0;
                    }
                })
                ->addColumn('closing_qty', function ($row) {

                    $branch_id = Auth::user()->branch_id;
                    $product_id = $row->product_id;

                    $stock_qty = StockQty::where('branch_id', $branch_id)
                        ->where('product_id', $product_id)
                        ->first();

                    if (!empty($stock_qty)) {
                        $stock_qty = $stock_qty->qty;
                        return $stock_qty;
                    } else {
                        return 0;
                    }


                })
                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('product_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('product_code', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('product_code', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('category', function ($query, $keyword) {
                    $query->whereHas('Product.category', function ($q) use ($keyword) {
                        $q->where('category_name', 'like', "%{$keyword}%");
                    });
                })  
                ->filterColumn('color', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('color_code', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('size', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('size', 'like', "%{$keyword}%");
                    });
                })
                // ->filterColumn('opening_qty', function ($query, $order) {
                //     $query->whereHas('StockQty', function ($q) use ($order) {
                //         $q->where('qty', 'like', "%{$order}%");
                //     });
                // })
                // ->filterColumn('closing_qty', function ($query, $order) {
                //     $query->whereHas('StockQty', function ($q) use ($order) {
                //         $q->where('qty', 'like', "%{$order}%");
                //     });
                // })
                ->make(true);
        }
    }


    public function getBranchStockListSearch(Request $request)
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
                ->orwhere('product_name_search', 'like', '%' . $id . '%')
                ->get();
            $product_code = [];
            foreach ($products as $p) {
                $product_code[] = $p->product_code;
            }
            $stocks = Product::whereIn('product_code', $product_code)->get();
        }
        return response()->json($stocks);
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
