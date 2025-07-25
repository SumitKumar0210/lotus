<?php

namespace App\Http\Controllers\Admin\Modules\Reports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockQty;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LowInventoryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('backend.admin.modules.reports.low-inventory', compact('products'));
    }


    public function getLowInventoryReportList(Request $request)
    {

        if ($request->ajax()) {

            $stock_qty_product_ids = StockQty::groupBy('product_id')
                ->selectRaw('sum(qty) as sum, product_id')
                ->pluck('sum', 'product_id');

            $product_id = [];
            $minimum_stock_quantity = [];
            foreach ($stock_qty_product_ids as $key => $stock_qty_product_id) {
                $product_id[] = $key;
                $minimum_stock_quantity[] = $stock_qty_product_id;
            }

            $products = Product::whereIn('id', $product_id)->get();
            $product_id_new = [];
            foreach ($products as $key => $product) {
                if ($product->minimum_stock_quantity >= $minimum_stock_quantity[$key]) {
                    $product_id_new[] = $product->id;
                }
            }

            if (!empty($_GET["product_id"])) {
                $data = Product::whereIn('id', $product_id_new)
                    ->where('id', $_GET["product_id"])
                    ->get();
            } else {
                $data = Product::whereIn('id', $product_id_new)
                    ->get();
            }


            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('product_name', function ($row) {
                    return $row->product_name;
                })
                ->addColumn('model_no', function ($row) {
                    return $row->product_code;
                })
                ->addColumn('category', function ($row) {
                    return $row->category->category_name;
                })
                ->addColumn('color', function ($row) {
                    return $row->color_code;
                })
                ->addColumn('size', function ($row) {
                    return $row->size;
                })
                ->addColumn('qty', function ($row) {
                    $warehouse = Branch::where('type', 'WAREHOUSE')->first();
                    $stock_qty = StockQty::where('product_id', $row->id)
                        ->where('branch_id', $warehouse->id)
                        ->first();
                    if(!empty($stock_qty)){
                        return $stock_qty->qty;
                    }else{
                        return 0;
                    }
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
