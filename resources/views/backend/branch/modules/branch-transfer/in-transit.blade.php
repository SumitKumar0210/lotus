@extends('backend.branch.layouts.master')
@section('title')
    IN TRANSIT
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">IN TRANSIT</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">IN TRANSIT</li>
                </ol>
            </div>
            <div class="d-flex">
                <div class="mr-2">
                    <a class="btn ripple btn-outline-primary dropdown-toggle mb-0" href="#" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="true">
                        <i class="fe fe-external-link"></i> Export <i class="fas fa-caret-down ml-1"></i>
                    </a>
                    <div class="dropdown-menu tx-13">
                        <a class="dropdown-item exportToExcel3" href="javascript:void(0);"><i
                                class="far fa-file-excel mr-2"></i>Export</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <!--End  Row -->
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card custom-card main-content-body-profile">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table" id="product_table_3" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Branch To</th>
                                        <th>Created By</th>
                                        <th>Product Name</th>
                                        <th>Product Code</th>
                                        <th>Colour</th>
                                        <th>Size</th>
                                        <th>Qty</th>
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


            let table3 = $('#product_table_3').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('branch.branchTransfer.getInTransitList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'branch_to',
                        name: 'branch_to'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
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
                        data: 'qty',
                        name: 'qty'
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


            $(document).on('click', '.exportToExcel3', function(e) {
                $("#product_table_3").table2excel({
                    exclude: ".noExl",
                    name: "branchInTransit",
                    filename: "branchInTransit-" + today + ".xls",
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
