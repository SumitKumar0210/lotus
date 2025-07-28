@extends('backend.warehouse.layouts.master')
@section('title')
    Product
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Product</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Create Product</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-secondary createProduct" href="javascript:void(0)"><i class="fa fa-plus"></i>
                    Create</a>
                <a class="btn ripple btn-primary exportToExcel" href="javascript:void(0)"><i class="fe fe-external-link"></i>
                    Export</a>
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
                                        <th class="wd-20p">Category</th>
                                        <th class="wd-20p">Product Code</th>
                                        <th class="wd-20p">Product Name</th>
                                        <th class="wd-20p">Brand / Vendor</th>
                                       
                                        <!--<th class="wd-20p">Description</th>-->
                                        <th class="wd-20p">Colour</th>
                                        <th class="wd-20p">Size</th>
                                        <!--<th class="wd-20p">Status</th>-->
                                        <!--<th class="wd-20p">RATE</th>-->
                                        <th class="wd-20p">Date</th>
                                        <th class="wd-25p">Action</th>
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
    <!-- CREATE MODAL START-->
    <div class="modal" id="modal_demo1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Create Product</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Name*</label>
                                <input class="form-control" placeholder="" name="product_name" id="product_name"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code">Product Code*</label>
                                <input class="form-control" placeholder="" name="product_code" id="product_code"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand_id">Brand*</label>
                                <select class="form-control select2-no-search" id="brand_id" name="brand_id">
                                    <option value="" selected>
                                        Choose Brand
                                    </option>
                                    @if (!empty($brands))
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">Category*</label>
                                <select class="form-control select2-no-search" id="category_id" name="category_id">
                                    <option value="" selected>
                                        Choose Category
                                    </option>
                                    @if (!empty($categories))
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color_code">Color Code </label><br>
                                <input class="form-control" placeholder="" id="color_code" name="color_code" type="text">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Size </label>
                                <input class="form-control" placeholder="" id="size" name="size" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maximum_retail_price">RATE*</label>
                                <input class="form-control" id="maximum_retail_price" name="maximum_retail_price"
                                    type="text" value="1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description </label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary createProductButton" type="button">Submit</button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- CREATE MODAL END-->
    <!-- EDIT MODAL START-->
    <div class="modal" id="modal_demo2">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Edit Product</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="update_id" id="update_id" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name_edit">Product Name*</label>
                                <input class="form-control" placeholder="" name="product_name_edit" id="product_name_edit"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code_edit">Product Code*</label>
                                <input class="form-control" placeholder="" name="product_code_edit" id="product_code_edit"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand_id_edit">Brand*</label>
                                <select class="form-control select2-no-search" id="brand_id_edit" name="brand_id_edit">
                                    @if (!empty($brands))
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id_edit">Category*</label>
                                <select class="form-control select2-no-search" id="category_id_edit"
                                    name="category_id_edit">
                                    @if (!empty($categories))
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color_code_edit">Color Code </label>
                                <input class="form-control" placeholder="" id="color_code_edit" name="color_code_edit"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size_edit">Size </label>
                                <input class="form-control" placeholder="" id="size_edit" name="size_edit" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maximum_retail_price_edit">RATE*</label>
                                <input class="form-control" id="maximum_retail_price_edit"
                                    name="maximum_retail_price_edit" type="text" value="1">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description_edit">Description </label>
                                <textarea class="form-control" placeholder="Description" id="description_edit"
                                    name="description_edit" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary updateProductButton" type="button">Update</button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                </div>
            </div>


        </div>
    </div>
    <!-- EDIT MODAL END-->
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
                order : [[1, "asc" ]],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse-product.getWarehouseProductList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'category_id',
                        name: 'category_id'
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
                        data: 'brand_id',
                        name: 'brand_id'
                    },
                    
                    // {
                    //     data: 'description',
                    //     name: 'description'
                    // },
                    
                     {
                         data: 'color_code',
                         name: 'color_code'
                     },
                     {
                         data: 'size',
                         name: 'size'
                    },
                     {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    
                    // {
                    //     data: 'status',
                    //     name: 'status'
                    // },
                    // {
                    //     data: 'maximum_retail_price',
                    //     name: 'maximum_retail_price'
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });


            @include('backend.warehouse.messages.message-jquery-confirm-function')

            //code for open modal for create
            $(document).on('click', '.createProduct', function() {
                $('#modal_demo1').modal('show');
                $('#product_name').val('');
                $('#product_code').val('');
                $('#brand_id').val('');
                $('#category_id').val('');
                $('#description').val('');
                $('#color_code').val('');
                $('#size').val('');
                $('#maximum_retail_price').val(1);
            });
            //code for open modal for create


            //code for create data
            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();

                let product_name = $('#product_name').val();
                let product_code = $('#product_code').val();
                let brand_id = $('#brand_id').val();
                let category_id = $('#category_id').val();
                let description = $('#description').val();
                let color_code = $('#color_code').val();
                let size = $('#size').val();
                let maximum_retail_price = $('#maximum_retail_price').val();

                if (product_name === '') {
                    alertErrorMessage("{{ __('product name cannot be empty') }}.")
                    return false;
                } else if (product_code === '') {
                    alertErrorMessage("{{ __('product code  cannot be empty') }}.")
                    return false;
                } else if (brand_id === '') {
                    alertErrorMessage("{{ __('brand name cannot be empty') }}.")
                    return false;
                } else if (category_id === '') {
                    alertErrorMessage("{{ __('category cannot be empty') }}.")
                    return false;
                } else if (maximum_retail_price === '') {
                    alertErrorMessage("{{ __('rate cannot be empty') }}.")
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('product_name', product_name);
                    formData.append('product_code', product_code);
                    formData.append('brand_id', brand_id);
                    formData.append('category_id', category_id);
                    formData.append('description', description);
                    formData.append('color_code', color_code);
                    formData.append('maximum_retail_price', maximum_retail_price);
                    formData.append('size', size);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('warehouse-product.store') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            console.log(data);
                            if (data.success) {
                                alertSuccessMessage(data.success)
                                table.draw();
                                $('#modal_demo1').modal('hide');
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
                        }
                    });
                }
            });
            //code for create data


            //code for get  show data
            $(document).on('click', '.editProduct', function() {
                $('#modal_demo2').modal('show');
                let update_id = $(this).data('id');
                $('#update_id').val(update_id);

                //get show route
                let url = "{{ route('warehouse-product.show', ':update_id') }}";
                url = url.replace(':update_id', update_id);
                //get show route
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data) {
                        console.log(data);
                        $('#product_name_edit').val(data.product_name);
                        $('#product_code_edit').val(data.product_code);
                        $('#brand_id_edit').val(data.brand_id);
                        $('#category_id_edit').val(data.category_id);
                        $('#description_edit').val(data.description);
                        $('#color_code_edit').val(data.color_code);
                        $('#maximum_retail_price_edit').val(data.maximum_retail_price);
                        $('#size_edit').val(data.size);
                    }
                });
            });
            //code for show data


            //code for update data
            $(document).on('click', '.updateProductButton', function(e) {
                e.preventDefault();

                let update_id = $('#update_id').val();
                let product_name = $('#product_name_edit').val();
                let product_code = $('#product_code_edit').val();
                let brand_id = $('#brand_id_edit').val();
                let category_id = $('#category_id_edit').val();
                let description = $('#description_edit').val();
                let color_code = $('#color_code_edit').val();
                let maximum_retail_price = $('#maximum_retail_price_edit').val();
                // let minimum_stock_quantity = $('#minimum_stock_quantity_edit').val();
                let size = $('#size_edit').val();

                if (product_name === '') {
                    alertErrorMessage("{{ __('product name cannot be empty') }}.")
                    return false;
                } else if (product_code === '') {
                    alertErrorMessage("{{ __('product code  cannot be empty') }}.")
                    return false;
                } else if (brand_id === '') {
                    alertErrorMessage("{{ __('brand name cannot be empty') }}.")
                    return false;
                } else if (category_id === '') {
                    alertErrorMessage("{{ __('category cannot be empty') }}.")
                    return false;
                } else if (maximum_retail_price === '') {
                    alertErrorMessage("{{ __('rate cannot be empty') }}.")
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('product_name', product_name);
                    formData.append('product_code', product_code);
                    formData.append('brand_id', brand_id);
                    formData.append('category_id', category_id);
                    formData.append('description', description);
                    formData.append('color_code', color_code);
                    formData.append('maximum_retail_price', maximum_retail_price);
                    formData.append('size', size);
                    formData.append('_method', 'patch');

                    //get update route
                    let url = "{{ route('warehouse-product.update', ':update_id') }}";
                    url = url.replace(':update_id', update_id);
                    //get update route

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            console.log(data);
                            if (data.success) {
                                alertSuccessMessage(data.success)
                                table.draw();
                                $('#update_id').val('');
                                $('#modal_demo2').modal('hide');

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
                                alertErrorMessage("{{ __('Something Went Wrong') }}.")
                                return false;
                            }
                        }
                    });
                }
            });
            //code for reset form data or update data


            //code for delete data
            $(document).on('click', '.deleteProduct', function() {
                let delete_id = $(this).data('id');
                $.confirm({
                    title: "{{ __('Hello!') }}",
                    content: "{{ __('Are you sure to remove this product?') }}",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {

                                let url =
                                    "{{ route('warehouse-product.destroy', ':delete_id') }}";
                                url = url.replace(':delete_id', delete_id);

                                $.ajax({
                                    url: url,
                                    type: "DELETE",
                                    data: {},
                                    cache: false,
                                    dataType: "json",
                                    success: function(data) {
                                        if (data.success) {
                                            table.draw();
                                            alertSuccessMessage(data.success)
                                        } else if (data.errors) {
                                            alertErrorMessage(data.errors)
                                        } else {
                                            alertErrorMessage(
                                                "{{ __('Something Went Wrong') }}."
                                            )
                                            return false;
                                        }
                                    }
                                });
                            },
                            btnClass: 'btn-green'
                        },
                        cancel: function() {

                        },
                    }
                });
            });
            //code for delete data


            //code for delete data
            $(document).on('click', '.changeStatus', function() {

                let update_id = $(this).data('id');
                let status = $(this).data('status');

                $.confirm({
                    title: "Hello!",
                    content: "Are you sure?",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {

                                let url =
                                    "{{ route('warehouse.product.productWarehouseStatusChange', ':update_id') }}";
                                url = url.replace(':update_id', update_id);

                                $.ajax({
                                    url: url,
                                    type: "POST",
                                    data: {
                                        update_id: update_id,
                                        status: status
                                    },
                                    cache: false,
                                    dataType: "json",
                                    success: function(data) {
                                        if (data.success) {
                                            table.draw();
                                            alertSuccessMessage(data.success)
                                        } else if (data.errors) {
                                            alertErrorMessage(data.errors)
                                        } else {
                                            alertErrorMessage(
                                                "{{ __('Something Went Wrong') }}."
                                            )
                                            return false;
                                        }
                                    }
                                });
                            },
                            btnClass: 'btn-green'
                        },
                        cancel: function() {

                        },
                    }
                });
            });
            //code for delete data


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
                    name: "products",
                    filename: "products-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });

        });

        // $("#color_code").spectrum({
        //     color: "#f00",
        //     change: function(color) {
        //
        //     }
        // });
    </script>
@endsection
