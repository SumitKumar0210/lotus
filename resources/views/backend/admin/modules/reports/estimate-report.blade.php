@extends('backend.admin.layouts.master')
@section('title')
    Estimate Report
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Estimate Report</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Report</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Estimate Report</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="javascript:void(0)"><i
                        class="fe fe-external-link"></i> Export</a>
            </div>
        </div>
        <!-- End Page Header -->


        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="branch_id">Select Branch</label>
                                    <select name="branch_id" id="branch_id" class="form-control select2">
                                        @if (!empty($branches))
                                            <option value="" selected>Select Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="from">From</label>
                                    <input class="form-control fc-datepicker pull-right" placeholder="MM/DD/YYYY"
                                        id="date_from" name="date_from" type="text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="To">To</label>
                                    <input class="form-control fc-datepicker pull-right" placeholder="MM/DD/YYYY"
                                        id="date_to" name="date_to" type="text">
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="To">SEARCH</label>
                                    <button class="form-control btn btn-primary" id="btnFiterSubmitSearch"
                                        type="button">SEARCH
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="product_table">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Created By</th>
                                        <th>Estimate no</th>
                                        <th>Branch</th>
                                        <th>Customer name</th>
                                        <th>Mobile</th>
                                        <th>Sub total</th>
                                        <th>Discount</th>
                                        <th>Total Paid</th>
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

    </div>
@endsection
@section('extra-js')
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#branch_id').select2();

            let table = $('#product_table').DataTable({
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                pageLength: 25,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('reports.getEstimateReportList') }}',
                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                        d.branch_id = $('#branch_id').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'estimate_no',
                        name: 'estimate_no'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'client_mobile',
                        name: 'client_mobile'
                    },
                    {
                        data: 'sub_total',
                        name: 'sub_total'
                    },
                    {
                        data: 'discount_value',
                        name: 'discount_value'
                    },
                    {
                        data: 'total_paid',
                        name: 'total_paid'
                    },
                    {
                        data: 'dues_amount',
                        name: 'dues_amount'
                    },
                ],
            });

            $('#btnFiterSubmitSearch').click(function(e) {
                e.preventDefault;
                e.stopPropagation();
                $('#product_table').DataTable().draw(true);
                return false;
            });

            //get current date
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;
            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            today = mm + '-' + dd + '-' + yyyy;
            //get current date

            $(document).on('click', '.exportToExcel', function(e) {
                $("#product_table").table2excel({
                    exclude: ".noExl",
                    name: "estimateReport",
                    filename: "estimateReport-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });
        });
    </script>
@endsection
