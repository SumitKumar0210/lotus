<?php

namespace App\Http\Controllers\Branch\Modules\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BranchAllPurchaseController extends Controller
{

    public function index()
    {
        return view('backend.branch.modules.purchase.all-purchase');
    }


    public function getBranchAllPurchaseList(Request $request)
    {
        if ($request->ajax()) {

            $usersQuery = Stock::query();

            $usersQuery->where('reason', 'PURCHASE')
                ->where('type', 'WAREHOUSE STOCK')
                ->where('branch_in', Auth::user()->branch_id)
                ->latest();

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
}
