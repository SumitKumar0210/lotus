@extends('backend.admin.layouts.master')
@section('title')
    Users
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Users</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Create Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
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
                                        <th class="wd-20p">Name</th>
                                        <th class="wd-20p">Email</th>
                                        <th class="wd-20p">Type</th>
                                        <th class="wd-20p">Branch</th>
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
                    <h6 class="modal-title">Create User</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control" placeholder="" name="name" id="name" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" placeholder="" name="email" id="email" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password </label>
                                <input class="form-control" placeholder="" name="password" id="password" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="conform_password">Confirm Password </label>
                                <input class="form-control" placeholder="" name="conform_password" id="conform_password"
                                    type="text">
                            </div>
                        </div>




                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">User Type</label>
                                <select class="form-control select2-no-search" name="type" id="type">
                                    <option value="" selected="">CHOOSE USER TYPE</option>
                                    <option value="ADMIN">ADMIN</option>
                                    <option value="BRANCH">BRANCH</option>
                                    <option value="WAREHOUSE">WAREHOUSE</option>
                                    {{-- <option value="FACTORY">FACTORY</option> --}}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="branch_id">Branch</label>
                                <select class="form-control select2-no-search" name="branch_id" id="branch_id" disabled>
                                    <option value="" selected="">Choose Branch</option>
                                    @if (!empty($branches))
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
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
                    <h6 class="modal-title">Edit User</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="update_id" id="update_id" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name_edit">Name</label>
                                <input class="form-control" placeholder="" name="name_edit" id="name_edit" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email_edit">Email</label>
                                <input class="form-control" placeholder="" name="email_edit" id="email_edit" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_edit">Password </label>
                                <input class="form-control" placeholder="" name="password_edit" id="password_edit"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="conform_password_edit">Confirm Password </label>
                                <input class="form-control" placeholder="" name="conform_password_edit"
                                    id="conform_password_edit" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary updateProductButton" type="button">Submit</button>
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
                ajax: "{{ route('user.getUserList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false, 
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
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
                        data: 'branch_id',
                        name: 'branch_id'
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
                $('#name').val('');
                $('#email').val('');
                $('#password').val('');
                $('#conform_password').val('');
                $('#type').val('');
                $('#branch_id').val('');
            });
            //code for open modal for create


            //code for create data
            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();


                let name = $('#name').val();
                let email = $('#email').val();
                let password = $('#password').val();
                let conform_password = $('#conform_password').val();
                let type = $('#type').val();
                let branch_id = $('#branch_id').val();



                if (name === '') {
                    alertErrorMessage("{{ __('name cannot be empty') }}.")
                    return false;
                } else if (email === '') {
                    alertErrorMessage("{{ __('email cannot be empty') }}.")
                    return false;
                } else if (password === '') {
                    alertErrorMessage("{{ __('password cannot be empty') }}.")
                    return false;
                } else if (conform_password === '') {
                    alertErrorMessage("{{ __('password confirmation cannot be empty') }}.")
                    return false;
                } else if (type === '') {
                    alertErrorMessage("{{ __('type cannot be empty') }}.")
                    return false;
                } else {

                    if (type === 'BRANCH') {
                        if (branch_id === '') {
                            alertErrorMessage("{{ __('branch cannot be empty') }}.")
                            return false;
                        }
                    }


                    let formData = new FormData();
                    formData.append('name', name);
                    formData.append('email', email);
                    formData.append('password', password);
                    formData.append('password_confirmation', conform_password);
                    formData.append('type', type);
                    formData.append('branch_id', branch_id);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.store') }}",
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
                    filename: "user-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });

            $(document).on('change', '#type', function() {
                let val = $(this).val();
                if (val === 'BRANCH') {
                    $('#branch_id').attr('disabled', false)
                } else {
                    $('#branch_id').attr('disabled', true)
                }

            });


            $(document).on('change', '#type_edit', function() {
                let val = $(this).val();
                if (val === 'BRANCH') {
                    $('#branch_id_edit').attr('disabled', false)
                } else {
                    $('#branch_id_edit').attr('disabled', true)
                }

            });

        });
    </script>
@endsection
