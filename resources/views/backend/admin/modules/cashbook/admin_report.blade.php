@extends('backend.admin.layouts.master')
@section('title')
Admin Report
@endsection
@section('extra-css')
@endsection
@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Admin Report</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Cashbook</a></li>
                <li class="breadcrumb-item active" aria-current="page">Admin Report</li>
            </ol>
        </div>
    </div>
    @php

    // dd($_GET['date_from']);
    if(isset($_GET['date_from']))
    {
    $current_date = \Carbon\Carbon::parse($_GET["date_to"]);
    $firstDay = \Carbon\Carbon::parse($_GET["date_from"]);
    }else{
    $current_date = \Carbon\Carbon::now()->toDateString();
    $firstDay = \Carbon\Carbon::now()->startOfMonth()->toDateString();;
    }
    // $date_from = Carbon::parse($_GET["date_from"])->format('Y-m-d 00:00:00');

    @endphp
    <!-- End Page Header -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    {{-- <div class="row"> --}}
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
                        {{--
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->
    @php
    $calc_date = $firstDay;
    $last_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
    @endphp
    @while($calc_date != $last_date)
    <div class="row">

        @php
        $date_from = \Carbon\Carbon::parse($calc_date)->format('Y-m-d 00:00:00');
        $date_to = \Carbon\Carbon::parse($calc_date)->format('Y-m-d 23:59:59');

        $openings =
        \App\Models\OpeningBalance::where('datetime',$calc_date)->where('branch_id','0')->get();
        @endphp
        @if(sizeOf($openings) > 0)
        @php
        $opening_balances = $openings[0]->opening_balance;
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
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Opening Balance</td>
                                <td>{{$opening_balances}}</td>
                            </tr>
                            @php
                            $total_amount = 0;
                            $cashbook_ho =
                            \App\Models\Cashbook::where('status','Approved')->where('statement',"HO")->whereBetween('created_at',[$date_from,$date_to])->get();
                            // dd($cashbook_ho);
                            @endphp
                            @if(!empty($cashbook_ho))
                            @foreach($cashbook_ho as $item)
                            <tr>
                                <td>From {{$item->BranchDetail->branch_name}}</td>
                                <td>{{$item->total_amount}}</td>
                            </tr>
                            @php
                            $total_amount += $item->total_amount;
                            @endphp
                            @endforeach
                            @endif

                            <tr rowspan="2">
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-right">Total</td>
                                <td>{{$total_amount + $opening_balances}}</td>
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
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total_collect = 0;
                            $cashbook_branch =
                            \App\Models\Cashbook::where('status','Approved')->where('statement','BRANCH')->whereBetween('created_at',[$date_from,$date_to])->get();
                            @endphp
                            @if(!empty($cashbook_branch))
                            @foreach($cashbook_branch as $item)
                            <tr>
                                <td>To {{$item->BranchDetail->branch_name}}</td>
                                <td>{{$item->total_amount}}</td>
                            </tr>
                            @php
                            $total_collect += $item->total_amount;
                            @endphp
                            @endforeach
                            @endif

                            @php
                            $expenses =
                            \App\Models\Expense::where('mode','CASH')->where('status',
                            'Approved')->whereBetween('datetime',[$date_from,$date_to])->where('branch_id',0)->get();
                            @endphp
                            @if(!empty($expenses))
                            @foreach($expenses as $item)
                            <tr>
                                <td>{{$item->remark}}</td>
                                <td>{{$item->amount}}</td>
                            </tr>
                            @php
                            $total_collect += $item->amount;
                            @endphp
                            @endforeach
                            @endif
                            <tr rowspan="2">
                                <td>&nbsp;</td>
                            </tr>
                            @php
                            $closing = $total_amount - $total_collect + $opening_balances;
                            @endphp
                            <tr>
                                <td>C/F</td>
                                <td>{{$closing}}</td>
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