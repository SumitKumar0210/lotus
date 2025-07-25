@extends('backend.admin.layouts.master')
@section('title')
    Estimate List
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Estimate List</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Estimate List</li>
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
                                        <th class="wd-20p">Date</th>
                                        <th class="wd-20p">Branch Name</th>
                                        <th class="wd-20p">Customer</th>
                                        <th class="wd-20p">Mobile No</th>
                                        <th class="wd-20p">Sub total</th>
                                        <th class="wd-20p">Discount</th>
                                        <th class="wd-20p">Total Paid</th>
                                        <th class="wd-20p">Dues</th>
                                        <th class="wd-20p">Status</th>
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
                ajax: "{{ route('estimate.getEstimateList') }}",
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
                        data: 'estimate_date',
                        name: 'estimate_date'
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
                        data: 'total_paid',
                        name: 'total_paid'
                    },
                    {
                        data: 'dues_amount',
                        name: 'dues_amount'
                    },
                    {
                        data: 'delivery_status',
                        name: 'delivery_status'
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


            //code for get  show data
            $(document).on('click', '.editProduct', function() {
                $('#modal_demo2').modal('show');
                let update_id = $(this).data('id');
                $('#update_id').val(update_id);

                //get show route
                let url = "{{ route('user.show', ':update_id') }}";
                url = url.replace(':update_id', update_id);
                //get show route
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data) {
                        console.log(data);
                        $('#name_edit').val(data.name);
                        $('#email_edit').val(data.email);
                    }
                });
            });
            //code for show data


            //code for update data
            $(document).on('click', '.updateProductButton', function(e) {
                e.preventDefault();

                let update_id = $('#update_id').val();
                let name = $('#name_edit').val();
                let email = $('#email_edit').val();
                let password = $('#password_edit').val();
                let conform_password = $('#conform_password_edit').val();


                if (name === '') {
                    alertErrorMessage("{{ __('name cannot be empty') }}.")
                    return false;
                } else if (email === '') {
                    alertErrorMessage("{{ __('email cannot be empty') }}.")
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('name', name);
                    formData.append('email', email);
                    formData.append('password', password);
                    formData.append('password_confirmation', conform_password);
                    formData.append('_method', 'patch');


                    //get update route
                    let url = "{{ route('user.update', ':update_id') }}";
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
                    content: "{{ __('Are you sure to remove this user?') }}",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {
                                let url = "{{ route('user.destroy', ':delete_id') }}";
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
                    name: "user",
                    filename: "estimateLists-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });

            //code for cancel data
            $(document).on('click', '.cancelProduct', function() {
                let cancel_id = $(this).data('id');
                $.confirm({
                    title: "{{ __('Hello!') }}",
                    content: "{{ __('Are you sure to cancel this Estimate?') }}",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {

                                let url =
                                    "{{ route('admin.estimateList.cancelEstimate', ':cancel_id') }}";
                                url = url.replace(':cancel_id', cancel_id);

                                $.ajax({
                                    url: url,
                                    type: "POST",
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
            //code for cancel data






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
                        url: "{{ route('admin.estimateList.applyForDueApproval') }}",
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
