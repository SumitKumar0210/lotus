@extends('backend.admin.layouts.master')
@section('title')
    Pending Delivery
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Pending Delivery</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Delivery</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pending Delivery</li>
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
                            <table class="table" id="product_table">
                                <thead>

                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Estimate No</th>
                                        <th>Branch Name</th>
                                        <th>Customer</th>
                                        <th>Mobile No</th>
                                        <th>Address</th>
                                        <th>Expected date(delivery)</th>
                                        <th>Product Name</th>
                                        <th>Model</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Qty Booked</th>
                                        <th>Qty(Undelivered)</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
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
                    <h6 class="modal-title">Mark Delivered</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Actual Booked Quantity</label>
                                <input class="form-control" id="product_booked_qty" name="product_booked_qty" type="number"
                                    value="" readonly>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Stock Qty</label>
                                <input class="form-control" placeholder="" name="product_stock_qty" id="product_stock_qty"
                                    type="text" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code">Undelivered Qty</label>
                                <input class="form-control" placeholder="" name="new_undelivered_quantity"
                                    id="new_undelivered_quantity" type="number" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code">Product Marked Delivered Qty</label>
                                <input class="form-control" placeholder="" name="product_mark_delivered_qty"
                                    id="product_mark_delivered_qty" type="number" minlength="1" min="1">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Quantity Already Delivered</label>
                                <input class="form-control" id="quantity_already_delivered"
                                    name="quantity_already_delivered" type="number" value="" readonly>
                            </div>
                        </div>


                        <input type="hidden" name="estimate_product_list_id" id="estimate_product_list_id" value="">
                        <input type="hidden" name="branch_id" id="branch_id" value="">
                        <input type="hidden" name="product_id" id="product_id" value="">


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
                ajax: "{{ route('delivery.getDueDeliveryList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
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
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'expected_delivery_date',
                        name: 'expected_delivery_date'
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
                    {
                        data: 'qty_undelivered',
                        name: 'qty_undelivered'
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


            //code for open modal for create
            $(document).on('click', '.conformDelivery', function(e) {
                e.preventDefault();

                let estimate_product_list_id = $(this).data('id');
                let product_booked_qty = $(this).data('qty');
                let new_undelivered_quantity = $(this).data('new_undelivered_quantity');
                let check_checked = $(this).is(':checked');
                let product_stock_qty = $(this).data('product_stock_qty');
                let quantity_already_delivered = $(this).data('quantity_already_delivered');
                let branch_id = $(this).data('branch_id');
                let product_id = $(this).data('product_id');

                if (check_checked) {
                    $('#modal_demo1').modal('show');
                    $('#new_undelivered_quantity').val(new_undelivered_quantity);
                    $('#product_mark_delivered_qty').val(1);
                    $('#estimate_product_list_id').val(estimate_product_list_id);
                    $('#product_stock_qty').val(product_stock_qty);
                    $('#quantity_already_delivered').val(quantity_already_delivered);
                    $('#product_booked_qty').val(product_booked_qty);
                    $('#branch_id').val(branch_id);
                    $('#product_id').val(product_id);
                }


            });
            //code for open modal for create


            //code for create data
            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();
                console.log("hi");

                let new_undelivered_quantity = $('#new_undelivered_quantity').val();
                let product_mark_delivered_qty = $('#product_mark_delivered_qty').val();
                let estimate_product_list_id = $('#estimate_product_list_id').val();
                let product_stock_qty = $('#product_stock_qty').val();
                let quantity_already_delivered = $('#quantity_already_delivered').val();
                let product_booked_qty = $('#product_booked_qty').val();
                let branch_id = $('#branch_id').val();
                let product_id = $('#product_id').val();

                let new_check = (parseInt(product_booked_qty) - parseInt(quantity_already_delivered));

                if (product_mark_delivered_qty === '') {
                    alertErrorMessage("{{ __('Product mark deliver qty cannot be empty') }}.")
                    return false;
                } else if (parseInt(product_mark_delivered_qty) > parseInt(product_booked_qty)) {
                    alertErrorMessage("Product mark deliver qty cannot be greater than product booked qty.")
                    return false;
                } else if (Number(product_stock_qty) === 0) {
                    alertErrorMessage("Product is not available in stock");
                    return false;
                } else if (parseInt(new_check) < parseInt(product_mark_delivered_qty)) {
                    alertErrorMessage('Mark deliver qty cannot be greater than ' + new_check);
                    return false;
                } else {


                    let estimate_product_list_table_status = '';
                    if (new_undelivered_quantity === product_mark_delivered_qty) {
                        estimate_product_list_table_status = 'DELIVERED';
                    } else {
                        estimate_product_list_table_status = 'NOT DELIVERED';
                    }


                    let formData = new FormData();
                    formData.append('new_undelivered_quantity', new_undelivered_quantity);
                    formData.append('product_mark_delivered_qty', product_mark_delivered_qty);
                    formData.append('estimate_product_list_id', estimate_product_list_id);
                    formData.append('estimate_product_list_table_status',
                        estimate_product_list_table_status);
                    formData.append('branch_id', branch_id);
                    formData.append('product_id', product_id);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('due-delivery.store') }}",
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
                    name: "category",
                    filename: "duesDelivery-" + today + ".xls",
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
