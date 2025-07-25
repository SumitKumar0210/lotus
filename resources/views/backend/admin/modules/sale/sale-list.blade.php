@extends('backend.admin.layouts.master')
@section('title')
    DSR
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">DSR</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">DSR</li>
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
                                    <label for="branch_id">Select Branch</label>
                                    <select name="branch_id" id="branch_id" class="form-control select2">
                                        <option value="">Select Branch</option>
                                        @if (!empty($branches))
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
                                    <input class="form-control fc-datepicker pull-right"
                                        value="{{ \Carbon\Carbon::now()->subDays(30)->format('m/d/Y') }}"
                                        placeholder="MM/DD/YYYY" id="date_from" name="date_from" type="text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="To">To</label>
                                    <input class="form-control fc-datepicker pull-right"
                                        value="{{ \Carbon\Carbon::now()->format('m/d/Y') }}" placeholder="MM/DD/YYYY"
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


        <div class="row row-sm">
            <div class="col-sm-6 col-xl col-lg">
                <div class="card custom-card">
                    <div class="card-body dash1">
                        <div class="d-flex">
                            <p class="mb-1 tx-inverse">Total Sales</p>
                        </div>
                        <div>
                            <h3 id="total_sale"></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl col-lg">
                <div class="card custom-card">
                    <div class="card-body dash1">
                        <div class="d-flex">
                            <p class="mb-1 tx-inverse">Total Discount ₹</p>
                        </div>
                        <div>
                            <h3 id="total_discount_value"> </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl col-lg">
                <div class="card custom-card">
                    <div class="card-body dash1">
                        <div class="d-flex">
                            <p class="mb-1 tx-inverse">Total Discount %</p>
                        </div>
                        <div>
                            <h3 id="total_discount_percent"></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl col-lg">
                <div class="card custom-card">
                    <div class="card-body dash1">
                        <div class="d-flex">
                            <p class="mb-1 tx-inverse">Total Cancelled Bill</p>
                        </div>
                        <div>
                            <h3 id="total_cancelled_bill"></h3>
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
                                        <th>Estimate No</th>
                                        <th>Branch Name</th>
                                        <th> Status</th>
                                        <th>Date</th>
                                        <th>Customer Details</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Discount</th>
                                        <th>Grand Total</th>
                                        <th>Dues Amount</th>
                                        <th>Action</th>
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
                    url: '{{ route('admin.sale.getSaleList') }}',
                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                        d.branch_id = $('#branch_id').val();
                    },
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                        data: 'status',
                        name: 'status'
                    },

                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'client_name_and_mobile',
                        name: 'client_name_and_mobile'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },

                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'mrp',
                        name: 'mrp'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },

                    {
                        data: 'discount',
                        name: 'discount'
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total'
                    },
                    {
                        data: 'dues_amount',
                        name: 'dues_amount'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });



            table.on('xhr', function() {
                let data = table.ajax.json();
                console.log(data);
                $('#total_sale').html(' ' + data.total_sale.toFixed(2));
                $('#total_discount_value').html(' ' + data.total_discount_value.toFixed(2));
                $('#total_discount_percent').html(' ' + data.total_discount_percent.toFixed(2));
                $('#total_cancelled_bill').html(' ' + data.total_cancelled_bill);
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
                    name: "saleList",
                    filename: "saleList-" + today + ".xls",
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
