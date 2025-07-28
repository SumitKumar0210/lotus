<?php

namespace App\Http\Controllers\WareHouse\Modules\Stock;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockQty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ConsolidatedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.warehouse.modules.reports.consolidate-report');
    }


    public function getConsolidateList(Request $request)
    {
        if ($request->ajax()) {

            //check zero stock data first so we will eliminate zero stock data
            // $usersQuery = Stock::query();
            // if (!empty($_GET["product_id"])) {
            //     $usersQuery
            //         ->where('reason', 'PURCHASE')
            //         ->where('product_id', $_GET["product_id"]);
            // } else {
            //     $usersQuery
            //         ->where('reason', 'PURCHASE');
            // }
            // $data1 = $usersQuery->latest()
            //     ->groupBy('product_id')
            //     ->get();
            // $branch_id = Auth::user()->branch_id;

            // $stocks_id = [];
            // foreach ($data1 as $row) {
            //     $stock_qty = StockQty::where('branch_id', $branch_id)
            //         ->where('product_id', $row->product_id)
            //         ->first();
            //     if (!empty($stock_qty)) {
            //         if ($stock_qty->qty > 0) {
            //             $stocks_id[] = $row->id;
            //         }
            //     }
            // }
            // //check zero stock data first so we will eliminate zero stock data


            // $usersQuery2 = Stock::query();
            // if (!empty($_GET["product_id"])) {
            //     $usersQuery2
            //         ->where('reason', 'PURCHASE')
            //         ->where('product_id', $_GET["product_id"])
            //         ->whereIn('id', $stocks_id)
            //         ->latest();
            // } else {
            //     $usersQuery2
            //         ->where('reason', 'PURCHASE')
            //         ->whereIn('id', $stocks_id)
            //         ->latest();
            // }
            // $data2 = $usersQuery2->groupBy('product_id');
            // ->get();

            $branch_id = Auth::user()->branch_id;
            $product_id = request()->get('product_id');

            // One query with subquery filter to only include products with positive stock
            $usersQuery = Stock::query()
                ->with(['product.brand', 'product.category'])
                ->where('reason', 'PURCHASE')
                ->when($product_id, function ($q) use ($product_id) {
                    $q->where('product_id', $product_id);
                })
                ->whereIn('product_id', function ($q) use ($branch_id) {
                    $q->select('product_id')
                        ->from('stock_qties')
                        ->where('branch_id', $branch_id)
                        ->where('qty', '>', 0);
                })
                ->groupBy('product_id')
                ->latest();

            return Datatables::eloquent($usersQuery)
                ->addIndexColumn()
                ->addColumn('product_name', function ($row) {
                    return optional($row->product)->product_name;
                })
                ->addColumn('brand_name', function ($row) {
                    return optional($row->product->brand)->brand_name;
                })
                ->addColumn('product_code', function ($row) {
                    return optional($row->product)->product_code;
                })
                ->addColumn('category', function ($row) {
                    return optional($row->product->category)->category_name;
                })
                ->addColumn('color', function ($row) {
                    return optional($row->product)->color_code;
                })
                ->addColumn('size', function ($row) {
                    return optional($row->product)->size;
                })
                // ->addColumn('qty', function ($row) {

                //     $product_id = $row->product_id;
                //     $branch_id = Auth::user()->branch_id;
                //     $stock_qty = StockQty::where('branch_id', $branch_id)
                //         ->where('product_id', $product_id)
                //         ->first();

                //     if (!empty($stock_qty)) {
                //         return $stock_qty->qty;
                //     } else {
                //         return 0;
                //     }
                // })
                ->addColumn('qty', function ($row) use ($branch_id) {
                    $stock_qty = StockQty::where('branch_id', $branch_id)
                        ->where('product_id', $row->product_id)
                        ->first();

                    return $stock_qty ? $stock_qty->qty : 0;
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d-F-Y');
                })
                ->filterColumn('product_name', function ($query, $keyword) {
                        $query->whereHas('product', function ($q) use ($keyword) {
                            $q->where('product_name', 'like', "%$keyword%");
                        });
                    })
                    ->filterColumn('brand_name', function ($query, $keyword) {
                        $query->whereHas('product.brand', function ($q) use ($keyword) {
                            $q->where('brand_name', 'like', "%$keyword%");
                        });
                    })
                    ->filterColumn('product_code', function ($query, $keyword) {
                        $query->whereHas('product', function ($q) use ($keyword) {
                            $q->where('product_code', 'like', "%$keyword%");
                        });
                    })
                    ->filterColumn('category', function ($query, $keyword) {
                        $query->whereHas('product.category', function ($q) use ($keyword) {
                            $q->where('category_name', 'like', "%$keyword%");
                        });
                    })
                    ->filterColumn('color', function ($query, $keyword) {
                        $query->whereHas('product', function ($q) use ($keyword) {
                            $q->where('color_code', 'like', "%$keyword%");
                        });
                    })
                    ->filterColumn('size', function ($query, $keyword) {
                        $query->whereHas('product', function ($q) use ($keyword) {
                            $q->where('size', 'like', "%$keyword%");
                        });
                    })
                    ->filterColumn('created_at', function ($query, $keyword) {
                        $query->whereDate('created_at', date('Y-m-d', strtotime($keyword)));
                    })
                ->make(true);
                }

    }


    public function getConsolidateListSearch(Request $request)
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
