<?php

namespace App\Http\Controllers\Admin\Modules\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockQty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Yajra\DataTables\DataTables;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin.modules.purchase.purchase');
    }


    public function getPurchaseList(Request $request)
    {
        

        if ($request->ajax()) {

            $data = Stock::where('reason', 'PURCHASE')->latest();

            return Datatables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('created_by', function ($row) {
                    return optional($row->created_by)->name ?? '';
                })
                ->addColumn('brand', function ($row) {
                    return optional($row->product->brand)->brand_name ?? '';
                })
                ->addColumn('product_name', function ($row) {
                    return optional($row->product)->product_name ?? '';
                })
                ->addColumn('model_no', function ($row) {
                    return optional($row->product)->product_code ?? '';
                })
                ->addColumn('color', function ($row) {
                    return optional($row->product)->color_code ?? '';
                })
                ->addColumn('category', function ($row) {
                    return optional($row->product->category)->category_name ?? '';
                })
                ->addColumn('size', function ($row) {
                    return optional($row->product)->size ?? '';
                })
                ->addColumn('qty', function ($row) {
                    return $row->qty ?? '';
                })
                ->addColumn('date', function ($row) {
                    return $row->created_at ?? '';
                })
                ->addColumn('action', function ($row) {
                    if ($row->approve_status == 'NOT APPROVED') {
                        return '<nav class="nav">
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a data-id="' . $row->id . '" class="dropdown-item approvePurchase" href="javascript:void(0)"><i class="fe fe-edit text-primary"></i> Approve</a>
                            <a class="dropdown-item" href="' . route('purchase.edit', $row->id) . '"><i class="fe fe-edit text-primary"></i> Edit</a>
                            <a data-id="' . $row->id . '" class="dropdown-item deleteProduct" href="javascript:void(0)"><i class="far fa-trash-alt text-danger"></i> Delete </a>
                        </div>
                        <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                    </nav>';
                    } else {
                        return '';
                    }
                })
                ->addColumn('checkbox', function ($row) {

                    if ($row->approve_status == 'NOT APPROVED') {
                        return '<input type="checkbox" data-id="' . $row->id . '"  name="someCheckbox[]" class="someCheckbox" />';
                    } else {
                        return '';
                    }
                })
                ->rawColumns(['action', 'checkbox'])
                ->filterColumn('created_by', function ($query, $keyword) {
                    $query->whereHas('created_by', function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%$keyword%");
                    });
                })

                ->filterColumn('brand', function ($query, $keyword) {
                    $query->whereHas('product.brand', function ($q) use ($keyword) {
                        $q->where('brand_name', 'LIKE', "%$keyword%");
                    });
                })

                ->filterColumn('product_name', function ($query, $keyword) {
                    $query->whereHas('product', function ($q) use ($keyword) {
                        $q->where('product_name', 'LIKE', "%$keyword%");
                    });
                })

                ->filterColumn('model_no', function ($query, $keyword) {
                    $query->whereHas('product', function ($q) use ($keyword) {
                        $q->where('product_code', 'LIKE', "%$keyword%");
                    });
                })
                ->filterColumn('date', function ($query, $keyword) {
                    $query->where('date', 'LIKE', "%$keyword%");
                })

                ->filterColumn('category', function ($query, $keyword) {
                    $query->whereHas('product.category', function ($q) use ($keyword) {
                        $q->where('category_name', 'LIKE', "%$keyword%");
                    });
                })

                ->filterColumn('color', function ($query, $keyword) {
                    $query->whereHas('product', function ($q) use ($keyword) {
                        $q->where('color_code', 'LIKE', "%$keyword%");
                    });
                })

                ->filterColumn('size', function ($query, $keyword) {
                    $query->whereHas('product', function ($q) use ($keyword) {
                        $q->where('size', 'LIKE', "%$keyword%");
                    });
                })
                ->make(true);
        }
    }


    public function approvePurchase(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'stock_id' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $stock = Stock::where('id', $request->stock_id)->update([
                "approve_status" => 'APPROVED',
            ]);


            if ($stock) {

                $stock_details = Stock::where('id', $request->stock_id)->first();
                $product_stock_qty = $stock_details->qty;
                $branch_id = $stock_details->branch_in;
                $product_id = $stock_details->product_id;

                $stock_qty = StockQty::where('branch_id', $branch_id)
                    ->where('product_id', $product_id)
                    ->first();

                if (!empty($stock_qty)) {
                    $old_stock_qty = $stock_qty->qty;

                    $stock_qty2 = StockQty::where('branch_id', $branch_id)
                        ->where('product_id', $product_id)->update([
                            'qty' => ($old_stock_qty + $product_stock_qty),
                        ]);
                } else {

                    $stock_qty2 = StockQty::create([
                        "branch_id" => $branch_id,
                        "product_id" => $product_id,
                        "qty" => $product_stock_qty,
                    ]);
                }

                if ($stock_qty2) {
                    $response = response()->json(['success' => 'Purchased Approved Successfully'], 200);

                } else {
                    $response = response()->json(['errors_success' => 'Error in approving purchase, please try again'], 200);

                }

            } else {
                $response = response()->json(['errors_success' => 'Error in approving purchase, please try again'], 200);
            }

            return $response;
        }
    }


    public function approvePurchaseBulk(Request $request)
    {

        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'stock_ids' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $array_stock_ids = array_map('intval', explode(',',$request->stock_ids));
            $stock = Stock::whereIn('id', $array_stock_ids)->update([
                "approve_status" => 'APPROVED',
            ]);


            if ($stock) {

                foreach ($array_stock_ids as $stock_id) {
                    $stock_details = Stock::where('id', $stock_id)->first();
                    $product_stock_qty = $stock_details->qty;
                    $branch_id = $stock_details->branch_in;
                    $product_id = $stock_details->product_id;

                    $stock_qty = StockQty::where('branch_id', $branch_id)
                        ->where('product_id', $product_id)
                        ->first();

                    if (!empty($stock_qty)) {
                        $old_stock_qty = $stock_qty->qty;

                        $stock_qty2 = StockQty::where('branch_id', $branch_id)
                            ->where('product_id', $product_id)->update([
                                'qty' => ($old_stock_qty + $product_stock_qty),
                            ]);
                    } else {
                        $stock_qty2 = StockQty::create([
                            "branch_id" => $branch_id,
                            "product_id" => $product_id,
                            "qty" => $product_stock_qty,
                        ]);
                    }
                }

                if ($stock_qty2) {
                    $response = response()->json(['success' => 'Purchased Approved Successfully'], 200);

                } else {
                    $response = response()->json(['errors_success' => 'Error in approving purchase, please try again'], 200);
                }


            } else {
                $response = response()->json(['errors_success' => 'Error in approving purchase, please try again'], 200);
            }
            return $response;
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $stock = Stock::where('id', $request->stock_id)->update([
            "qty" => $request->qty,
        ]);

        if ($stock) {
            return redirect()->back()->with(['success', 'Qty updated successfully']);
        } else {
            return redirect()->back()->with(['error', 'Error in  updating Qty']);
        }


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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stock = Stock::find($id);
        return view('backend.admin.modules.purchase.purchase-edit', compact('stock'));
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
        $stock = Stock::find($id);
        if (!empty($stock)) {
            $stock->delete();
            return response()->json(['success' => 'Stock deleted successfully']);
        }
    }
}
