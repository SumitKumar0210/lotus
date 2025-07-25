<?php

namespace App\Http\Controllers\Admin\Modules\Estimate;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use App\Models\EstimatePaymentList;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DuePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin.modules.estimates.due-payment-lists');
    }

    public function getDuePaymentList(Request $request)
    {
        if ($request->ajax()) {

            $data = Estimate::with(['EstimateProductLists', 'EstimatePaymentLists', 'user.branch:id,branch_name'])
                ->where('payment_status', 'PAYMENT DUE')
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                ->latest();
                // ->get();
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return optional($row->user->branch)->branch_name ?? '';
                })
                ->addColumn('dues_paid', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimatePaymentLists as $key => $EstimatePaymentList) {
                        $options .= '<li>' . $EstimatePaymentList->total_paid . '</li>';
                    }
                    $options .= '<ul>';
                    return $options;
                })
                ->addColumn('mode', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimatePaymentLists as $key => $EstimatePaymentList) {
                        if ($EstimatePaymentList->paid_in_cash == 0) {
                            $mode = 'Bank';
                        } else if ($EstimatePaymentList->paid_in_bank == 0) {
                            $mode = 'Cash';
                        } else {
                            $mode = 'Cash & Bank';
                        }
                        $options .= '<li>' . $mode . '</li>';
                    }
                    $options .= '<ul>';
                    return $options;
                })
                ->addColumn('date', function ($row) {
                    $options = '<ul>';
                    foreach ($row->EstimatePaymentLists as $key => $EstimatePaymentList) {
                        $options .= '<li>' . $EstimatePaymentList->date_time . '</li>';
                    }
                    $options .= '<ul>';
                    return $options;
                })
                //                ->addColumn('total', function ($row) {
                //                    return $row->EstimatePaymentLists->sum('total_paid');
                //                })
                ->addColumn('delivery_status', function ($row) {
                    return $row->delivery_status;
                })
                ->addColumn('action', function ($row) {
                    return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                              <a class="dropdown-item" data-toggle="modal" data-target="#modal_demo2" onclick="setPaymentDetail(' . $row->id . ',' . $row->dues_amount . ')"><i class="fe fe-edit text-primary"></i> Settlement</a>
                            </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                })
                ->rawColumns(['dues_paid', 'mode', 'date', 'action'])
               
                // ->filterColumn('branch_name', function ($query, $keyword) {
                //     $query->whereHas('user.branch', function ($q) use ($keyword) {
                //         $q->where('branch_name', 'like', "%{$keyword}%");
                //     });
                // })
                ->filterColumn('dues_paid', function ($query, $keyword) {
                    $query->whereHas('EstimatePaymentLists', function ($q) use ($keyword) {
                        $q->where('total_paid', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('date', function ($query, $keyword) {
                    $query->whereHas('EstimatePaymentLists', function ($q) use ($keyword) {
                        $q->where('date_time', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('delivery_status', function ($query, $keyword) {
                    $query->where('delivery_status', 'like', "%{$keyword}%");
                })
                //  ->filterColumn('mode', function ($query, $keyword) {
                //     $query->whereHas('EstimatePaymentLists', function ($q) use ($keyword) {
                //         $keyword = strtolower(trim($keyword));

                //         if ($keyword === 'cash') {
                //             $q->where('paid_in_cash', '>', 0)->where('paid_in_bank', 0);
                //         } elseif ($keyword === 'bank') {
                //             $q->where('paid_in_bank', '>', 0)->where('paid_in_cash', 0);
                //         } elseif ($keyword === 'cash & bank' || $keyword === 'cash and bank') {
                //             $q->where('paid_in_cash', '>', 0)->where('paid_in_bank', '>', 0);
                //         }
                //     });
                // })

               

                ->make(true);
        }
    }

    public function updateSettleAmount(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'settle_amount' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $total_due = $request->due_amount - $request->settle_amount;


            if ($total_due < 0 || $total_due == 0) {
                $status = 'PAYMENT DONE';
            } else {
                $status = 'PAYMENT DUE';
            }

            $update_estimate = Estimate::where('id', $request->id)->update([
                "dues_amount" => $total_due,
                "payment_status" => $status,
                "settlement_amount" => $request->settle_amount,
            ]);

            if ($update_estimate) {
                EstimatePaymentList::create([
                    "estimate_id" => $request->id,
                    "paid_in_cash" => $request->settle_amount,
                    "total_paid" => $request->settle_amount,
                    "is_settled" => "YES",
                    "date_time" => Carbon::now()->format('d-m-Y'),
                ]);

                $response = response()->json(['success' => 'Settlement Amount updated successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in updating Settlement Amount, Please try again'], 200);
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
