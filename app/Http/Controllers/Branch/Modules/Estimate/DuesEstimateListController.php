<?php

namespace App\Http\Controllers\Branch\Modules\Estimate;

use App\Http\Controllers\Controller;
use App\Models\Estimate;
use App\Models\EstimatePaymentList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DuesEstimateListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.branch.modules.estimates.due-estimate-lists');
    }


    public function getDuesEstimateList(Request $request)
    {
        if ($request->ajax()) {
            $data = Estimate::with('EstimateProductLists', 'EstimatePaymentLists', 'user.branch:id,branch_name')
                ->where('payment_status', 'PAYMENT DUE')
                ->where('branch_id', Auth::user()->branch->id)
                ->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                //->where('delivery_status_order_to_make', 'NOT DELIVERED')
                ->latest();
                // ->get();
            return Datatables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return optional($row->user->branch)->branch_name;
                })
                ->addColumn('action', function ($row) {

                    if ($row->is_admin_approved == "NO") {
                        return '<nav class="nav">
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a data-id="' . $row->id . '" class="dropdown-item viewProduct" href="' . route('dues-estimate-list.show', $row->id) . '"><i class="fe fe-edit text-primary"></i> View</a>
                                <a data-id="' . $row->id . '" data-dues_amount="' . $row->dues_amount . '" class="dropdown-item createProduct" href="javascript:void(0)"><i class="fe fe-edit text-success"></i> Amount </a>
                                </div>
                            <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                        </nav>';
                    } else if ($row->is_admin_approved == null) {
                        return '<nav class="nav">
                                <div class="dropdown-menu dropdown-menu-right shadow">
                                    <a data-id="' . $row->id . '" class="dropdown-item viewProduct" href="' . route('dues-estimate-list.show', $row->id) . '"><i class="fe fe-edit text-primary"></i> View</a>
                                    <a data-id="' . $row->id . '" data-dues_amount="' . $row->dues_amount . '" class="dropdown-item createProduct" href="javascript:void(0)"><i class="fe fe-edit text-success"></i> Amount </a>
                                    <a data-id="' . $row->id . '" class="dropdown-item dueApproval" href="javascript:void(0)"><i class="fe fe-user text-danger"></i> Apply for Approval </a>
                                
                                    </div>
                                <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                            </nav>';
                    } else {
                        return '<nav class="nav">
                                <div class="dropdown-menu dropdown-menu-right shadow">
                                    <a data-id="' . $row->id . '" class="dropdown-item viewProduct" href="' . route('dues-estimate-list.show', $row->id) . '"><i class="fe fe-edit text-primary"></i> View</a>
                                    <a data-id="' . $row->id . '" data-dues_amount="' . $row->dues_amount . '" class="dropdown-item createProduct" href="javascript:void(0)"><i class="fe fe-edit text-success"></i> Amount </a>
                                    </div>
                                <button class="btn ripple btn-outline-primary btn-rounded " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fe fe-more-vertical"></i></button>
                            </nav>';
                    }
                })
                ->rawColumns(['action'])
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'amount' => ['required', 'numeric'],
                'paid_in_cash' => ['required', 'numeric'],
                'paid_in_bank' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }


            $estimate_payment_lists = new EstimatePaymentList();
            $estimate_payment_lists->estimate_id = $request->estimate_id;
            $estimate_payment_lists->date_time = Carbon::now()->format('d-m-Y');
            $estimate_payment_lists->paid_in_cash = $request->paid_in_cash;
            $estimate_payment_lists->paid_in_bank = $request->paid_in_bank;
            $estimate_payment_lists->total_paid = $request->amount;
            if ($estimate_payment_lists->save()) {

                $estimate = Estimate::find($request->estimate_id);
                $dues_amount = $estimate->dues_amount;
                $new_dues_amount =  $dues_amount - $request->amount;

                if ($new_dues_amount < 0 || $new_dues_amount == 0) {
                    $status = 'PAYMENT DONE';
                } else {
                    $status = 'PAYMENT DUE';
                }

                $estimate_update = Estimate::where('id', $request->estimate_id)->update([
                    'dues_amount' => $new_dues_amount,
                    'payment_status' => $status
                ]);

                $response = response()->json(['success' => 'Payment Recorded successfully'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Error in adding Payment, please try again'], 200);
            }
            return $response;
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
        $estimate = Estimate::with('EstimateProductLists', 'EstimatePaymentLists', 'user.branch')->find($id);

        $paid_in_cash = 0;
        $paid_in_bank = 0;
        $total_paid = 0;
        foreach ($estimate->EstimatePaymentLists as $key => $EstimatePaymentList) {
            $paid_in_cash += $EstimatePaymentList->paid_in_cash;
            $paid_in_bank += $EstimatePaymentList->paid_in_bank;
            $total_paid += $EstimatePaymentList->total_paid;
        }

        return view('backend.branch.modules.estimates.due-estimate-view', compact('estimate', 'paid_in_cash', 'paid_in_bank', 'total_paid'));
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



    public function applyForDueApproval(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'estimate_id' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors_validation' => $validator->errors()->all(),
                ], 200);
            }

            $estimate_update = Estimate::where('id', $request->estimate_id)->update([
                'is_admin_approved' => "NO",
            ]);

            if ($estimate_update) {
                $response = response()->json(['success' => 'Estimate waiting for approval'], 200);
            } else {
                $response = response()->json(['errors_success' => 'Something went wrong, please try again'], 200);
            }
            return $response;
        }
    }
}
