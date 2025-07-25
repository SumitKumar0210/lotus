<?php

namespace App\Http\Controllers\Branch\Modules\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BranchLastPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.branch.modules.purchase.last-purchase');
    }


    public function getBranchLastPurchaseList(Request $request)
    {
        if ($request->ajax()) {

            $last_stock = Stock::where('reason', 'PURCHASE')
                ->where('type', 'WAREHOUSE STOCK')
                ->where('branch_in', Auth::user()->branch_id)
                ->orderBy('created_at', 'DESC')->first();

            $usersQuery = Stock::query();


            if (!empty($last_stock)) {
                $usersQuery->where('purchase_no', $last_stock->purchase_no)
                    ->where('reason', 'PURCHASE')
                    ->where('type', 'WAREHOUSE STOCK')
                    ->where('branch_in', Auth::user()->branch_id)
                    ->latest();
            } else {
                $usersQuery->where('reason', 'PURCHASE')
                    ->where('type', 'WAREHOUSE STOCK')
                    ->where('branch_in', Auth::user()->branch_id)
                    ->latest();
            }


            $data = $usersQuery->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('product_name', function ($row) {
                    return $row->product->product_name;
                })
                ->addColumn('brand_name', function ($row) {
                    return $row->product->brand->brand_name;
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
                    return $row->qty;
                })
                ->addColumn('date', function ($row) {
                    return $row->date;
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
