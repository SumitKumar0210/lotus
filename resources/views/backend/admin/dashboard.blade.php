@extends('backend.admin.layouts.master')
@section('title')
    ADMIN DASHBOARD
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Admin Dashboard</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin Dashboard</li>
                </ol>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Row -->
        <div class="row row-sm">



            <div class="col-sm-6 col-xl col-lg">
                <div class="card custom-card">
                    <div class="card-body dash1">
                        <div class="d-flex">
                            <p class="mb-1 tx-inverse">Total Sales of Month</p>
                            <div class="ml-auto">
                                <i class="fas fa-chart-line fs-20 text-primary"></i>
                            </div>
                        </div>
                        <div>
                            @php
                                $startDate = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                                $firstDay = \Carbon\Carbon::now()
                                    ->firstOfMonth()
                                    ->format('Y-m-d H:i:s');
                                $estimate = \App\Models\Estimate::where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                                    ->whereBetween('created_at', [$firstDay, $startDate])
                                    ->pluck('id');
                                $estimate_payments = \App\Models\EstimatePaymentList::whereIn('estimate_id', $estimate)->get();
                                $estimate_payments_total = $estimate_payments->sum('total_paid');
                            @endphp
                            <h3 class="dash-25">{{ $estimate_payments_total }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl col-lg">
                <div class="card custom-card">
                    <div class="card-body dash1">
                        <div class="d-flex">
                            <p class="mb-1 tx-inverse">Total Dues</p>
                            <div class="ml-auto">
                                <i class="fab fa-rev fs-20 text-secondary"></i>
                            </div>
                        </div>
                        <div>
                            @php
                                $estimate = \App\Models\Estimate::where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                                    ->where('payment_status', 'PAYMENT DUE')
                                    ->get();
                                $total_dues = $estimate->sum('dues_amount');
                            @endphp
                            <h3 class="dash-25">{{ $total_dues }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl col-lg">
                <div class="card custom-card">
                    <div class="card-body dash1">
                        <div class="d-flex">
                            <p class="mb-1 tx-inverse">Total Discount</p>
                            <div class="ml-auto">
                                <i class="fas fa-dollar-sign fs-20 text-success"></i>
                            </div>
                        </div>
                        <div>
                            @php
                                $discount_value = \App\Models\Estimate::where('estimate_status', '!=', 'ESTIMATE CANCELLED')->get();
                                if (!empty($discount_value)) {
                                    $all_discount_value = $discount_value->sum('discount_value');
                                } else {
                                    $all_discount_value = 0;
                                }
                            @endphp
                            <h3 class="dash-25">&#8377; {{ $all_discount_value }} </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl col-lg">
                <div class="card custom-card">
                    <div class="card-body dash1">
                        <div class="d-flex">
                            <p class="mb-1 tx-inverse">Total Collection</p>
                            <div class="ml-auto">
                                <i class="fas fa-signal fs-20 text-info"></i>
                            </div>
                        </div>
                        <div>
                            @php
                                $estimate = \App\Models\Estimate::where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                                    ->where('payment_status', 'PAYMENT DONE')
                                    ->pluck('id');
                                $estimate_payments = \App\Models\EstimatePaymentList::whereIn('estimate_id', $estimate)->get();
                                $estimate_payments_total = $estimate_payments->sum('total_paid');
                            @endphp
                            <h3 class="dash-25">{{ $estimate_payments_total }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End  Row -->
        <!-- Row-->
        <div class="row">
            <div class="col-sm-6 col-xl-6 col-lg-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div>
                            <h6 class="card-title mb-1">Top Five Branches of {{ Carbon\Carbon::now()->format('F') }}</h6>
                            <br>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap mb-0" id="product_table_8">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Branch Name</th>
                                        <th>Sale</th>
                                        {{-- <th>Discount</th> --}}
                                        <th>Dues</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-6 col-lg-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <div>
                            <h6 class="card-title mb-1">Top Five Branches of Quarter</h6>
                            <br>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap mb-0" id="product_table_9">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Branch Name</th>
                                        <th>Sale</th>
                                        {{-- <th>Discount</th> --}}
                                        <th>Dues</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card custom-card mb-5" style="margin-bottom: 200px;">
                    <div class="card-body">
                        <div id="topBranchesList">
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
@section('extra-js')
    <script src="{{ asset('/vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            let table1 = $('#product_table_8').DataTable({
                lengthMenu: [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "All"]
                ],
                order: [
                    [2, "desc"]
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 5,
                ajax: "{{ route('admin.topFiveBranchesList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
						orderable: false,
                        searchable: false
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'sale',
                        name: 'sale'
                    },
                    // {data: 'discount', name: 'discount'},
                    {
                        data: 'dues',
                        name: 'dues'
                    },
                ],
            });


            let table2 = $('#product_table_9').DataTable({
                lengthMenu: [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "All"]
                ],
                order: [
                    [2, "desc"]
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 5,
                ajax: "{{ route('admin.topFiveQuarterBranchesList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
						orderable: false,
                        searchable: false
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'sale',
                        name: 'sale'
                    },
                    // {data: 'discount', name: 'discount'},
                    {
                        data: 'dues',
                        name: 'dues'
                    },
                ],
            });


            //code for get  data
            $.ajax({
                type: "GET",
                url: '{{ route('admin.topBranchesList') }}',
                data: '',
                success: function(data) {
                    console.log(data);
                    let estimate = data.estimate;
                    let branch_name = data.branch_name;
                    let sale = data.sale;
                    let discount = data.discount;
                    let dues = data.dues;
                    let grand_total = data.grand_total;

                    let html = '';

                    html += '<div class=" main-content-body-profile">\n' +
                        '<nav class="nav main-nav-line">\n';
                    $.each(estimate, function(index2, value2) {
                        let active2 = index2 === 0 ? 'active' : '';
                        html += '<a style="margin-bottom: 25px;" class="nav-link ' + active2 +
                            '" data-toggle="tab" href="#tab' + value2.id + '">' + branch_name[
                                index2] + '</a>';
                    });
                    html += ' </nav>';

                    html += '<div class="card-body tab-content h-100">\n';

                    $.each(estimate, function(index, value) {
                        let active = index === 0 ? 'active' : '';
                        html += '       <div class="tab-pane ' + active + '" id="tab' + value
                            .id + '">\n' +
                            '                            <div class="row row-sm">\n' +
                            '                                <div class="col-sm-6 col-xl col-lg">\n' +
                            '                                    <div class="card custom-card">\n' +
                            '                                        <div class="card-body dash1">\n' +
                            '                                            <div class="d-flex">\n' +
                            '                                                <p class="mb-1 tx-inverse">Total Sales of Month</p>\n' +
                            '                                                <div class="ml-auto">\n' +
                            '                                                    <i class="fas fa-chart-line fs-20 text-primary"></i>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                            <div>\n' +
                            '                                                <h3 class="dash-25">' +
                            sale[index] + '</h3>\n' +
                            '                                            </div>\n' +
                            '                                        </div>\n' +
                            '                                    </div>\n' +
                            '                                </div>\n' +
                            '                                <div class="col-sm-6 col-xl col-lg">\n' +
                            '                                    <div class="card custom-card">\n' +
                            '                                        <div class="card-body dash1">\n' +
                            '                                            <div class="d-flex">\n' +
                            '                                                <p class="mb-1 tx-inverse">Total Dues</p>\n' +
                            '                                                <div class="ml-auto">\n' +
                            '                                                    <i class="fab fa-rev fs-20 text-secondary"></i>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                            <div>\n' +
                            '                                                <h3 class="dash-25">' +
                            dues[index] + '</h3>\n' +
                            '                                            </div>\n' +
                            '                                        </div>\n' +
                            '                                    </div>\n' +
                            '                                </div>\n' +
                            '                                <div class="col-sm-6 col-xl col-lg">\n' +
                            '                                    <div class="card custom-card">\n' +
                            '                                        <div class="card-body dash1">\n' +
                            '                                            <div class="d-flex">\n' +
                            '                                                <p class="mb-1 tx-inverse">Discount %</p>\n' +
                            '                                                <div class="ml-auto">\n' +
                            '                                                    <i class="fas fa-dollar-sign fs-20 text-success"></i>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                            <div>\n' +
                            '                                                <h3 class="dash-25">' +
                            discount[index] + '%</h3>\n' +
                            '                                            </div>\n' +
                            '                                        </div>\n' +
                            '                                    </div>\n' +
                            '                                </div>\n' +
                            '                                <div class="col-sm-6 col-xl col-lg">\n' +
                            '                                    <div class="card custom-card">\n' +
                            '                                        <div class="card-body dash1">\n' +
                            '                                            <div class="d-flex">\n' +
                            '                                                <p class="mb-1 tx-inverse">Total Inventory</p>\n' +
                            '                                                <div class="ml-auto">\n' +
                            '                                                    <i class="fas fa-signal fs-20 text-info"></i>\n' +
                            '                                                </div>\n' +
                            '                                            </div>\n' +
                            '                                            <div>\n' +
                            '                                                <h3 class="dash-25">' +
                            grand_total[index] + '</h3>\n' +
                            '                                            </div>\n' +
                            '                                        </div>\n' +
                            '                                    </div>\n' +
                            '                                </div>\n' +
                            '                            </div>\n' +
                            '                        </div>\n';
                    });
                    html += '</div></div>';

                    $('#topBranchesList').html(html);
                }
            });
            //code for get  data
        });
    </script>
@endsection
