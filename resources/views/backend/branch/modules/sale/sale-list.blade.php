@extends('backend.branch.layouts.master')
@section('title')
    Sale List
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Sale List</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sale List</li>
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

            {{-- let table = $('#product_table').DataTable({ --}}
            {{-- lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]], --}}
            {{-- responsive: true, --}}
            {{-- processing: true, --}}
            {{-- serverSide: true, --}}
            {{-- ajax: "{{route('branch.sale.getSaleList')}}", --}}
            {{-- columns: [ --}}
            {{-- {data: 'DT_RowIndex', name: 'DT_RowIndex'}, --}}
            {{-- {data: 'estimate_no', name: 'estimate_no'}, --}}
            {{-- {data: 'branch_name', name: 'branch_name'}, --}}
            {{-- {data: 'client_name', name: 'client_name'}, --}}
            {{-- {data: 'client_mobile', name: 'client_mobile'}, --}}
            {{-- {data: 'client_address', name: 'client_address'}, --}}
            {{-- {data: 'delivery_date', name: 'delivery_date'}, --}}
            {{-- {data: 'product', name: 'product'}, --}}
            {{-- {data: 'qty', name: 'qty'}, --}}
            {{-- {data: 'product_code', name: 'product_code'}, --}}
            {{-- {data: 'color', name: 'color'}, --}}
            {{-- {data: 'size', name: 'size'}, --}}
            {{-- {data: 'quantity', name: 'quantity'}, --}}
            {{-- {data: 'action', name: 'action', orderable: false, searchable: false}, --}}
            {{-- ], --}}
            {{-- }); --}}



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
                    url: '{{ route('branch.sale.getSaleList') }}',
                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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
