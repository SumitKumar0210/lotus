@extends('backend.branch.layouts.master')
@section('title')
    Due Estimate Lists
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Due Estimate Lists</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Due Estimate Lists</li>
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
                                        <th class="wd-20p">Sub</th>
                                        <th class="wd-20p">Discount</th>
                                        <th class="wd-20p">Total</th>
                                        <th class="wd-20p">Deliver Status</th>
                                        <th class="wd-25p">Dues</th>
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
                    <h6 class="modal-title">Record Payment</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">


                        <input type="hidden" name="estimate_id" id="estimate_id">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paid_in_cash">Dues Amount </label>
                                <input class="form-control" name="dues_amount" id="dues_amount" type="number" value=""
                                    readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paid_in_cash">Paid In Cash </label>
                                <input class="form-control" name="paid_in_cash" id="paid_in_cash" type="number" min="0"
                                    minlength="1" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paid_in_bank">Paid In Bank </label>
                                <input class="form-control" name="paid_in_bank" id="paid_in_bank" type="number" min="0"
                                    minlength="1" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Amount </label>
                                <input class="form-control" placeholder="" name="amount" id="amount" type="number"
                                    minlength="1" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn ripple btn-primary createProductButton" type="submit">Submit</button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- CREATE MODAL END-->






    <!-- CREATE MODAL START-->
    <div class="modal" id="modal_demo2">
        <div class="modal-dialog modal-sm" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Due Approval</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="estimate_id_2" id="estimate_id_2">
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn ripple btn-primary createDueApplyButton" type="submit">Submit</button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Cancel</button>
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
                ajax: "{{ route('branch.duesEstimateList.getDuesEstimateList') }}",
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
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'client_mobile',
                        name: 'client_mobile'
                    },
                    {
                        data: 'sub_total',
                        name: 'sub_total'
                    },
                    {
                        data: 'discount_value',
                        name: 'discount_value'
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total'
                    },
                    {
                        data: 'delivery_status',
                        name: 'delivery_status'
                    },
                    {
                        data: 'dues_amount',
                        name: 'dues_amount'
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

            //calculate the paid_in_cash
            $(document).on('change keyup keydown', '#paid_in_cash', function() {
                let paid_in_cash = $(this).val();
                let paid_in_bank = $('#paid_in_bank').val();
                if (paid_in_cash === '') {
                    paid_in_cash = 0;
                }
                if (paid_in_bank === '') {
                    paid_in_bank = 0;
                }
                let amount = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#amount').val(amount);
            });
            //calculate the paid_in_cash


            //calculate the paid_in_cash
            $(document).on('change keyup keydown', '#paid_in_bank', function() {
                let paid_in_bank = $(this).val();
                let paid_in_cash = $('#paid_in_cash').val();
                if (paid_in_cash === '') {
                    paid_in_cash = 0;
                }
                if (paid_in_bank === '') {
                    paid_in_bank = 0;
                }
                let amount = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#amount').val(amount);
            });
            //calculate the paid_in_cash


            //code for open modal for create
            $(document).on('click', '.createProduct', function() {

                let dues_amount = $(this).data('dues_amount');

                $('#modal_demo1').modal('show');
                $('#amount').val('');
                $('#paid_in_cash').val('');
                $('#paid_in_bank').val('');
                $('#dues_amount').val(dues_amount);

                let estimate_id = $(this).data('id');
                $('#estimate_id').val(estimate_id);

            });
            //code for open modal for create


            //code for create data
            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();

                let estimate_id = $('#estimate_id').val();
                let amount = $('#amount').val();
                let paid_in_cash = $('#paid_in_cash').val();
                let paid_in_bank = $('#paid_in_bank').val();
                let dues_amount = $('#dues_amount').val();

                if (paid_in_cash === '') {
                    $('#paid_in_cash').val(0);
                    paid_in_cash = 0;

                }
                if (paid_in_bank === '') {
                    $('#paid_in_bank').val(0);
                    paid_in_bank = 0;
                }


                if (estimate_id === '') {
                    alertErrorMessage("{{ __('estimate id cannot be empty') }}.")
                    return false;
                } else if (amount === '') {
                    alertErrorMessage("{{ __('amount  cannot be empty') }}.")
                    return false;
                } else if (parseInt(amount) > parseInt(dues_amount)) {
                    alertErrorMessage("{{ __('amount  cannot be greater than dues amount') }}.")
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('estimate_id', estimate_id);
                    formData.append('amount', amount);
                    formData.append('paid_in_cash', paid_in_cash);
                    formData.append('paid_in_bank', paid_in_bank);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('dues-estimate-list.store') }}",
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
                    name: "duesEstimate",
                    filename: "duesEstimate-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });


            //code for open modal for create
            $(document).on('click', '.dueApproval', function() {
                $('#modal_demo2').modal('show');
                let estimate_id = $(this).data('id');
                $('#estimate_id_2').val(estimate_id);

            });
            //code for open modal for create


            //code for create data
            $(document).on('click', '.createDueApplyButton', function(e) {
                e.preventDefault();

                let estimate_id = $('#estimate_id_2').val();


                if (estimate_id === '') {
                    alertErrorMessage("{{ __('estimate id cannot be empty') }}.")
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('estimate_id', estimate_id);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('branch.duesEstimateList.applyForDueApproval') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            console.log(data);
                            if (data.success) {
                                alertSuccessMessage(data.success)
                                table.draw();
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
                                alertErrorMessage("Something Went Wrong 2")
                                return false;
                            }
                        }
                    });
                }
            });
            //code for create data


        });
    </script>
@endsection
