@extends('backend.branch.layouts.master')
@section('title')
Consolidate Report
@endsection
@section('extra-css')
@endsection
@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5"> Consolidate Report</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Report</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Consolidate Report</li>
            </ol>
        </div>
        <div class="btn btn-list">
            <a class="btn ripple btn-primary exportToExcel" href="javascript:void(0)"><i
                    class="fe fe-external-link"></i>
                Export</a>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row">


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="To">Product Name</label>
                                <div class="input-group mb-2">
                                    <select class="livesearch form-control" name="product_name" id="product_name"
                                        autocomplete="off"></select>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-2 ">
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
                                    <th class="wd-20p">Brand Name</th>
                                    <th class="wd-20p">Category</th>
                                    <th class="wd-20p">Product Code</th>
                                    <th class="wd-20p">Product Name</th>
                                    <th class="wd-20p">Color</th>
                                    <th class="wd-20p">Size</th>
                                    <th class="wd-20p">Qty</th>
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
                    url: '{{ route('branch.consolidate.getConsolidateList') }}',
                    data: function(d) {
                        d.product_id = $('#product_name').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                    data: 'brand_name',
                    name: 'brand_name'
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
                        data: 'product_name',
                        name: 'product_name'
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

            $('#btnFiterSubmitSearch').click(function(e) {
                e.preventDefault;
                e.stopPropagation();
                $('#product_table').DataTable().draw(true);
                return false;
            });

            $('.livesearch').select2({
                placeholder: 'Select product',
                width: '100%',
                ajax: {
                    url: '{{ route('branch.consolidate.getConsolidateListSearch') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.product_name,
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

            $(document).on('click', '.exportToExcel', function(e) {
                $("#product_table").table2excel({
                    exclude: ".noExl",
                    name: "consolidateReport",
                    filename: "consolidateReport-" + today + ".xls",
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