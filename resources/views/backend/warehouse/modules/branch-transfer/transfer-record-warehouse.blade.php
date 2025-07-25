@extends('backend.warehouse.layouts.master')
@section('title')
    TRANSFER RECORD
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Transfer Record</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Transfer Record</li>
                </ol>
            </div>
            <div class="d-flex">
                <div class="mr-2">
                    <a class="btn ripple btn-outline-primary dropdown-toggle mb-0" href="#" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="true">
                        <i class="fe fe-external-link"></i> Export <i class="fas fa-caret-down ml-1"></i>
                    </a>
                    <div class="dropdown-menu tx-13">
                        <a class="dropdown-item exportToExcel2" href="javascript:void(0);"><i
                                class="far fa-image mr-2"></i>Export</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card custom-card main-content-body-profile">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="product_table_2" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Out Qty</th>
                                        <th>Returned Qty</th>
                                        <th>Created At</th>
                                        <th>Product</th>
										<th>Category</th>
										
                                        <th>Model</th>
										<th>Size</th>
                                        <th>Remarks</th>
                                        <th>Return Reason</th>

                                        <th>Created by</th>
                                        <th>Accepted by</th>
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



            let table2 = $('#product_table_2').DataTable({
                lengthMenu: [
                    [10, 25, 100, 250,500,1000, -1],
                    [10, 25, 100, 250, 500, 1000]
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse.branchTransfer.getTransferRecordWarehouseList') }}",
                columns: [
					{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
						orderable: false,
                        searchable: false
                    },
                    {
                        data: 'from',
                        name: 'from'
                    },
                    {
                        data: 'to',
                        name: 'to'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'returned_qty',
                        name: 'returned_qty'
                    },

                    {
                        data: 'created_at',
                        name: 'created_at'
                    },

                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
					{
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'model_no',
                        name: 'model_no'
                    },
					{
                        data: 'size',
                        name: 'size'
                    },
                     {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'return_reason',
                        name: 'return_reason'
                    },

                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'accepted_by',
                        name: 'accepted_by'
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



            $(document).on('click', '.exportToExcel2', function(e) {
                $("#product_table_2").table2excel({
                    exclude: ".noExl",
                    name: "transferRecordWarehouse",
                    filename: "transferRecordWarehouse-" + today + ".xls",
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
