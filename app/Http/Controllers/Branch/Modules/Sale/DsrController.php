<?php

namespace App\Http\Controllers\Branch\Modules\Sale;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DsrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.branch.modules.sale.dsr-list');
    }


    public function getDailySaleList(Request $request)
    {
        if ($request->ajax()) {
            $data = Estimate::with('EstimateProductLists', 'EstimatePaymentLists')
                ->where('branch_id', Auth::user()->branch->id)
                //->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->latest()
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row->user->branch->branch_name;
                })
                ->addColumn('product_name', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->product_name . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })
                ->addColumn('product_code', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->product_code . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })
                ->addColumn('color', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->color . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })
                ->addColumn('size', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->size . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })
                ->addColumn('quantity', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimateProductLists as $key => $EstimateProductList) {
                        $options .= '<li>' . $EstimateProductList->qty . '</li>';
                    }
                    $options .= '</ul>';
                    return $options;
                })
                ->rawColumns(['product_name', 'product_code', 'color', 'size', 'quantity'])
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
