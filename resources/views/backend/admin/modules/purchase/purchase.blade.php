@extends('backend.admin.layouts.master')
@section('title')
    Purchase
@endsection
@section('extra-css')


@endsection
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Purchase</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Purchase</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Purchase</li>
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

                            <form id="checkbox_form">
                                <table class="table" id="product_table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary btn-sm checkbox_btn">Select</a>
                                                <input type="hidden" name="checkbox_value" id="checkbox_value"
                                                    value="unchecked">
                                            </th>
                                            {{-- <th>Sr No.</th> --}}
                                            <th>Created by</th>
                                            <th>Brand</th>
                                            <th>Product Name</th>
                                            <th>Model no</th>
                                            <th>Color</th>
                                            <th>Category</th>
                                            <th>Size</th>
                                            <th>Qty</th>
                                            <th>Date</th>

                                            <th>Bill Number</th>
                                            <th>Vendor Name</th>
                                            <th>Remarks</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                                <button type="submit" name="btn_submit" id="btn_submit" value="btn_submit"
                                    class="btn btn-primary">
                                    Submit
                                </button>
                            </form>

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
                                <label for="product_name">Product Name</label>
                                <input class="form-control" placeholder="" name="product_name" id="product_name"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code">Product Code</label>
                                <input class="form-control" placeholder="" name="product_code" id="product_code"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand_id">Brand</label>
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
                                <label for="category_id">Category</label>
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
                                <label for="color_code">Color Code </label>
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
                                <label for="maximum_retail_price">Rate </label>
                                <input class="form-control" placeholder="" id="maximum_retail_price"
                                    name="maximum_retail_price" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="minimum_stock_quantity">Minimum Stock Qty </label>
                                <input class="form-control" placeholder="" id="minimum_stock_quantity"
                                    name="minimum_stock_quantity" type="number">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description </label>
                                <textarea class="form-control" placeholder="Textarea" id="description" name="description"
                                    rows="3"></textarea>
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
                                <label for="product_name_edit">Product Name</label>
                                <input class="form-control" placeholder="" name="product_name_edit" id="product_name_edit"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code_edit">Product Code</label>
                                <input class="form-control" placeholder="" name="product_code_edit" id="product_code_edit"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand_id_edit">Brand</label>
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
                                <label for="category_id_edit">Category</label>
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
                                <label for="maximum_retail_price_edit">Rate </label>
                                <input class="form-control" placeholder="" id="maximum_retail_price_edit"
                                    name="maximum_retail_price_edit" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="minimum_stock_quantity_edit">Minimum Stock Qty </label>
                                <input class="form-control" placeholder="" id="minimum_stock_quantity_edit"
                                    name="minimum_stock_quantity_edit" type="number">
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
                processing: true,
                serverSide: true,
                ajax: "{{ route('purchase.getPurchaseList') }}",
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'brand',
                        name: 'brand'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'model_no',
                        name: 'model_no'
                    },
                    {
                        data: 'color',
                        name: 'color'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'size',
                        name: 'size'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },

                    {
                        data: 'bill_number',
                        name: 'bill_number'
                    },
                    {
                        data: 'vendor_name',
                        name: 'vendor_name'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });








            @include('backend.admin.messages.message-jquery-confirm-function')


            let checked_value = [];
            $(document).on('click', '.checkbox_btn', function() {
                let checkbox_value = $('#checkbox_value').val();
                if (checkbox_value === 'unchecked') {
                    $(this).text('Selected');
                    $('.someCheckbox').prop('checked', true);
                    $('#checkbox_value').val('checked');

                    $('.someCheckbox').each(function(i) {
                        checked_value[i] = $(this).data('id');
                    });
                } else {
                    $(this).text('Select');
                    $('.someCheckbox').prop('checked', false);
                    $('#checkbox_value').val('unchecked');
                    checked_value = []
                }
                console.log(checked_value);
            });
            $(document).on('click', '.someCheckbox', function() {
                let id = $(this).data('id');
                if ($(this).is(":checked")) {
                    checked_value.push(id);
                } else {
                    checked_value = checked_value.filter(function(item) {
                        return item !== id
                    });
                }
                console.log(checked_value);
            });


            //code for delete data
            $(document).on('click', '.approvePurchase', function() {
                let stock_id = $(this).data('id');
                $.confirm({
                    title: "{{ __('Hello!') }}",
                    content: "{{ __('Are you sure to approve this product?') }}",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {

                                let formData = new FormData();
                                formData.append('stock_id', stock_id);

                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('purchase.approvePurchase') }}",
                                    data: formData,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function(data) {
                                        console.log(data);
                                        if (data.success) {
                                            alertSuccessMessage(data.success)
                                            table.draw();
                                        } else if (data.errors_success) {
                                            alertErrorMessage(data.errors_success)
                                            return false;
                                        } else if (data.errors_validation) {
                                            let html = '';
                                            for (let count = 0; count < data
                                                .errors_validation.length; count++
                                            ) {
                                                html += '<p>' + data
                                                    .errors_validation[count] +
                                                    '</p>';
                                            }
                                            alertErrorMessage(html)
                                            return false;
                                        } else {
                                            alertErrorMessage(
                                                "Something Went Wrong")
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



            //code for bulk submit
            $(document).on('click', '#btn_submit', function(e) {
                e.preventDefault();

                $.confirm({
                    title: "Hello!",
                    content: "Are you sure to approve bulk products?",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {
                                let formData = new FormData();
                                formData.append('stock_ids', checked_value);
                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('purchase.approvePurchaseBulk') }}",
                                    data: formData,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function(data) {
                                        console.log(data);
                                        if (data.success) {
                                            alertSuccessMessage(data.success)
                                            table.draw();
                                            checked_value = [];
                                        } else if (data.errors_success) {
                                            alertErrorMessage(data.errors_success)
                                            return false;
                                        } else if (data.errors_validation) {
                                            let html = '';
                                            for (let count = 0; count < data
                                                .errors_validation.length; count++
                                            ) {
                                                html += '<p>' + data
                                                    .errors_validation[count] +
                                                    '</p>';
                                            }
                                            alertErrorMessage(html)
                                            return false;
                                        } else {
                                            alertErrorMessage(
                                                "Something Went Wrong")
                                            return false;
                                        }
                                    }
                                });
                            },
                            btnClass: 'btn-green'
                        },
                        cancel: function() {},
                    }
                });
            });
            //code for bulk submit
















            //code for delete data
            $(document).on('click', '.deleteProduct', function() {
                let delete_id = $(this).data('id');
                $.confirm({
                    title: "{{ __('Hello!') }}",
                    content: "{{ __('Are you sure to remove this purchase?') }}",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {

                                let url = "{{ route('purchase.destroy', ':delete_id') }}";
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
                    name: "purchase",
                    filename: "purchase-" + today + ".xls",
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
