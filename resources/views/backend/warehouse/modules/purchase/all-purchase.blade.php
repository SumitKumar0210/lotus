@extends('backend.warehouse.layouts.master')
@section('title')
    All Purchases
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">All Purchases </h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Purchase</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Purchases </li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="{{route('warehouse.dashboard')}}"><i
                        class="fe fe-external-link"></i> Go Back</a>
                <a class="btn ripple btn-primary exportToExcel1" href="javascript:void(0)"><i
                        class="fe fe-external-link"></i>Export</a>
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
                                    <th class="wd-20p">Purchase No</th>
                                    <th class="wd-20p">Created at</th>
                                    <th class="wd-20p">Brand Name</th>
                                    <th class="wd-20p">Product Name</th>
                                    <th class="wd-20p">Vendor Name</th>
                                    <th class="wd-20p">Model No</th>
                                    <th class="wd-20p">Category</th>
                                    <th class="wd-20p">Colour</th>
                                    <th class="wd-20p">Size</th>
                                    <th class="wd-20p">Qty</th>
                                    <th class="wd-20p">Date</th>
                                    <th class="wd-20p">Bill Number</th>
                                    <th class="wd-20p">Remarks</th>

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
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            let table = $('#product_table').DataTable({
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                pageLength: 25,
                processing: true,
                serverSide: true,
                responsive:true,
                ajax:
                    {
                        url: '{{ route("warehouse.purchase.getWarehouseAllPurchaseList") }}',
                    },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'purchase_no', name: 'purchase_no'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'brand_name', name: 'brand_name'},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'vendor_name', name: 'vendor_name'},
                    {data: 'product_code', name: 'product_code'},
                    {data: 'category', name: 'category'},
                    {data: 'color', name: 'color'},
                    {data: 'size', name: 'size'},
                    {data: 'qty', name: 'qty'},
                    {data: 'date', name: 'date'},
                    {data: 'bill_number', name: 'bill_number'},
                    {data: 'remarks', name: 'remarks'},
                ],
            });

            //get current date
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1;
            var yyyy = today.getFullYear();
            if(dd<10)
            {
                dd='0'+dd;
            }
            if(mm<10)
            {
                mm='0'+mm;
            }
            today = mm+'-'+dd+'-'+yyyy;
            //get current date

            $(document).on('click', '.exportToExcel1', function (e) {
                $("#product_table").table2excel({
                    exclude: ".noExl",
                    name: "allPurchase",
                    filename: "allPurchase-" + today + ".xls",
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
