<?php

namespace App\Http\Controllers\Branch\Modules\Reports;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.branch.modules.reports.product-report');
    }


    public function getProductReportList(Request $request)
    {
        if ($request->ajax()) {
            $usersQuery = Stock::query()
            ->where('branch_in', Auth::user()->branch_id);

            // if (!empty($_GET["date_from"]) && !empty($_GET["date_to"]) && !empty($_GET["product_id"])) {

            //     $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
            //     $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');

            //     $usersQuery
            //         ->where('branch_in', Auth::user()->branch_id)
            //        // ->where('type', 'BRANCH STOCK')
            //         ->where('product_id', $_GET["product_id"])
            //         ->whereBetween('date', [$date_from, $date_to])
            //         ->latest();
            // } else {
            //     $usersQuery
            //         ->where('branch_in', Auth::user()->branch_id)
            //         //->where('type', 'BRANCH STOCK')
            //         ->latest();
            // }
            if ($request->product_id) {
                $usersQuery->where('product_id', $request->product_id);
            }
            if ($request->date_from && $request->date_to) {
                $date_from = Carbon::parse($request->date_from)->startOfDay();
                $date_to = Carbon::parse($request->date_to)->endOfDay();
                $usersQuery->whereBetween('date', [$date_from, $date_to]);
            }
            $usersQuery->latest();
            // $data = $usersQuery->get();
            return DataTables::of($usersQuery)
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
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('type', function ($row) {
                    return $row->type;
                })
                ->addColumn('from', function ($row) {
                    return optional($row->to->branch)->branch_name ?? '';
                })
                ->addColumn('to', function ($row) {
                    return optional($row->branchTo)->branch_name ?? '';
                })
                ->addColumn('date', function ($row) {
                    return $row->date;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('remarks', function ($row) {
                    return $row->remarks;
                })
                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('product', function ($q) use ($keyword) {
                        $q->where('product_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('product_code', function ($query, $keyword) {
                    $query->whereHas('product', function ($q) use ($keyword) {
                        $q->where('product_code', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('brand_name', function ($query, $keyword) {      
                    $query->whereHas('product.brand', function ($q) use ($keyword) {
                        $q->where('brand_name', 'like', "%{$keyword}%");
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
                ->filterColumn('status', function ($query, $keyword) {
                    $query->where('status', 'like', "%{$keyword}%");
                })
                ->filterColumn('type', function ($query, $keyword) {
                    $query->where('type', 'like', "%{$keyword}%");
                })
                ->filterColumn('from', function ($query, $keyword) {
                    $query->whereHas('to.branch', function ($q) use ($keyword) {
                        $q->where('branch_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('to', function ($query, $keyword) {
                    $query->whereHas('branchTo', function ($q) use ($keyword) {
                        $q->where('branch_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('date', function ($query, $keyword) {
                    try {
                        $formatted = Carbon::parse($keyword)->format('Y-m-d');
                        $query->where('date', 'like', "%{$formatted}%");
                    } catch (\Exception $e) {
                        // Invalid date input — skip filtering
                    }
                })
                
                ->filterColumn('remarks', function ($query, $keyword) {
                    $query->where('remarks', 'like', "%{$keyword}%");
                })
                 ->make(true);
        }
    }


    public function getBranchProductStockListSearch(Request $request)
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
