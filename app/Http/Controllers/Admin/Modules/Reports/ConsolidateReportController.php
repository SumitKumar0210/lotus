<?php

namespace App\Http\Controllers\Admin\Modules\Reports;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockQty;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ConsolidateReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('backend.admin.modules.reports.consolidate-report', compact('products'));
    }


    public function getConsolidateReportList(Request $request)
    {
        if ($request->ajax()) {


            //check zero stock data first so we will eliminate zero stock data
            // $usersQuery = Stock::query();
            // if (!empty($_GET["product_id"])) {
            //     $usersQuery
            //         ->where('product_id', $_GET["product_id"])
            //         ->latest();
            // } else {
            //     $usersQuery->latest();
            // }
            // $data1 = $usersQuery->groupBy('product_id')->get();

            // $stocks_id = [];
            // foreach ($data1 as $row) {
            //     $stock_qty = StockQty::where('product_id', $row->product_id)
            //         ->get();

            //     if (!empty($stock_qty)) {
            //         foreach ($stock_qty as $row2) {

            //             if ($row2->qty > 0) {
            //                 $stocks_id[] = $row->id;
            //             }
            //         }
            //     }
            // }
            // //check zero stock data first so we will eliminate zero stock data


            // $usersQuery2 = Stock::query();
            // if (!empty($_GET["product_id"])) {
            //     $usersQuery2
            //         ->where('product_id', $_GET["product_id"])
            //         ->whereIn('id', $stocks_id)
            //         ->latest();
            // } else {
            //     $usersQuery2
            //         ->whereIn('id', $stocks_id)
            //         ->latest();
            // }

            // $data2 = $usersQuery2->groupBy('product_id')->get();

            // return Datatables::of($data2)
            //     ->addIndexColumn()
            //     ->addColumn('brand_name', function ($row) {
            //         return $row->product->brand->brand_name;
            //     })
            //     ->addColumn('product_name', function ($row) {
            //         return $row->product->product_name;
            //     })
            //     ->addColumn('product_code', function ($row) {
            //         return $row->product->product_code;
            //     })
            //     ->addColumn('category', function ($row) {
            //         return $row->product->category->category_name;
            //     })
            //     ->addColumn('color', function ($row) {
            //         return $row->product->color_code;
            //     })
            //     ->addColumn('size', function ($row) {
            //         return $row->product->size;
            //     })
            //     ->addColumn('qty', function ($row) {

            //         $product_id = $row->product_id;
            //         $stock_qty = StockQty::where('product_id', $product_id)->get();
            //         return $stock_qty->sum('qty');
            //     })
            //     ->make(true);




            //check zero stock data first so we will eliminate zero stock data
            $productIdsWithStock = StockQty::select('product_id')
                ->groupBy('product_id')
                ->havingRaw('SUM(qty) > 0');

            if (!empty($_GET["product_id"])) {
                $productIdsWithStock->where('product_id', $_GET["product_id"]);
            }

            $productIds = $productIdsWithStock->pluck('product_id')->toArray();

            // Step 2: Main stock query
            $dataQuery = Stock::query()
                ->with(['product.brand', 'product.category'])
                ->whereIn('product_id', $productIds)
                ->when(!empty($_GET["product_id"]), fn($q) => $q->where('product_id', $_GET["product_id"]))
                ->groupBy('product_id')
                ->latest('id'); // Use appropriate sort column

            return DataTables::eloquent($dataQuery)
                ->addIndexColumn()

                ->addColumn('brand_name', fn($row) => $row->product->brand->brand_name ?? '')
                ->addColumn('product_name', fn($row) => $row->product->product_name ?? '')
                ->addColumn('product_code', fn($row) => $row->product->product_code ?? '')
                ->addColumn('category', fn($row) => $row->product->category->category_name ?? '')
                ->addColumn('color', fn($row) => $row->product->color_code ?? '')
                ->addColumn('size', fn($row) => $row->product->size ?? '')

                ->addColumn('qty', function ($row) {
                    return StockQty::where('product_id', $row->product_id)->sum('qty');
                })

                // Optional: enable search on related fields
                ->filterColumn('brand_name', function ($query, $keyword) {
                    $query->whereHas('product.brand', fn($q) => $q->where('brand_name', 'like', "%$keyword%"));
                })
                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('product', fn($q) => $q->where('product_name', 'like', "%$keyword%"));
                })
                ->filterColumn('color', function ($query, $keyword) {
                    $query->whereHas('product', fn($q) => $q->where('color_code', 'like', "%$keyword%"));
                })
                ->filterColumn('product_code', function ($query, $keyword) {
                    $query->whereHas('product', fn($q) => $q->where('product_code', 'like', "%$keyword%"));
                })
                ->filterColumn('qty', function ($query, $keyword) {
                    $productIds = StockQty::select('product_id')
                        ->groupBy('product_id')
                        ->havingRaw('SUM(qty) LIKE ?', ["%{$keyword}%"])
                        ->pluck('product_id');

                    $query->whereIn('product_id', $productIds);
                })

                ->filterColumn('category', function ($query, $keyword) {
                    $query->whereHas('product.category', fn($q) => $q->where('category_name', 'like', "%$keyword%"));
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
