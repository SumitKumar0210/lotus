@extends('backend.admin.layouts.master')
@section('title')
Report
@endsection
@section('extra-css')
@endsection
@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Report - {{$branches[0]}}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Cashbook</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report</li>
            </ol>
        </div>
    </div>
    <!-- End Page Header -->
    <!-- End Page Header -->
    @php
    $current_date = \Carbon\Carbon::now()->toDateString();
    $firstDay = \Carbon\Carbon::now()->startOfMonth()->toDateString();
    @endphp
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" id="branch_id" value={{$branch_id}}>
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
                                <button class="form-control btn btn-primary" onclick="getData()" type="button">Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Total Cash</p>
                        <div class="ml-auto"> <i class="fas fa-chart-line fs-20 text-primary"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25 total_cash">0</h3>
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
                        <h3 class="dash-25 total_bank">0</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Cheque</p>
                        <div class="ml-auto"> <i class="fas fa-dollar-sign fs-20 text-success"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25 total_cheque">0 </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">NEFT</p>
                        <div class="ml-auto"> <i class="fas fa-dollar-sign fs-20 text-success"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25 total_neft">0 </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">UPI</p>
                        <div class="ml-auto"> <i class="fas fa-signal fs-20 text-info"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25 total_upi">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->

    <section class="detail_data"></section>


    <!-- End Edit Expense modal -->
</div>
@endsection
@section('extra-js')
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        getData();
    });

    //getData();
    //get data according to date
    function getData()
    {
        let date_from = $('#date_from').val();
        let date_to = $('#date_to').val();
        let branch_id = $('#branch_id').val();

        if(date_from == '' && date_to == '')
        {
            toastr.warning('From and To Date is Required', 'WARNING');
        }
        else{
            $.ajax({
                url: "{{route('admin.cashbook.getData')}}",
                method:"POST",
                data:{date_from:date_from,date_to:date_to,branch_id:branch_id},
                cache: false,
                dataType: "json",
                success:function(data)
                {
                    //console.log(data);
                    $('.detail_data').html(data.data);
                    $('.total_cash').html(data.cash);
                    $('.total_bank').html(data.bank);
                    $('.total_cheque').html(data.cheque);
                    $('.total_neft').html(data.neft);
                    $('.total_upi').html(data.other);
                }
            });
        }
    }
    
</script>
@endsection