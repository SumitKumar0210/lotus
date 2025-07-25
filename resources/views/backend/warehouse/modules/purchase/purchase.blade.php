@extends('backend.warehouse.layouts.master')
@section('title')
    Purchase
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Purchase</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Purchase</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Purchase</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="{{ route('warehouse.dashboard') }}"><i
                        class="fe fe-external-link"></i> Go Back</a>
            </div>
        </div>
        <!-- End Page Header -->
        <form action="{{ route('purchase-warehouse.store') }}" method="POST" enctype="multipart/form-data" id="submit_form">
            @method('POST')
            @csrf

            <div class="card custom-card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <p class="h5 mt-2">Purchase No: {{ $purchase_no }}</p>
                        </div>
                        <div class="col-lg-6 text-right">
                            <div style="width:64.5%; float: right;">
                                <div class="form-group m-0">

                                    <input name="date" class="form-control  pull-right" id="date"
                                        value="{{ old('date') }}" placeholder="MM/DD/YYYY" required>
                                </div>
                            </div>
                        </div>
                        <input name="from_warehouse_id" id="from_warehouse_id" type="hidden"
                            value="{{ $warehouse_detail->id }}">
                    </div>

                    <br>
                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group m-0">
                                <input name="bill_number" class="form-control" id="bill_number"
                                    value="{{ old('bill_number') }}" placeholder="Bill Number" required>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group m-0">
                                <input name="vendor_name" class="form-control" id="vendor_name"
                                    value="{{ old('vendor_name') }}" placeholder="Vendor Name" required>
                            </div>
                        </div>


                        <div class="col-lg-4">
                            <div class="form-group m-0">
                                <textarea style="height: 38px;" name="remarks" class="form-control" id="remarks"
                                    placeholder="Remarks"></textarea>
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
                            <div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
                                <a aria-controls="collapseExample" aria-expanded="true"
                                    class="btn ripple btn-primary btn-block text-left" data-toggle="collapse"
                                    href="#collapseExample" role="button">ADD PURCHASE PRODUCTS</a>
                                <div class="collapse mg-t-5 show" id="collapseExample">
                                    <div class="card-body">
                                        <div class="text-right mb-2 ">
                                            <a class="btn ripple btn-info btn-sm createProduct"
                                                href="javascript:void(0);"><i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Sr No.</th>
                                                        <th>Category</th>
                                                        <th>Product Code</th>
                                                        <th>Product Name</th>
                                                        <th>Colour</th>
                                                        <th>Size</th>
                                                        <th>Qty</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="ajax_fetch_transfer_product">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center" style="margin-bottom: 100px;">
                            <button class="btn ripple btn-primary" type="submit">Submit</button>
                            <a class="btn ripple btn-secondary" href="{{ route('branch.dashboard') }}">Go Back</a>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
            <!-- End Row -->
        </form>
    </div>
    <div class="modal" id="modal_demo1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Add Products</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code_choose">Product Code (model no)</label>
                                <!--<select class="form-control" id="product_code_choose" name="product_code_choose" required>-->
                                <!--    <option value="" selected>Choose Product Code</option>-->
                                <!--    @if (!empty($products))-->
                                <!--        @foreach ($products as $product)-->
                                <!--            <option value="{{ $product->id }}">{{ $product->product_code }}</option>-->
                                <!--        @endforeach-->
                                <!--    @endif-->
                                <!--</select>-->
                                
                                
                                
                                <select class="livesearch form-control" name="product_code_choose" id="product_code_choose"
                                                autocomplete="off" required></select>
                                
                                
                            </div>
                        </div>

                        <div class="col-md-6">
                            <input type="hidden" id="product_id" value="">
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Code</label>
                                <input class="form-control" placeholder="" name="product_code" id="product_code" type="text"
                                    readonly>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input class="form-control" placeholder="" name="product_name" id="product_name" type="text"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code">Color</label>
                                <input class="form-control" placeholder="" name="color" id="color" type="text" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color_code">Size </label><br>
                                <input class="form-control" placeholder="" id="size" name="size" type="text" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Qty </label>
                                <input class="form-control" placeholder="" id="qty" name="qty" type="number" min="1"
                                    minlength="1">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Rate </label>
                                <input class="form-control" placeholder="" id="mrp" name="mrp" type="number" readonly>
                            </div>
                        </div>
                        
                        
                         <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <input class="form-control" id="category" name="category" type="text" readonly>
                            </div>
                        </div>
                        
                        
                        

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary createProductButton" data-count="0" type="button">Submit
                    </button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
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

            @include('backend.warehouse.messages.message-jquery-confirm-function')


            //code for open modal for create
            $(document).on('click', '.createProduct', function() {
                $('#modal_demo1').modal('show');
                $('#product_code_choose').val('');
                $('#product_id').val('');
                $('#product_code').val('');
                $('#product_name').val('');
                $('#color').val('');
                $('#size').val('');
                $('#qty').val('');
                $('#mrp').val('');
                $('#category').val('');
            });
            //code for open modal for create


            $('.livesearch').select2({
                placeholder: 'Select product',
                width: '100%',
                ajax: {
                    url: '{{ route('warehouse.wareHouseDashboard.getWarehouseStockListSearch') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                // return {
                                //     text: item.product_code + ', ' + item.product_name,
                                //     id: item.id
                                // }
                                return {
                                    text: item.product_code + ', ' + item.product_name + ', '+ item.size+ ', '+ item.color_code + ', '+ item.category.category_name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });


            //get product details
            $(document).on('change', '#product_code_choose', function() {
                let product_code_choose = $(this).val();
                if (product_code_choose !== '') {

                    let url =
                        "{{ route('warehouse.purchase.getBranchTransferProductDetail', ':product_code_choose') }}";
                    url = url.replace(':product_code_choose', product_code_choose);

                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(data) {
                            $('#product_id').val(data.id);
                            $('#product_code').val(data.product_code);
                            $('#product_name').val(data.product_name);
                            $('#color').val(data.color_code);
                            $('#size').val(data.size);
                            $('#qty').val(1);
                            $('#mrp').val(data.maximum_retail_price);
                            $('#category').val(data.category.category_name);
                            
                        }
                    });
                } else {
                    $('#product_id').val('');
                    $('#product_code').val('');
                    $('#product_name').val('');
                    $('#color').val('');
                    $('#size').val('');
                    $('#qty').val('');
                    $('#mrp').val('');
                    $('#category').val('');
                }
            });
            //get product details

            let initial_index_start_value = 0;
            let initial_index_start_value_counter = 0;
            let productArray = [];
            let sl_no = 1;
            let sl_no_counter = 1;

            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();

                let product_id = $('#product_id').val();
                let product_name = $('#product_name').val();
                let product_code = $('#product_code').val();
                let color = $('#color').val();
                let size = $('#size').val();
                let qty = $('#qty').val();
                let mrp = $('#mrp').val();
                let category = $('#category').val();

                if (product_id === '') {
                    alertErrorMessage("{{ __('product id cannot be Empty') }}.")
                    return false;
                } else if (qty === '') {
                    alertErrorMessage("{{ __('qty cannot be Empty') }}.")
                    return false;
                } else {

                    //check already added or not
                    function findValueInArrayObject(product_id) {
                        return productArray.some(function(el) {
                            return el.product_id === product_id;
                        });
                    }

                    let checkAccessory = findValueInArrayObject(product_id);
                    //check already added or not

                    if (checkAccessory === true) {
                        alertErrorMessage("{{ __('This product is already added') }}.");
                        return false;
                    } else {

                        initial_index_start_value_counter = initial_index_start_value++;
                        sl_no_counter = sl_no++;

                        let html = '<tr id="acc' + initial_index_start_value_counter + '">' +
                            '<td>' + sl_no_counter + '</td>' +
                            '<td>' + category + '</td>' +
                            '<td>' + product_code + '</td>' +
                            '<td>' + product_name + '</td>' +
                            '<td>' + color + '</td>' +
                            '<td>' + size + '</td>' +
                            '<td><input data-id="' + product_id + '" data-index="' +
                            initial_index_start_value_counter +
                            '" class="quantity_table" type="number" min="1" minlength="1" value="' + qty +
                            '"></td>' +
                            '<td><a href="javascript:void(0)" data-index="' +
                            initial_index_start_value_counter + '"  data-product_id ="' + product_id +
                            '"  class="btn-danger btn-sm removeProductButton"><i class="fa fa-trash"></i></a></td>' +
                            '</tr>';
                        $('.ajax_fetch_transfer_product').append(html);

                        let productObject = {
                            product_id: product_id,
                            product_name: product_name,
                            product_code: product_code,
                            color: color,
                            size: size,
                            qty: qty,
                            mrp: mrp,
                            index: initial_index_start_value_counter,
                        };
                        productArray.push(productObject);

                        toastr["success"]("Product added successfully", "Notification")
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        $('#modal_demo1').modal('hide');
                    }
                }
            });
            // code for add to ready product

            //quantity change
            $(document).on('change keyup keydown', '.quantity_table', function(e) {
                e.preventDefault();
                let product_id = $(this).data('id');
                let this_value = $(this).val();
                let this_index = $(this).data('index');
                objIndex = productArray.findIndex((obj => obj.product_id == product_id));
                productArray[objIndex].qty = this_value;
            });
            //quantity change


            //remove ready product
            $(document).on('click', '.removeProductButton', function(e) {
                e.preventDefault();

                let product_id = $(this).data("product_id");
                let index = $(this).data("index");

                for (let i = productArray.length - 1; i >= 0; --i) {
                    if (productArray[i].product_id == product_id) {
                        productArray.splice(i, 1);
                    }
                }
                $('#acc' + index).remove();
                sl_no_counter = sl_no--;

                toastr["warning"]("Product removed successfully", "Notification")
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }

            });
            //remove ready product

            //form submit
            $(document).on('submit', '#submit_form', function(e) {
                e.preventDefault();


                let from_warehouse_id = $('#from_warehouse_id').val();
                let date = $('#date').val();



                let bill_number = $('#bill_number').val();
                let vendor_name = $('#vendor_name').val();
                let remarks = $('#remarks').val();


                if (from_warehouse_id === '') {
                    alertErrorMessage("{{ __('from_warehouse_id cannot be empty') }}.")
                    return false;
                } else if (date === '') {
                    alertErrorMessage("{{ __('Date cannot be empty') }}.")
                    return false;
                } else if (Object.entries(productArray).length === 0) {
                    alertErrorMessage("{{ __('purchase products cannot be empty') }}.")
                    return false;
                } else if (bill_number === '') {
                    alertErrorMessage("{{ __('Bill number cannot be empty') }}.")
                    return false;
                } else if (vendor_name === '') {
                    alertErrorMessage("{{ __('Vendor name cannot be empty') }}.")
                    return false;
                } else {

                    let productFormObject = {
                        from_warehouse_id: from_warehouse_id,
                        date: date,

                        bill_number: bill_number,
                        vendor_name: vendor_name,
                        remarks: remarks,

                        products: productArray
                    };
                    let jsonArrayObject = JSON.stringify(productFormObject);
                    $.ajax({
                        type: "POST",
                        url: "{{ route('purchase-warehouse.store') }}",
                        data: jsonArrayObject,
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function(data) {

                            if (data.success) {
                                alertSuccessMessage(data.success);
                                $('.ajax_fetch_transfer_product').html('');
                                initial_index_start_value = 0;
                                initial_index_start_value_counter = 0;
                                productArray = [];

                                setTimeout(function() {
                                    location.reload();
                                }, 3000);

                            } else if (data.errors_success) {
                                alertErrorMessage(data.errors_success)
                                return false;
                            } else if (data.errors_validation) {
                                let html = '';
                                for (let count = 0; count < data.errors_validation
                                    .length; count++) {
                                    html += '<p>' + data.errors_validation[count] + '</p>';
                                }
                                alertErrorMessage(html)
                                return false;
                            } else {
                                alertErrorMessage("Something Went Wrong")
                                return false;
                            }
                        },
                        error: function(data) {
                            alertErrorMessage("Something Went Wrong Dev")

                        }
                    });
                }
            });
            //from submit


            $("#date").datepicker().datepicker("setDate", new Date());


            $('#submit_form').on('keyup keypress', function(e) {
                let keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });
            
            
            
            
            
        });
    </script>
@endsection
