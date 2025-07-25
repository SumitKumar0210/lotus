<?php

namespace App\Http\Controllers\Admin\Modules\Reports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Estimate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EstimateReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::latest()->get();
        return view('backend.admin.modules.reports.estimate-report',compact('branches'));
    }


    public function getEstimateReportList(Request $request)
    {
        if ($request->ajax()) {
            $usersQuery = Estimate::query();

            if (!empty($_GET["date_from"]) && !empty($_GET["date_to"]) && !empty($_GET["branch_id"])) {

                $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');
                $date_to = Carbon::parse($_GET["date_to"])->format('Y-m-d 23:59:59');

                $usersQuery
                    ->where('branch_id', $_GET["branch_id"])
                    ->whereBetween('created_at', [$date_from, $date_to])
                    ->latest();
            } else {
                $usersQuery->latest();
            }
            $data = $usersQuery->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_by', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->user->branch->branch_name;
                })
                ->addColumn('total_paid', function ($row) {
                    return $row->EstimatePaymentLists->sum('total_paid');
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
