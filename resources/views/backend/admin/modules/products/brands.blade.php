@extends('backend.admin.layouts.master')
@section('title')
    Brands
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Brand</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Create Brand</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Brand</li>
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
                                        <th class="wd-20p">Sl No.</th>
                                        <th class="wd-20p">Brand / Vendor</th>
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
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Create Brand</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg">
                            <div class="form-group">
                                <label for="brand_name">Brand Name </label>
                                <input class="form-control" placeholder="" id="brand_name" name="brand_name" type="text">
                            </div>
                            <button class="btn ripple btn-primary createProductButton float-right"
                                type="button">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CREATE MODAL END-->
    <!-- EDIT MODAL START-->
    <div class="modal" id="modal_demo2">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Edit Brand</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="update_id" id="update_id" value="">
                    <div class="row">
                        <div class="col-lg">
                            <div class="form-group">
                                <label for="brand_name">Brand Name </label>
                                <input class="form-control" placeholder="" id="brand_name_edit" name="brand_name_edit"
                                    type="text">
                            </div>
                            <button class="btn ripple btn-primary updateProductButton" type="button">Update</button>
                        </div>
                    </div>
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

            //$.fn.dataTable.ext.errMode = 'throw';

            let table = $('#product_table').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                order : [[ 1, "asc" ]],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('brand.getBrandsList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'brand_name',
                        name: 'brand_name'
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
                $('#brand_name').val('');
            });
            //code for open modal for create


            //code for create data
            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();

                let brand_name = $('#brand_name').val();

                if (brand_name === '') {
                    alertErrorMessage("{{ __('Brand name cannot be empty') }}.")
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('brand_name', brand_name);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('brand.store') }}",
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
                let url = "{{ route('brand.show', ':update_id') }}";
                url = url.replace(':update_id', update_id);
                //get show route
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data) {
                        $('#brand_name_edit').val(data.brand_name);
                    }
                });
            });
            //code for show data




            //code for update data
            $(document).on('click', '.updateProductButton', function(e) {
                e.preventDefault();

                let brand_name_edit = $('#brand_name_edit').val();
                let update_id = $('#update_id').val();

                if (brand_name_edit === '') {
                    alertErrorMessage("{{ __('Brand name cannot be empty') }}.")
                    return false;
                } else {

                    let formData = new FormData();
                    formData.append('brand_name', brand_name_edit);
                    formData.append('_method', 'patch');

                    //get update route
                    let url = "{{ route('brand.update', ':update_id') }}";
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
                    content: "{{ __('Are you sure to remove this brand?') }}",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {

                                let url = "{{ route('brand.destroy', ':delete_id') }}";
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
                    name: "Brands",
                    filename: "Brands-" + today + ".xls",
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
