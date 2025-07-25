@extends('backend.branch.layouts.master')
@section('title')
    Daily Sale List
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Daily Sale List</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Sale</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daily Sale List</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="javascript:void(0)">
                    <i class="fe fe-external-link"></i> Export</a>
            </div>
        </div>
        <!-- End Page Header -->


        <!-- Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="product_table">
                                <thead>
                                    <tr>
                                        <th class="wd-20p">Sr No.</th>
                                        <th class="wd-20p">Estimate No</th>
                                        <th class="wd-20p">Branch Name</th>
                                        <th class="wd-20p">Customer</th>
                                        <th class="wd-20p">Mobile No</th>
                                        <th class="wd-20p">Address</th>


                                        <th class="wd-20p">Bill Amount</th>
                                        <th class="wd-20p">Due Amount</th>

                                        <th class="wd-20p">Expected date(delivery)</th>
                                        <th class="wd-20p">Product Name</th>
                                        <th class="wd-20p">Model</th>
                                        <th class="wd-25p">Colour</th>
                                        <th class="wd-25p">Size</th>
                                        <th class="wd-25p">Qty</th>
                                        <th class="wd-25p">Remarks</th>
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
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('branch.sale.getDailySaleList') }}",
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
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'client_mobile',
                        name: 'client_mobile'
                    },
                    {
                        data: 'client_address',
                        name: 'client_address'
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
                        data: 'expected_delivery_date',
                        name: 'expected_delivery_date'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'product_code',
                        name: 'product_code'
                    },
                    {
                        data: 'color',
                        name: 'color'
                    },
                    {
                        data: 'size',
                        name: 'size'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                ],
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
                    name: "dsrList",
                    filename: "dsrList-" + today + ".xls",
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
