<?php

namespace App\Http\Controllers\Admin\Modules\Delivery;

use App\Http\Controllers\Controller;
use App\Models\EstimateProductDeliveryStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DeliveredController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin.modules.delivery.delivered');
    }



  public function getDeliveredList(Request $request)
    {

        if ($request->ajax()) {
            ini_set('memory_limit', '1024M');
            $data = EstimateProductDeliveryStatus::with([
                'ProductList:id,product_name,product_code,color,size,qty,estimate_id', 
                'ProductList.Estimate:id,estimate_no,user_id,client_name,client_mobile,client_address,remarks', 
                'user:id,name'])
                ->where('delivery_status', 'ITEM DELIVERED')
                ->latest();
                // ->take(18000)
                // ->orderBy('id', 'DESC')
                // ->get();

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('estimate_no', function ($row) {
                    return optional($row->ProductList?->Estimate)->estimate_no ?? '';
                })
                ->addColumn('branch_name', function ($row) {
                    return optional($row->ProductList?->Estimate?->user->branch)->branch_name ?? '';
                })
                ->addColumn('client_name', function ($row) {
                    return optional($row->ProductList?->Estimate)->client_name ?? '';
                })
                ->addColumn('client_mobile', function ($row) {
                    return optional($row->ProductList?->Estimate)->client_mobile ?? '';
                })
                ->addColumn('client_address', function ($row) {
                    return optional($row->ProductList?->Estimate)->client_address ?? '';
                })
                ->addColumn('delivery_date', function ($row) {
                    return $row->date_time ?? '';
                })
                ->addColumn('product_name', function ($row) {
                    return optional($row->ProductList)->product_name ?? '';
                })
                ->addColumn('product_code', function ($row) {
                    return optional($row->ProductList)->product_code ?? '';
                })
                ->addColumn('color', function ($row) {
                    return optional($row->ProductList)->color ?? '';
                })
                ->addColumn('size', function ($row) {
                    return optional($row->ProductList)->size ?? '';
                })
                ->addColumn('quantity', function ($row) {
                    return $row->qty ?? '';
                })
                ->addColumn('remarks', function ($row) {
                    return optional($row->ProductList?->Estimate)->remarks ?? '';
                })
                ->addColumn('product_type', function ($row) {
                    return optional($row->ProductList)->product_type ?? '';
                })
                ->addColumn('created_by', function ($row) {
                    return optional($row->ProductList?->Estimate?->user)->name ?? '';
                })
                ->addColumn('delivered_by', function ($row) {
                    return optional($row->user)->name ?? '';
                })

                ->addColumn('action', function ($row) {

                    if (!empty($row->ProductList)) {

                        if ($row->ProductList->is_sale_returned ?? '' == 'ITEM SALE RETURNED') {
                            return '';
                        } else {
                            return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">

                           <a class="dropdown-item " href="javascript:void(0);">
                           <label class="ckbox">
                           <input type="checkbox" data-qty="' . $row->ProductList->qty . '"  data-id="' . $row->ProductList->id . '" data-product_id="' . $row->ProductList->product_id . '"    data-estimate_id="' . $row->ProductList->estimate_id . '"  class="conformDelivery">
                           <span>Sale Return</span>
                           </label>
                           </a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                        }
                    }
                })
                ->rawColumns(['action'])
                ->filterColumn('estimate_no', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate', fn($q) => $q->where('estimate_no', 'like', "%$keyword%"));
                })
                ->filterColumn('branch_name', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate.user.branch', fn($q) => $q->where('branch_name', 'like', "%$keyword%"));
                })
                ->filterColumn('client_name', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate', fn($q) => $q->where('client_name', 'like', "%$keyword%"));
                })
                ->filterColumn('client_mobile', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate', fn($q) => $q->where('client_mobile', 'like', "%$keyword%"));
                })
                ->filterColumn('client_address', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate', fn($q) => $q->where('client_address', 'like', "%$keyword%"));
                })
                ->filterColumn('delivery_date', function ($query, $keyword) {
                    $query->where('date_time', 'like', "%$keyword%");
                })
                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('ProductList', fn($q) => $q->where('product_name', 'like', "%$keyword%"));
                })
                ->filterColumn('product_code', function ($query, $keyword) {
                    $query->whereHas('ProductList', fn($q) => $q->where('product_code', 'like', "%$keyword%"));
                })
                ->filterColumn('color', function ($query, $keyword) {
                    $query->whereHas('ProductList', fn($q) => $q->where('color', 'like', "%$keyword%"));
                })
                ->filterColumn('size', function ($query, $keyword) {
                    $query->whereHas('ProductList', fn($q) => $q->where('size', 'like', "%$keyword%"));
                })
                ->filterColumn('quantity', function ($query, $keyword) {
                    $query->where('qty', 'like', "%$keyword%");
                })
                ->filterColumn('remarks', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate', fn($q) => $q->where('remarks', 'like', "%$keyword%"));
                })
                ->filterColumn('product_type', function ($query, $keyword) {
                    $query->whereHas('ProductList', fn($q) => $q->where('product_type', 'like', "%$keyword%"));
                })
                ->filterColumn('created_by', function ($query, $keyword) {
                    $query->whereHas('ProductList.Estimate.user', fn($q) => $q->where('name', 'like', "%$keyword%"));
                })
                ->filterColumn('delivered_by', function ($query, $keyword) {
                    $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$keyword%"));
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
