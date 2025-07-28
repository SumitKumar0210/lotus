<?php

namespace App\Http\Controllers\Branch\Modules\Estimate;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CancelledEstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.branch.modules.estimates.estimate-lists-cancelled');
    }


    public function getEstimateListCancelled(Request $request)
    {
        if ($request->ajax()) {
            $data = Estimate::with('EstimateProductLists', 'EstimatePaymentLists', 'user.branch:id,branch_name')
                ->where('branch_id', Auth::user()->branch->id)
                ->where('estimate_status', '=', 'ESTIMATE CANCELLED')
                ->latest();
                // ->get();
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return optional($row->user->branch)->branch_name ?? '';
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
                        $options .= '<li>' . $EstimateProductList->product_name . '</li>';
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
                ->addColumn('action', function ($row) {
                    return '<nav class="nav">
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a data-id="' . $row->id . '" class="dropdown-item" href="' . route('estimate-list.show', $row->id) . '" target="_blank"><i class="fe fe-edit text-primary"></i> View</a>
                            <a data-id="' . $row->id . '" class="dropdown-item deleteProduct" href="javascript:void(0)"><i class="far fa-trash-alt text-danger"></i> Delete </a>
                        </div>
                        <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                    </nav>';

                })
                ->rawColumns(['product_name', 'product_code', 'color', 'size', 'quantity', 'action'])
                ->filterColumn('branch_name', function ($query, $keyword) {
                    $query->whereHas('user.branch', function ($q) use ($keyword) {
                        $q->where('branch_name', 'like', "%{$keyword}%");
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $estimate = Estimate::with('EstimateProductLists', 'EstimatePaymentLists')->find($id);
        if (!empty($estimate)) {
            if ($estimate->estimate_status == 'ESTIMATE CANCELLED' && $estimate->payment_status == 'PAYMENT DUE' && $estimate->delivery_status == 'NOT DELIVERED') {
                $estimate->EstimateProductLists->each->delete();
                $estimate->EstimatePaymentLists->each->delete();
                $estimate->delete();
                return response()->json(['success' => 'Estimate deleted successfully']);
            }
        } else {
            return response()->json(['errors' => 'Error in deleting, child exists']);
        }
    }
}
