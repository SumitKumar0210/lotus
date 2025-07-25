@extends('backend.admin.layouts.master')
@section('title')
    Sale List Today
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Today's Sale List</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Today's Sale List</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="javascript:void(0)"><i
                        class="fe fe-external-link"></i> Export</a>
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
                                        <th>Sr No.</th>
                                        <th>Estimate No</th>
                                        <th>Branch Name</th>
                                        <th>Customer</th>
                                        <th>Mobile No</th>
                                        <th>Address</th>
                                        <th>Delivery date</th>
                                        <th>Product Name</th>
                                        <th>Model</th>
                                        <th>Colour</th>
                                        <th>Size</th>
                                        <th>Qty</th>
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

            let table = $('#product_table').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.sale.getSaleListToday') }}",
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
                        data: 'delivery_date',
                        name: 'delivery_date'
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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
                    name: "TodaySaleList",
                    filename: "TodaySaleList-" + today + ".xls",
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
