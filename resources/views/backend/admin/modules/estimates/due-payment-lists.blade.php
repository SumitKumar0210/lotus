@extends('backend.admin.layouts.master')
@section('title')
Due Payment List
@endsection
@section('extra-css')
@endsection
@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Due Payment List</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                <li class="breadcrumb-item active" aria-current="page">Due Payment List</li>
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
                                    <th>Grand total</th>
                                    <th>Dues Paid</th>
                                    <th>Mode</th>
                                    <th>Date</th>
                                    <th>Dues</th>
                                    {{-- <th>Total</th> --}}
                                    <th>Delivery Status</th>
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

    <!-- CREATE MODAL START-->
    <div class="modal" id="modal_demo2">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Settlement Amount</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="estimate_id" id="estimate_id">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="size">Due Amount</label>
                                <input class="form-control" id="due_amount" name="due_amount" type="number" value="0"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="size">Settle Amount</label>
                                <input class="form-control" id="settle_amount" name="settle_amount" type="number"
                                    value="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn ripple btn-primary" type="button" onclick="updateSettlement()">Submit</button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- CREATE MODAL END-->
</div>
@endsection
@section('extra-js')
<script>
    @include('backend.admin.messages.message-jquery-confirm-function')

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
                ajax: "{{ route('estimate.getDuePaymentList') }}",
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
                        data: 'grand_total',
                        name: 'grand_total'
                    },
                    {
                        data: 'dues_paid',
                        name: 'dues_paid'
                    },
                    {
                        data: 'mode',
                        name: 'mode'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'dues_amount',
                        name: 'dues_amount'
                    },
                    // {data: 'total', name: 'total'},
                    {
                        data: 'delivery_status',
                        name: 'delivery_status'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
            });



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
                    filename: "duePaymentLists-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });

        });

        function setPaymentDetail(id,amount)
        {
            $('#estimate_id').val(id);
            $('#due_amount').val(amount);
        }

        function updateSettlement()
        {
            let id = $('#estimate_id').val();
            let due_amount = $('#due_amount').val();
            let settle_amount = $('#settle_amount').val();
            
            console.log(due_amount);
            console.log(settle_amount);
            if(parseInt(settle_amount) > parseInt(due_amount))
            {
                alertErrorMessage("Enter valid Amount, Settle Amount cannot be greater than Due Amount")
                return false;
            } else{
                $.ajax({
                        type: "POST",
                        url: "{{ route('estimate.updateSettleAmount') }}",
                        data: {id:id,due_amount:due_amount,settle_amount:settle_amount},
                        cache: false,
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            if (data.success) {
                                alertSuccessMessage(data.success)
                                window.setTimeout(function() {
                                    location.reload();
                                }, 3000);
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
                                alertErrorMessage("Something Went Wrong")
                                return false;
                            }
                        }
                    });
            }
        }
</script>
@endsection