@extends('backend.branch.layouts.master')
@section('title')
    Delivered List
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Delivered List</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Delivered List</li>
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
                            @include('backend.branch.messages.message-jquery-confirm')

                            <form target="_blank" id="checkbox_form"
                                action="{{ route('branch.deliveredList.printChallanBulk') }}" enctype="multipart/form-data"
                                method="POST">
                                @csrf

                                <input type="hidden" name="estimate_product_delivery_status_ids"
                                    id="estimate_product_delivery_status_ids" value="">
                                <input type="hidden" name="estimate_numbers" id="estimate_numbers" value="">


                                <table class="table" id="product_table">
                                    <thead>
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary btn-sm checkbox_btn">Select</a>
                                                <input type="hidden" name="checkbox_value" id="checkbox_value"
                                                    value="unchecked">
                                            </th>
                                            <th>Action</th>
                                            <th>Estimate No</th>
                                            <th>Branch Name</th>
                                            <th>Customer Details</th>
                                            <th>Delivery date</th>
                                            <th>Product Name</th>
                                            <th>Model</th>
                                            <th>Qty</th>
                                            <th>Remarks</th>
                                            <th>Type</th>
                                            <th>Created By</th>
                                            <th>Delivered By</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <button type="submit" name="btn_submit" id="btn_submit" value="btn_submit"
                                    class="btn btn-primary">
                                    Print Challan
                                </button>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>






    <div class="modal" id="modal_demo1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Sale Return Product</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Qty To Sale Return </label>
                                <input class="form-control" id="qty_to_sale_return" name="qty_to_sale_return" type="number"
                                    min="1" minlength="1">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Saled Quantity</label>
                                <input class="form-control" id="sale_qty" name="sale_qty" type="number" value="" readonly>
                            </div>
                        </div>

                        <input type="hidden" name="estimate_product_list_id" id="estimate_product_list_id" value="">
                        <input type="hidden" name="product_id" id="product_id" value="">
                        <input type="hidden" name="estimate_id" id="estimate_id" value="">

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

            let table = $('#product_table').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('branch.deliveredList.getDeliveredList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
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
                        data: 'customer_details',
                        name: 'customer_details'
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
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'product_type',
                        name: 'product_type'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'delivered_by',
                        name: 'delivered_by'
                    },

                ],
            });






            @include('backend.admin.messages.message-jquery-confirm-function')


            //code for open modal for create
            $(document).on('click', '.conformDelivery', function(e) {
                e.preventDefault();

                let sale_qty = $(this).data('qty');
                let estimate_product_list_id = $(this).data('id');
                let product_id = $(this).data('product_id');
                let estimate_id = $(this).data('estimate_id');

                let check_checked = $(this).is(':checked');
                if (check_checked) {
                    $('#modal_demo1').modal('show');
                    $('#sale_qty').val(sale_qty);
                    $('#estimate_product_list_id').val(estimate_product_list_id);
                    $('#estimate_id').val(estimate_id);
                    $('#product_id').val(product_id);
                }
            });
            //code for open modal for create


            //code for create data
            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();


                let qty_to_sale_return = $('#qty_to_sale_return').val();
                let sale_qty = $('#sale_qty').val();
                let estimate_product_list_id = $('#estimate_product_list_id').val();
                let estimate_id = $('#estimate_id').val();
                let product_id = $('#product_id').val();


                if (qty_to_sale_return === '') {
                    alertErrorMessage("Sale return qty cannot be empty");
                    return false;
                } else if (sale_qty == 0) {
                    alertErrorMessage("sale qty cannot be zero");
                    return false;
                } else if (parseInt(sale_qty) < parseInt(qty_to_sale_return)) {
                    alertErrorMessage("Saled Quantity always greater than or equal to qty_to_sale_return");
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('qty_to_sale_return', qty_to_sale_return);
                    formData.append('sale_qty', sale_qty);
                    formData.append('estimate_product_list_id', estimate_product_list_id);
                    formData.append('estimate_id', estimate_id);
                    formData.append('product_id', product_id);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('delivered-list.store') }}",
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
                    name: "Delivered",
                    filename: "Delivered-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });


            //code for bulk challan print start***********************
            let checked_value = [];
            let estimate_numbers = [];
            $(document).on('click', '.checkbox_btn', function() {

                $('#estimate_product_delivery_status_ids').val('');
                $('#estimate_numbers').val('');

                let checkbox_value = $('#checkbox_value').val();

                if (checkbox_value === 'unchecked') {
                    $(this).text('Selected');
                    $('.someCheckbox').prop('checked', true);
                    $('#checkbox_value').val('checked');

                    $('.someCheckbox').each(function(i) {
                        checked_value[i] = $(this).data('id');
                        estimate_numbers[i] = $(this).data('estimate_numbers');
                    });
                } else {
                    $(this).text('Select');
                    $('.someCheckbox').prop('checked', false);
                    $('#checkbox_value').val('unchecked');
                    checked_value = [];
                    estimate_numbers = [];
                }
                console.log(checked_value);
                console.log(estimate_numbers);
            });


            $(document).on('click', '.someCheckbox', function() {

                $('#estimate_product_delivery_status_ids').val('');
                $('#estimate_numbers').val('');

                let id = $(this).data('id');
                let estimate_number = $(this).data('estimate_numbers');
                if ($(this).is(":checked")) {
                    checked_value.push(id);
                    estimate_numbers.push(estimate_number);
                } else {
                    checked_value = checked_value.filter(function(item) {
                        return item !== id
                    });


                    estimate_numbers = estimate_numbers.filter(function(item) {
                        return item !== estimate_number
                    });
                }
                console.log(checked_value);
                console.log(estimate_numbers);
            });

            //code for bulk submit
            $(document).on('click', '#btn_submit', function(e) {
                e.preventDefault();

                $.confirm({
                    title: "Hello!",
                    content: "Are you sure want to print challan?",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {

                            action: function() {

                                $('input:hidden[name=estimate_product_delivery_status_ids]')
                                    .val(checked_value);
                                $('input:hidden[name=estimate_numbers]').val(estimate_numbers);
                                $('#checkbox_form').submit();

                            },
                            btnClass: 'btn-green'
                        },
                        cancel: function() {},
                    }
                });
            });
            //code for bulk submit
            //code for bulk challan print end***********************


        });
    </script>
@endsection
