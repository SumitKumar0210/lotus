@extends('backend.warehouse.layouts.master')
@section('title')
    Stock Report
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Stock Report</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Stock Report</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Stock Report</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="{{ route('warehouse.dashboard') }}"><i
                        class="fe fe-external-link"></i> Go Back</a>
                <a class="btn ripple btn-primary exportToExcel1" href="javascript:void(0)"><i
                        class="fe fe-external-link"></i>Export</a>
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
                                    <label for="from">Product Code</label>
                                    <select class="livesearch form-control" name="product_name" id="product_name"
                                        autocomplete="off"></select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="from">From</label>
                                    <input class="form-control fc-datepickerClass" placeholder="" id="date_from"
                                        name="date_from" type="text" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="To">To</label>
                                    <input class="form-control fc-datepickerClass" placeholder="" id="date_to"
                                        name="date_to" type="text" autocomplete="off">
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
                                        <th class="wd-20p">Sr No.</th>
                                        <th class="wd-20p">Category</th>
                                        <th class="wd-20p">Model No</th>
                                        {{-- <th class="wd-20p">Product Name</th> --}}
                                        <th class="wd-20p">Brand Name</th>


                                        <th class="wd-20p">Colour</th>
                                        {{-- <th class="wd-20p">Size</th> --}}
                                        <th class="wd-20p">Opening Qty</th>
                                        {{-- <th class="wd-20p">Closing Qty</th>
                                        <th class="wd-20p">Created at</th> --}}
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
                pageLength: 25,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('warehouse.branchStock.getBranchStockList') }}',
                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.date_to = $('#date_to').val();
                        d.product_id = $('#product_name').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'product_code',
                        name: 'product_code'
                    },
                    // {
                    //     data: 'product_name',
                    //     name: 'product_name'
                    // },
                    {
                        data: 'brand_name',
                        name: 'brand_name'
                    },


                    {
                        data: 'color',
                        name: 'color'
                    },
                    // {
                    //     data: 'size',
                    //     name: 'size'
                    // },
                    {
                        data: 'opening_qty',
                        name: 'opening_quantity'
                    },
                    // {
                    //     data: 'closing_qty',
                    //     name: 'closing_qty'
                    // },
                    // {
                    //     data: 'created_at',
                    //     name: 'created_at'
                    // },
                ],
            });

            $('#btnFiterSubmitSearch').click(function(e) {
                e.preventDefault;
                e.stopPropagation();
                $('#product_table').DataTable().draw(true);
                return false;
            });



            $('.fc-datepickerClass').datepicker({
                showOtherMonths: true,
                selectOtherMonths: true
            });


            $('.livesearch').select2({
                placeholder: 'Select product',
                width: '100%',
                ajax: {
                    url: '{{ route('warehouse.branchStock.getBranchStockListSearch') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.product_code,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
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

            $(document).on('click', '.exportToExcel1', function(e) {
                $("#product_table").table2excel({
                    exclude: ".noExl",
                    name: "stockReport",
                    filename: "stockReport-" + today + ".xls",
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
