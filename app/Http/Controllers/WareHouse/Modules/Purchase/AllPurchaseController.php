<?php

namespace App\Http\Controllers\WareHouse\Modules\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AllPurchaseController extends Controller
{

    public function index()
    {
        return view('backend.warehouse.modules.purchase.all-purchase');
    }


    public function getWarehouseAllPurchaseList(Request $request)
    {
        if ($request->ajax()) {

            $usersQuery = Stock::query();

            $usersQuery->with([
                'product:id,product_name,product_code,color_code,size,brand_id,category_id', 
                'product.brand:id,brand_name', 
                'product.category:id,category_name'])->where('reason', 'PURCHASE')
                ->where('type', 'WAREHOUSE STOCK')
                ->where('branch_in', Auth::user()->branch_id)
                ->latest();

            // $data = $usersQuery->get();
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
                ->addColumn('qty', function ($row) {
                    return $row->qty;
                })
                ->addColumn('date', function ($row) {
                    return $row->date;
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d-F-Y');
                })
                // ->filterColumn('created_at', function ($query, $keyword) {
                //     $query->whereDate('created_at', Carbon::parse($keyword)->format('Y-m-d'));
                // })
                // ->filterColumn('date', function ($query, $keyword) {
                //     $query->whereDate('date', Carbon::parse($keyword)->format('Y-m-d'));
                // })
                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('product', function ($q) use ($keyword) {
                        $q->where('product_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('brand_name', function ($query, $keyword) {
                    $query->whereHas('product.brand', function ($q) use ($keyword) {
                        $q->where('brand_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('product_code', function ($query, $keyword) {
                    $query->whereHas('product', function ($q) use ($keyword) {
                        $q->where('product_code', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('category', function ($query, $keyword) {
                    $query->whereHas('product.category', function ($q) use ($keyword) {
                        $q->where('category_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('color', function ($query, $keyword) {   
                    $query->whereHas('product', function ($q) use ($keyword) {
                        $q->where('color_code', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('size', function ($query, $keyword) {
                    $query->whereHas('product', function ($q) use ($keyword) {
                        $q->where('size', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('qty', function ($query, $keyword) {
                    $query->where('qty', 'like', "%{$keyword}%");
                })
                ->make(true);
        }
    }
}
