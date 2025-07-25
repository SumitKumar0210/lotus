<?php

namespace App\Http\Controllers\Branch\Modules\Reports;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockQty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ConsolidatedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.branch.modules.reports.consolidate-report');
    }


  public function getConsolidateList(Request $request)
    {
        if ($request->ajax()) {


            //check zero stock data first so we will eliminate zero stock data
            $usersQuery = Stock::query();
            if (!empty($_GET["product_id"])) {
                $usersQuery
                    ->where('branch_in', Auth::user()->branch_id)
                    ->where('product_id', $_GET["product_id"]);
            } else {
                $usersQuery
                    ->where('branch_in', Auth::user()->branch_id);
            }
            $data1 = $usersQuery->groupBy('product_id')->get();

            $stocks_id = [];
            foreach ($data1 as $row) {
                $stock_qty = StockQty::where('branch_id', Auth::user()->branch_id)
                    ->where('product_id', $row->product_id)
                    ->first();
                if (!empty($stock_qty)) {
                    if ($stock_qty->qty > 0) {
                        $stocks_id[] = $row->id;
                    }
                }
            }
            //check zero stock data first so we will eliminate zero stock data



            $usersQuery2 = Stock::query();
            if (!empty($_GET["product_id"])) {
                $usersQuery2
                    ->where('branch_in', Auth::user()->branch_id)
                    ->where('product_id', $_GET["product_id"])
                    ->whereIn('id', $stocks_id)
                    ->latest();
            } else {
                $usersQuery2
                    ->where('branch_in', Auth::user()->branch_id)
                    ->whereIn('id', $stocks_id)
                    ->latest();
            }
            $data = $usersQuery2->groupBy('product_id')->get();










            // $usersQuery = Stock::query();
            // if (!empty($_GET["product_id"])) {
            //     $usersQuery
            //         ->where('branch_in', Auth::user()->branch_id)
            //         ->where('product_id', $_GET["product_id"])
            //         ->latest();
            // } else {
            //     $usersQuery
            //         ->where('branch_in', Auth::user()->branch_id)
            //         ->latest();
            // }
            // $data = $usersQuery->groupBy('product_id')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('brand_name', function ($row) {
                    return $row->product->brand->brand_name;
                })
                ->addColumn('product_name', function ($row) {
                    return $row->product->product_name;
                })
                ->addColumn('product_code', function ($row) {
                    return $row->product->product_code;
                })
                ->addColumn('category', function ($row) {
                    return $row->product->category->category_name;
                })
                ->addColumn('color', function ($row) {
                    return $row->product->color_code;
                })
                ->addColumn('size', function ($row) {
                    return $row->product->size;
                })
                ->addColumn('qty', function ($row) {

                    $product_id = $row->product_id;
                    $branch_id = Auth::user()->branch_id;
                    $stock_qty = StockQty::where('branch_id', $branch_id)
                        ->where('product_id', $product_id)
                        ->first();

                    if (!empty($stock_qty)) {
                        return $stock_qty->qty;
                    } else {
                        return 0;
                    }
                })
                ->make(true);
        }
    }




    public function getConsolidateListSearch(Request $request)
    {
//        $stocks = [];
//        if ($request->has('q')) {
//            $search = $request->q;
//            $stocks = Product::select("id", "product_name")
//                ->where('product_name', 'LIKE', "%$search%")
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
