<?php

namespace App\Http\Controllers\WareHouse\Modules\BranchTransfer;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class InTransitWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.warehouse.modules.branch-transfer.in-transit');
    }


    public function getInTransitWarehouseList(Request $request)
    {


        if ($request->ajax()) {
            $branch_id = Auth::user()->branch_id;

            $data = Stock::with(['branchTo:id,branch_name', 
            'branchUserOut:id,name', 
            'Product:id,product_name,product_code,color_code,size'])
                ->where('type', 'IN TRANSIT')
                ->where('status', 'OUT STOCK')
                ->where('reason', 'BRANCH TRANSFER')
                ->where('branch_out', $branch_id);
                // ->get();

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('branch_to', function ($row) {
                    return optional($row->branchTo)->branch_name;
                })
                ->addColumn('created_by', function ($row) {
                    return optional($row->branchUserOut)->name;
                })
                ->addColumn('product_name', function ($row) {
                    return optional($row->Product)->product_name;
                })
                ->addColumn('product_code', function ($row) {
                    return optional($row->Product)->product_code;
                })
                ->addColumn('color', function ($row) {
                    return optional($row->Product)->color_code;
                })
                ->addColumn('size', function ($row) {
                    return optional($row->Product)->size;
                })
                ->addColumn('qty', function ($row) {
                    return $row->qty;
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d-F-Y');
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereDate('created_at', date('Y-m-d', strtotime($keyword)));
                })
                ->filterColumn('qty', function ($query, $keyword) {
                    $query->where('qty', $keyword);
                })
                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('product_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('color', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('color_code', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('branch_to', function ($query, $keyword) {
                    $query->whereHas('branchTo', function ($q) use ($keyword) {
                        $q->where('branch_name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('created_by', function ($query, $keyword) {
                    $query->whereHas('branchUserOut', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('product_code', function ($query, $keyword) {
                    $query->whereHas('Product', function ($q) use ($keyword) {
                        $q->where('product_code', 'like', "%{$keyword}%");
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
