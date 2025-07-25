@extends('backend.admin.layouts.master')
@section('title')
    Branches
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Branches</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Create Branches</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Branches</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-secondary createProduct" href="javascript:void(0)"><i class="fa fa-plus"></i>
                    Create</a>
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
                                        <th class="wd-20p">Branch Name</th>
                                        <th class="wd-20p">Address</th>
                                        <th class="wd-20p">Phone No</th>
                                        <th class="wd-20p">Email Id</th>
                                        <th class="wd-20p">Type</th>
                                        <th class="wd-20p">Print Slug</th>
                                        <th class="wd-20p">Purchase Permission</th>
                                        <th class="wd-20p">Product Permission</th>
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
                    <h6 class="modal-title">Create Branch</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="branch_name">Branch Name</label>
                                <input class="form-control" placeholder="" name="branch_name" id="branch_name" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input class="form-control" placeholder="" name="address" id="address" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone </label>
                                <input class="form-control" placeholder="" name="phone" id="phone" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email </label>
                                <input class="form-control" placeholder="" name="email" id="email" type="text">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="print_slug">Print Slug (3 Char) </label>
                                <input class="form-control" placeholder="" name="print_slug" id="print_slug" type="text">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="permissions">Purchase Permission</label>
                                <input type="checkbox" name="permissions" id="permissions">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="permissions">Product Permission</label>
                                <input type="checkbox" name="product_permissions" id="product_permissions">
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
                    <h6 class="modal-title">Edit Branch</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="update_id" id="update_id" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="branch_name_edit">Branch Name</label>
                                <input class="form-control" placeholder="" name="branch_name_edit" id="branch_name_edit"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_edit">Address</label>
                                <input class="form-control" placeholder="" name="address_edit" id="address_edit"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_edit">Phone </label>
                                <input class="form-control" placeholder="" name="phone_edit" id="phone_edit" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email_edit">Email </label>
                                <input class="form-control" placeholder="" name="email_edit" id="email_edit" type="text">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="print_slug_edit">Print Slug (3 Char) </label>
                                <input class="form-control" name="print_slug_edit" id="print_slug_edit" type="text">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="permissions">Purchase Permission</label>
                                <input type="checkbox" name="permissions_edit" id="permissions_edit">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="permissions">Product Permission</label>
                                <input type="checkbox" name="product_permission_edit" id="product_permission_edit">
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
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('branch.getBranchList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'print_slug',
                        name: 'print_slug'
                    },
                    {
                        data: 'purchase_permission',
                        name: 'purchase_permission'
                    },
                    {
                        data: 'product_permission',
                        name: 'product_permission'
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
            $(document).on('click', '.createProduct', function() {
                $('#modal_demo1').modal('show');
                $('#branch_name').val('');
                $('#address').val('');
                $('#phone').val('');
                $('#email').val('');
                $('#print_slug').val('');
                $('.js-example-basic-multiple').select2();
            });
            //code for open modal for create


            $("#print_slug").keyup(function() {
                $(this).val($(this).val().toUpperCase());
            });

            $("#print_slug_edit").keyup(function() {
                $(this).val($(this).val().toUpperCase());
            });


            //code for create data
            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();


                let branch_name = $('#branch_name').val();
                let address = $('#address').val();
                let phone = $('#phone').val();
                let email = $('#email').val();
                let print_slug = $('#print_slug').val();
                let permissions = null;
                let product_permissions = null;


                if ($("#permissions").prop('checked') === true) {
                    permissions = 'yes';
                } else {
                    permissions = 'no';
                }

                if ($("#product_permissions").prop('checked') === true) {
                    product_permissions = 'yes';
                } else {
                    product_permissions = 'no';
                }

                if (branch_name === '') {
                    alertErrorMessage("{{ __('branch name cannot be empty') }}.")
                    return false;
                } else if (address === '') {
                    alertErrorMessage("{{ __('address cannot be empty') }}.")
                    return false;
                } else if (phone === '') {
                    alertErrorMessage("{{ __('phone cannot be empty') }}.")
                    return false;
                } else if (email === '') {
                    alertErrorMessage("{{ __('email cannot be empty') }}.")
                    return false;
                } else if (print_slug === '') {
                    alertErrorMessage("{{ __('print slug cannot be empty') }}.")
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('branch_name', branch_name);
                    formData.append('address', address);
                    formData.append('phone', phone);
                    formData.append('email', email);
                    formData.append('print_slug', print_slug);
                    formData.append('permissions', permissions);
                    formData.append('product_permissions', product_permissions);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('branch.store') }}",
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
                $('.js-example-basic-multiple').select2();
                let update_id = $(this).data('id');
                $('#update_id').val(update_id);

                //get show route
                let url = "{{ route('branch.show', ':update_id') }}";
                url = url.replace(':update_id', update_id);
                //get show route
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data) {
                        console.log(data);
                        $('#branch_name_edit').val(data.branch_name);
                        $('#address_edit').val(data.address);
                        $('#phone_edit').val(data.phone);
                        $('#email_edit').val(data.email);
                        $('#print_slug_edit').val(data.print_slug);

                        if (data.purchase_permission === 'yes') {
                            $('#permissions_edit').prop('checked', true);
                        } else {
                            $('#permissions_edit').prop('checked', false);
                        }

                        if (data.product_permission === 'yes') {
                            $('#product_permission_edit').prop('checked', true);
                        } else {
                            $('#product_permission_edit').prop('checked', false);
                        }


                    }
                });
            });
            //code for show data


            //code for update data
            $(document).on('click', '.updateProductButton', function(e) {
                e.preventDefault();

                let update_id = $('#update_id').val();
                let branch_name = $('#branch_name_edit').val();
                let address = $('#address_edit').val();
                let phone = $('#phone_edit').val();
                let email = $('#email_edit').val();
                let print_slug = $('#print_slug_edit').val();

                let permissions = null;
                if ($("#permissions_edit").prop('checked') === true) {
                    permissions = 'yes';
                } else {
                    permissions = 'no';
                }


                let product_permission = null;
                if ($("#product_permission_edit").prop('checked') === true) {
                    product_permission = 'yes';
                } else {
                    product_permission = 'no';
                }


                if (branch_name === '') {
                    alertErrorMessage("{{ __('branch name cannot be empty') }}.")
                    return false;
                } else if (address === '') {
                    alertErrorMessage("{{ __('address  cannot be empty') }}.")
                    return false;
                } else if (phone === '') {
                    alertErrorMessage("{{ __('phone cannot be empty') }}.")
                    return false;
                } else if (email === '') {
                    alertErrorMessage("{{ __('email cannot be empty') }}.")
                    return false;
                } else if (print_slug === '') {
                    alertErrorMessage("{{ __('print slug cannot be empty') }}.")
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('branch_name', branch_name);
                    formData.append('address', address);
                    formData.append('phone', phone);
                    formData.append('email', email);
                    formData.append('print_slug', print_slug);
                    formData.append('permissions', permissions);
                    formData.append('product_permission', product_permission);
                    formData.append('_method', 'patch');

                    //get update route
                    let url = "{{ route('branch.update', ':update_id') }}";
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
                    content: "{{ __('Are you sure to remove this branch?') }}",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {

                                let url = "{{ route('branch.destroy', ':delete_id') }}";
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
                    name: "category",
                    filename: "branches-" + today + ".xls",
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
