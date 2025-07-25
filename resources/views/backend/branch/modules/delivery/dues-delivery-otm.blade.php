@extends('backend.branch.layouts.master')
@section('title')
    Order To Make Dues Delivery
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Order To Make Dues Delivery</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Order To Make Dues Delivery</li>
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
                                        <th class="wd-20p">Sr No.</th>
                                        <th class="wd-20p">Estimate No</th>
                                        <th class="wd-20p">Branch Name</th>
                                        <th class="wd-20p">Customer</th>
                                        <th class="wd-20p">Mobile No</th>
                                        <th class="wd-20p">Address</th>
                                        <th class="wd-20p">Expected date(delivery)</th>
                                        <th class="wd-20p">Product Name</th>
                                        <th class="wd-20p">Model</th>
                                        <th class="wd-25p">Colour</th>
                                        <th class="wd-25p">Size</th>
                                        <th class="wd-25p">Qty</th>
                                        <th class="wd-25p">Type</th>
                                        <th class="wd-25p">Remarks</th>
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
    <div class="modal" id="modal_demo1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Accept Products</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_stock_qty">Available Product stock qty </label>
                                <input class="form-control" id="product_stock_qty" name="product_stock_qty" type="number"
                                    minlength="1" value="" readonly>
                            </div>
                        </div>
                        <input class="form-control" name="untouched_qty" id="untouched_qty" type="hidden" value="">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="qty_to_mark_deliver">Qty To Mark Deliver </label>
                                <input class="form-control" id="qty_to_mark_deliver" name="qty_to_mark_deliver"
                                    type="number" min="1" minlength="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="actual_qty">Actual Booked Quantity</label>
                                <input class="form-control" id="actual_qty" name="actual_qty" type="number" value=""
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity_already_delivered">Quantity Already Delivered</label>
                                <input class="form-control" id="quantity_already_delivered"
                                    name="quantity_already_delivered" type="number" value="" readonly>
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
                ajax: "{{ route('branch.duesDeliveryListOTM.getDuesDeliveryListOTM') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
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
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'client_mobile',
                        name: 'client_mobile'
                    },
                    {
                        data: 'client_address',
                        name: 'client_address'
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
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'product_type',
                        name: 'product_type'
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

                let actual_qty = $(this).data('qty');
                let estimate_product_list_id = $(this).data('id');
                let product_id = $(this).data('product_id');
                let estimate_id = $(this).data('estimate_id');
                let untouched_qty = $(this).data('untouched_qty');
                let product_stock_qty = $(this).data('product_stock_qty');
                let quantity_already_delivered = $(this).data('quantity_already_delivered');

                let check_checked = $(this).is(':checked');
                if (check_checked) {
                    $('#modal_demo1').modal('show');
                    $('#actual_qty').val(actual_qty);
                    $('#estimate_product_list_id').val(estimate_product_list_id);
                    $('#estimate_id').val(estimate_id);
                    $('#product_id').val(product_id);
                    $('#untouched_qty').val(untouched_qty);
                    $('#product_stock_qty').val(product_stock_qty);
                    $('#quantity_already_delivered').val(quantity_already_delivered);
                }
            });
            //code for open modal for create


            //code for create data
            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();

                let untouched_qty = $('#untouched_qty').val();
                let qty_to_mark_deliver = $('#qty_to_mark_deliver').val();
                let actual_qty = $('#actual_qty').val();
                let estimate_product_list_id = $('#estimate_product_list_id').val();
                let estimate_id = $('#estimate_id').val();
                let product_id = $('#product_id').val();
                let product_stock_qty = $('#product_stock_qty').val();
                let quantity_already_delivered = $('#quantity_already_delivered').val();

                let new_check = (parseInt(actual_qty) - parseInt(quantity_already_delivered));

                console.log(qty_to_mark_deliver);
                console.log(product_stock_qty);
                console.log(parseInt(product_stock_qty) <= parseInt(qty_to_mark_deliver));

                if (qty_to_mark_deliver === '') {
                    alertErrorMessage("Mark deliver qty cannot be empty");
                    return false;
                } else if (product_stock_qty == 0) {
                    alertErrorMessage("Product is not available in stock");
                    return false;
                } else if (parseInt(product_stock_qty) < parseInt(qty_to_mark_deliver)) {
                    alertErrorMessage("Mark deliver qty cannot be greater than product left qty");
                    return false;
                } else if (parseInt(actual_qty) < parseInt(qty_to_mark_deliver)) {
                    alertErrorMessage("Mark deliver qty cannot be greater than product actual booked qty");
                    return false;
                } else if (parseInt(new_check) < parseInt(qty_to_mark_deliver)) {
                    alertErrorMessage('Mark deliver qty cannot be greater than ' + new_check);
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('untouched_qty', untouched_qty);
                    formData.append('qty_to_mark_deliver', qty_to_mark_deliver);
                    formData.append('actual_qty', actual_qty);
                    formData.append('estimate_product_list_id', estimate_product_list_id);
                    formData.append('estimate_id', estimate_id);
                    formData.append('product_id', product_id);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('dues-delivery-list-otm.store') }}",
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
                    name: "duesDelivery",
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
