@extends('backend.admin.layouts.master')
@section('title')
    Product History
@endsection
@section('extra-css')
    <style>
        div.table-responsive>div.dataTables_wrapper>div.row {
            overflow-x: scroll;
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Product History</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Report</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product History</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="javascript:void(0)"><i class="fe fe-external-link"></i>
                    Export</a>
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
                                    <label for="branch_id">Select Model</label>
                                    <select name="product_id" id="product_id" class="form-control select2">
                                        @if (!empty($products))
                                            <option value="" selected>Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->product_code }}</option>
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
                        <h5>Purchase & Branch Transfer</h5>
                        <hr />
                        <div class="table-responsive">
                            <table class="table" id="product_table">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Model No</th>
                                        <th>Brand Name</th>
                                        <th>Reason</th>
                                        <th>Branch Out</th>
                                        <th>Branch In</th>
                                        <th>Out</th>
                                        <th>In</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <h5>Sale & Sale Return</h5>
                        <hr />
                        <div class="table-responsive">
                            <table class="table" id="product_table_2">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Model No</th>
                                        <th>Brand Name</th>
                                        <th>Reason</th>
                                        <th>Branch Out</th>
                                        <th>Branch In</th>
                                        <th>Out</th>
                                        <th>In</th>
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


            let table = $('#product_table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                "lengthChange": false,
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false,
                ajax: {
                    url: '{{ route('reports.getProductWiseReportList') }}',
                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                        d.product_id = $('#product_id').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'product_code',
                        name: 'product_code'
                    },
                    {
                        data: 'brand_name',
                        name: 'brand_name'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'branch_in',
                        name: 'branch_in'
                    },
                    {
                        data: 'branch_out',
                        name: 'branch_out'
                    },
                    {
                        data: 'in',
                        name: 'in'
                    },
                    {
                        data: 'out',
                        name: 'out'
                    },
                ],
            });
            $('#product_id').select2();



            let table2 = $('#product_table_2').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                "lengthChange": false,
                "searching": false,
                "paging": false,
                "ordering": false,
                "info": false,
                ajax: {
                    url: '{{ route('reports.getEstimateListProductReport') }}',
                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                        d.product_id = $('#product_id').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'product_code',
                        name: 'product_code'
                    },
                    {
                        data: 'brand_name',
                        name: 'brand_name'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'branch_in',
                        name: 'branch_in'
                    },
                    {
                        data: 'branch_out',
                        name: 'branch_out'
                    },
                    {
                        data: 'in',
                        name: 'in'
                    },
                    {
                        data: 'out',
                        name: 'out'
                    },
                ],
            });

            $('#btnFiterSubmitSearch').click(function(e) {
                e.preventDefault;
                e.stopPropagation();
                $('#product_table').DataTable().draw(true);
                $('#product_table_2').DataTable().draw(true);
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
                    name: "productHistoryReport",
                    filename: "productHistoryReport-" + today + ".xls",
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
