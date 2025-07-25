@extends('backend.branch.layouts.master')
@section('title')
    Branch Transfer
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Branch Transfer</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Branch Transfer</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Branch Transfer</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="javascript:void(0)"><i
                        class="fe fe-external-link"></i> Go Back</a>
            </div>
        </div>
        <!-- End Page Header -->
        <form action="{{ route('branch-transfer.store') }}" method="POST" enctype="multipart/form-data" id="submit_form">
            @method('POST')
            @csrf
            <div class="card custom-card overflow-hidden">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <p class="h5">From:</p>
                            <div style="width:60%">
                                <input class="form-control" placeholder="Name" name="from_name" id="from_name" type="text"
                                    value="{{ auth()->user()->branch->branch_name }}"><br>
                                <input class="form-control" placeholder="Email" name="from_email" id="from_email"
                                    type="email" value="{{ auth()->user()->branch->email }}"><br>
                                <input class="form-control" placeholder="Mobile No" name="from_mobile" id="from_mobile"
                                    type="text" value="{{ auth()->user()->branch->phone }}"><br>
                                <textarea placeholder="Address" class="form-control" name="from_address" id="from_address"
                                    rows="" cols="">{{ auth()->user()->branch->address }}</textarea>
                                <input name="from_branch_id" id="from_branch_id" type="hidden"
                                    value="{{ auth()->user()->branch->id }}">
                            </div>
                        </div>
                        <div class="col-lg-6 text-right">
                            <p class="h5">To:</p>
                            <div style="width:60%; float: right;">
                                <div class="form-group">
                                    <!-- <label for="mode">To </label> -->
                                    <select class="form-control" id="to_branch_id" name="to_branch_id">
                                        <option value="" selected>Select Branch</option>
                                        @if (!empty($branches))
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div id="branch_details"></div>
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
                                    href="#collapseExample" role="button">ADD BRANCH TRANSFER PRODUCTS</a>
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
                        <div class="ml-3 mr-3">
                            <label>Remark</label>
                            <textarea class="form-control" placeholder="" id="remark" name="remark" min="1" minlength="1"></textarea>
                            <br>
                            <br>
                        </div>
                        <div class="text-center">
                            <button class="btn ripple btn-primary" type="submit">Submit</button>
                            <a class="btn ripple btn-secondary" href="{{ route('branch.dashboard') }}">Go Back</a>
                            <br>
                            <br>
                        </div>
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
                                <label for="size">Available Product stock qty </label>
                                <input class="form-control" id="product_stock_qty" name="product_stock_qty" type="number"
                                    minlength="1" value="" readonly>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Category </label>
                                <input class="form-control" id="category_id" name="category_id" type="text"
                                     readonly>
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

            @include('backend.admin.messages.message-jquery-confirm-function')

            //code for get  show data
            $(document).on('change', '#to_branch_id', function() {
                let selected_value = $(this).val();

                if (selected_value !== '') {
                    //get show route
                    let url = "{{ route('branch-transfer.show', ':selected_value') }}";
                    url = url.replace(':selected_value', selected_value);
                    //get show route
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(data) {
                            let html = '<div class="form-group">\n' +
                                '<input class="form-control" type="text" placeholder="Branch Name" name="to_name" value="' +
                                data.branch_name + '" id="to_name" readonly>' +
                                '</div>\n' +
                                '<div class="form-group">\n' +
                                '<input class="form-control" type="text" placeholder="Email" name="to_email" value="' +
                                data.email + '" id="to_email" readonly>\n' +
                                '</div>\n' +
                                '<div class="form-group">\n' +
                                '<input class="form-control" type="text" placeholder="Phone" name="to_phone" value="' +
                                data.phone + '" id="to_phone" readonly>\n' +
                                '</div>\n' +
                                '<div class="form-group">\n' +
                                '<input class="form-control" type="text" placeholder="Address"  name="to_address" value="' +
                                data.address + '"  id="to_address" readonly>\n' +
                                '</div>';
                            $('#branch_details').html(html);
                        }
                    });
                } else {
                    $('#branch_details').html('');
                }
            });
            //code for show data


            $('#mobile').keyup(function(e) {
                if (/\D/g.test(this.value)) {
                    this.value = this.value.replace(/\D/g, '');
                }
            });

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
                $('#product_stock_qty').val('');
                $('#category_id').val('');
                
            });
            //code for open modal for create


            $('.livesearch').select2({
                placeholder: 'Select product',
                width: '100%',
                ajax: {
                    url: '{{ route('branch.branchDashboard.getBranchStockListSearch') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
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
                let product_id = $(this).val();
                if (product_id !== '') {

                    let url =
                        "{{ route('branch.branchTransfer.getBranchTransferProductDetail', ':product_id') }}";
                    url = url.replace(':product_id', product_id);

                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(data) {
                            $('#product_id').val(data.product.id);
                            $('#product_code').val(data.product.product_code);
                            $('#product_name').val(data.product.product_name);
                            $('#color').val(data.product.color_code);
                            $('#size').val(data.product.size);
                            $('#qty').val(1);
                            $('#product_stock_qty').val(data.product_stock_qty);
                            $('#category_id').val(data.product.category.category_name);
                        }
                    });
                } else {
                    $('#product_code').val('');
                    $('#product_name').val('');
                    $('#product_id').val('');
                    $('#color').val('');
                    $('#size').val('');
                    $('#qty').val('');
                    $('#product_stock_qty').val('');
                    $('#category_id').val('');
                }
            });
            //get product details

            let sl_no = 1;
            let sl_no_counter = 1;
            let initial_index_start_value = 0;
            let initial_index_start_value_counter = 0;
            let productArray = [];
            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();

                let product_id = $('#product_id').val();
                let product_name = $('#product_name').val();
                let product_code = $('#product_code').val();

                let color = $('#color').val();
                let size = $('#size').val();
                let qty = $('#qty').val();
                let product_stock_qty = $('#product_stock_qty').val();

                let category_id = $('#category_id').val();

                if (product_id === '') {
                    alertErrorMessage("{{ __('product id cannot be Empty') }}.")
                    return false;
                } else if (qty === '') {
                    alertErrorMessage("{{ __('qty cannot be Empty') }}.")
                    return false;
                } else if (parseInt(qty) > parseInt(product_stock_qty)) {
                    alertErrorMessage("{{ __('Stock Quantity not available') }}.")
                    return false;
                } else if (product_stock_qty <= 0) {
                    alertErrorMessage("Product is not available in stock");
                    return false;
                } else if (category_id === '') {
                    alertErrorMessage("{{ __('category cannot be Empty') }}.")
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
                        sl_no_counter = sl_no++;
                        initial_index_start_value_counter = initial_index_start_value++;
                        let html = '<tr id="acc' + initial_index_start_value_counter + '">' +
                            '<td>' + sl_no_counter + '</td>' +
                             '<td>' + category_id + '</td>' +
                             '<td>' + product_code + '</td>' +
                            '<td>' + product_code + '</td>' +
                            '<td>' + product_name + '</td>' +
                            '<td>' + color + '</td>' +
                            '<td>' + size + '</td>' +
                            '<td><input data-id="' + product_id + '" data-index="' +
                            initial_index_start_value_counter +
                            '" class="quantity_table" type="number" min="1" minlength="1" value="' + qty +
                            '" readonly></td>' +
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
                console.log(productArray);
            });
            //remove ready product

            //form submit
            $(document).on('submit', '#submit_form', function(e) {
                e.preventDefault();

                let from_name = $('#from_name').val();
                let from_email = $('#from_email').val();
                let from_mobile = $('#from_mobile').val();
                let from_address = $('#from_address').val();
                let from_branch_id = $('#from_branch_id').val();
                let to_branch_id = $('#to_branch_id').val();
                let to_name = $('#to_name').val();
                let to_email = $('#to_email').val();
                let to_phone = $('#to_phone').val();
                let to_address = $('#to_address').val();
                let remark = $('#remark').val();

                if (from_name === '') {
                    alertErrorMessage("{{ __('from name cannot be empty') }}.")
                    return false;
                } else if (from_email === '') {
                    alertErrorMessage("{{ __('from email cannot be empty') }}.")
                    return false;
                } else if (from_mobile === '') {
                    alertErrorMessage("{{ __('from mobile cannot be empty') }}.")
                    return false;
                } else if (from_address === '') {
                    alertErrorMessage("{{ __('from address cannot be empty') }}.")
                    return false;
                } else if (from_branch_id === '') {
                    alertErrorMessage("{{ __('from branch id cannot be empty') }}.")
                    return false;
                } else if (to_branch_id === '') {
                    alertErrorMessage("{{ __('to branch id cannot be empty') }}.")
                    return false;
                } else if (to_name === '') {
                    alertErrorMessage("{{ __('to name cannot be empty') }}.")
                    return false;
                } else if (to_email === '') {
                    alertErrorMessage("{{ __('to email cannot be empty') }}.")
                    return false;
                } else if (to_phone === '') {
                    alertErrorMessage("{{ __('to phone cannot be empty') }}.")
                    return false;
                } else if (to_address === '') {
                    alertErrorMessage("{{ __('to address cannot be empty') }}.")
                    return false;
                } else if (Object.entries(productArray).length === 0) {
                    alertErrorMessage("{{ __('branch transfer products cannot be empty') }}.")
                    return false;
                } else {


                    let productFormObject = {
                        from_name: from_name,
                        from_email: from_email,
                        from_mobile: from_mobile,
                        from_address: from_address,
                        from_branch_id: from_branch_id,
                        to_branch_id: to_branch_id,
                        to_name: to_name,
                        to_email: to_email,
                        to_phone: to_phone,
                        to_address: to_address,
                        products: productArray,
                        remark: remark,
                    };
                    let jsonArrayObject = JSON.stringify(productFormObject);
                    $.ajax({
                        type: "POST",
                        url: "{{ route('branch-transfer.store') }}",
                        data: jsonArrayObject,
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function(data) {
                            //console.log(data);
                            if (data.success) {
                                alertSuccessMessage(data.success);

                                $('.ajax_fetch_transfer_product').html('');
                                $('#branch_details').html('');
                                $('#to_branch_id').val('');
                                initial_index_start_value = 0;
                                initial_index_start_value_counter = 0;
                                productArray = [];
                                //location.reload();
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
                            console.log('error ' + data);
                        }
                    });
                }
            });
            //from submit

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
