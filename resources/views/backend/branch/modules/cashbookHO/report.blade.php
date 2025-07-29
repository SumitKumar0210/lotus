@extends('backend.branch.layouts.master')
@section('title')
Branch Wise Report
@endsection
@section('extra-css')
@endsection
@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Branch Wise Report</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Cashbook</a></li>
                <li class="breadcrumb-item active" aria-current="page">Branch Wise Report</li>
            </ol>
        </div>
    </div>
    @php
    if(isset($_GET['date_from']))
    {
    $current_date = \Carbon\Carbon::parse($_GET["date_to"]);
    $firstDay = \Carbon\Carbon::parse($_GET["date_from"]);
    }else{
    $current_date = \Carbon\Carbon::now()->toDateString();
    $firstDay = \Carbon\Carbon::now()->startOfMonth()->toDateString();;
    }
    @endphp
    <!-- End Page Header -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <form class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="from">From</label>
                                <input class="form-control fc-datepicker pull-right" placeholder="MM/DD/YYYY"
                                    id="date_from" name="date_from" type="text"
                                    value="{{date('d-m-Y',strtotime($firstDay))}}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="To">To</label>
                                <input class="form-control fc-datepicker pull-right" placeholder="MM/DD/YYYY"
                                    id="date_to" name="date_to" type="text"
                                    value="{{date('d-m-Y',strtotime($current_date))}}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mt-4 pt-1">
                                <button class="form-control btn btn-primary" id="btnFiterSubmitSearch"
                                    type="submit">Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row row-sm">
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Total Cash</p>
                        <div class="ml-auto"> <i class="fas fa-chart-line fs-20 text-primary"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25">1428400</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Bank</p>
                        <div class="ml-auto"> <i class="fab fa-rev fs-20 text-secondary"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25">33686926</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Bank</p>
                        <div class="ml-auto"> <i class="fas fa-dollar-sign fs-20 text-success"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25">₹ 11675507 </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Bank</p>
                        <div class="ml-auto"> <i class="fas fa-signal fs-20 text-info"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25">38262009</h3>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Row -->
    @php
    $branch_id = \Request::segment(4);
    $calc_date = $firstDay;
    $last_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
    @endphp
    @while($calc_date != $last_date)
    <div class="row">

        @php
        $date_from = \Carbon\Carbon::parse($calc_date)->format('Y-m-d 00:00:00');
        $date_to = \Carbon\Carbon::parse($calc_date)->format('Y-m-d 23:59:59');

        $openings =
        \App\Models\OpeningBalance::where('datetime',$calc_date)->where('branch_id',$branch_id)->get();
        @endphp
        @if(sizeOf($openings) > 0)
        @php
        $opening_balances = $openings[0]->opening_balance;
        // dd($opening_balances)
        @endphp
        <div class="col-lg-12">
            <h5>{{date('d-m-Y',strtotime($calc_date))}}</h5>
        </div>
        <div class="col-lg-6">
            <div class="card custom-card">
                <div class="card-body">
                    <h6 class="card-title">Cr</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Particular</th>
                                <th scope="col">Mode</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Other Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Opening Balance</td>
                                <td></td>
                                <td>{{$opening_balances}}</td>
                                <td></td>
                            </tr>
                            @php
                            $total_opening = 0;
                            $total_other_amount =0;
                            $estimate_payments =
                            \App\Models\EstimatePaymentList::whereBetween('updated_at',[$date_from,$date_to])->get();
                            @endphp
                            @if(!empty($estimate_payments))
                            @foreach ($estimate_payments as $item)
                            @php
                            $estimate_datas = \App\Models\Estimate::where('id',
                            $item->estimate_id)->where('branch_id',$branch_id)->get();
                            @endphp
                            @if(sizeOf($estimate_datas) > 0)
                            @php
                            $estimate_no = $estimate_datas[0]->estimate_no;
                            $customer = $estimate_datas[0]->client_name;
                            @endphp
                            @if($item->paid_in_cash > 0)
                            @php
                            $total_opening += $item->paid_in_cash;
                            @endphp
                            <tr>
                                <td>{{$estimate_no}}/{{$customer}}</td>
                                <td>Cash</td>
                                <td>{{$item->paid_in_cash}}</td>
                                <td></td>
                            </tr>
                            @elseif($item->paid_in_bank > 0)
                            @php
                            $total_other_amount +=$item->paid_in_bank;
                            @endphp
                            <tr>
                                <td>{{$estimate_no}}/{{$customer}}</td>
                                <td>Bank</td>
                                <td>&nbsp;</td>
                                <td>{{$item->paid_in_bank}}</td>
                            </tr>
                            @elseif($item->paid_in_neft > 0)
                            @php
                            $total_other_amount +=$item->paid_in_neft;
                            @endphp
                            <tr>
                                <td>{{$estimate_no}}/{{$customer}}</td>
                                <td>NEFT</td>
                                <td>&nbsp;</td>
                                <td>{{$item->paid_in_neft}}</td>
                            </tr>
                            @elseif($item->paid_in_cheque > 0 && $item->status == 'Approved')
                            @php
                            $total_other_amount +=$item->paid_in_cheque;
                            @endphp
                            <tr>
                                <td>{{$estimate_no}}/{{$customer}}</td>
                                <td>CHEQUE</td>
                                <td>&nbsp;</td>
                                <td>{{$item->paid_in_cheque}}</td>
                            </tr>
                            @endif
                            @endif
                            @endforeach
                            @endif
                            @php
                            $cashbook_branch =
                            \App\Models\Cashbook::where('status','Approved')->where('statement','BRANCH')->whereBetween('created_at',[$date_from,$date_to])->where('branch_id',$branch_id)->get();
                            @endphp
                            @if(!empty($cashbook_branch))
                            @foreach($cashbook_branch as $item)
                            <tr>
                                <td>From HO</td>
                                <td>Cash</td>
                                <td>{{$item->total_amount}}</td>
                                <td></td>
                            </tr>
                            @php
                            $total_opening += $item->total_amount;
                            @endphp
                            @endforeach
                            @endif
                            <tr rowspan="2">
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right">Total</td>
                                <td>{{$total_opening + $opening_balances}}</td>
                                <td>{{$total_other_amount}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card custom-card">
                <div class="card-body">
                    <h6 class="card-title">Dr</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Reason</th>
                                <th scope="col">Cash Amount</th>
								<th scope="col">Bank Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total_expenses = 0;
							$total_dr =0;
                            $expenses =
                            \App\Models\Expense::whereBetween('datetime',[$date_from,$date_to])->where('branch_id',$branch_id)->get();
                            @endphp
                            @if(!empty($expenses))
                            @foreach($expenses as $item)
							@if($item->mode == 'Cash')
								 @php
                            $total_expenses += $item->amount;
                            @endphp
                            <tr>
                                <td>{{$item->remark}}</td>
                                <td>{{$item->amount}}</td>
								<td></td>
                            </tr>
							@else
								 @php
                            $total_dr += $item->amount;
                            @endphp
							<tr>
                                <td>{{$item->remark}}</td>
								<td></td>
                                <td>{{$item->amount}}</td>
                            </tr>
							@endif
                           
                            @endforeach
                            @endif
                            @php
                            $cashbook_branch =
                            \App\Models\Cashbook::where('status','Approved')->where('statement','HO')->whereBetween('created_at',[$date_from,$date_to])->where('branch_id',$branch_id)->get();
                            @endphp
                            @if(!empty($cashbook_branch))
                            @foreach($cashbook_branch as $item)
                            <tr>
                                <td>To HO</td>
                                <td>{{$item->total_amount}}</td>
								<td></td>
                            </tr>
                            @php
                            $total_expenses += $item->total_amount;
                            @endphp
                            @endforeach
                            @endif
                            <tr rowspan="2">
                                <td>&nbsp;</td>
                            </tr>
                            @php
                            $closing = $total_opening - $total_expenses + $opening_balances;
                            @endphp
                            <tr>
                                <td>C/F</td>
                                <td>{{$closing}}</td>
								<td>{{ $total_dr}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    @php
    $calc_date = date('Y-m-d', strtotime($calc_date . ' +1 day'));
    @endphp
    @endwhile
    <!-- End Row -->

</div>
@endsection