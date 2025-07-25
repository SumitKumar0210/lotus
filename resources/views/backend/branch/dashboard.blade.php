@extends('backend.branch.layouts.master')
@section('title')
BRANCH DASHBOARD
@endsection
@section('extra-css')
@endsection
@section('content')

<div class="container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Branch Dashboard</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Branch Dashboard</li>
            </ol>
        </div>
        <div class="d-flex">
            <div class="mr-2">
                <a class="btn ripple btn-outline-primary dropdown-toggle mb-0" href="#" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="true">
                    <i class="fe fe-external-link"></i> Export <i class="fas fa-caret-down ml-1"></i>
                </a>
                <div class="dropdown-menu tx-13">
                    <a class="dropdown-item exportToExcel1" href="javascript:void(0);"><i
                            class="far fa-file-pdf mr-2"></i>Export Branch Transfer In</a>
                    <a class="dropdown-item exportToExcel2" href="javascript:void(0);"><i
                            class="far fa-image mr-2"></i>Export Branch Transfer Return</a>
                    <a class="dropdown-item exportToExcel3" href="javascript:void(0);"><i
                            class="far fa-file-excel mr-2"></i>Export Branch Transfer Out</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Header -->


    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-10">
                                        <select class="livesearch form-control" name="product_name" id="product_name"
                                            autocomplete="off"></select>
                                    </div>
                                    <div class="col-2">
                                        <span class="input-group-append">
                                            <button class="btn ripple btn-primary stockSearch"
                                                type="button">Search</button>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="stockSearchResult" style="display: none">
        <!-- Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div>
                            <table class="table" id="product_table_search">
                                <thead>
                                    <tr>
                                        <th class="wd-20p">Sr No.</th>
                                        <th class="wd-20p">Branch Name</th>
                                        <th class="wd-20p">Brand Name</th>
                                        <th class="wd-20p">Category</th>
                                        <th class="wd-20p">Product Name</th>
                                        <th class="wd-20p">Model No</th>
                                        <th class="wd-20p">Colour</th>
                                        <th class="wd-20p">Size</th>
                                        <!--<th class="wd-20p">Opening Qty</th>-->
                                        <th class="wd-20p">Stock Qty</th>
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


    <!-- Row -->
    <div class="row row-sm">
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Total Sales of Month</p>
                        <div class="ml-auto">
                            <i class="fas fa-chart-line fs-20 text-primary"></i>
                        </div>
                    </div>
                    <div>
                        @php
                        $startDate = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                        $firstDay = \Carbon\Carbon::now()
                        ->firstOfMonth()
                        ->format('Y-m-d H:i:s');
                        $estimate = \App\Models\Estimate::whereBetween('created_at', [$firstDay, $startDate])
						->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                        ->where('user_id', Auth::id())
                        ->pluck('id');
                        $estimate_payments = \App\Models\EstimatePaymentList::whereIn('estimate_id', $estimate)->get();
                        $estimate_payments_total = $estimate_payments->sum('total_paid');
                        @endphp
                        <h3 class="dash-25">{{ $estimate_payments_total }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Total Dues</p>
                        <div class="ml-auto">
                            <i class="fab fa-rev fs-20 text-secondary"></i>
                        </div>
                    </div>
                    <div>
                        @php
                        $estimate = \App\Models\Estimate::where('payment_status', 'PAYMENT DUE')
						->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                        ->where('user_id', Auth::id())
                        ->get();
                        $total_dues = $estimate->sum('dues_amount');
                        @endphp
                        <h3 class="dash-25">{{ $total_dues }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Total Discount</p>
                        <div class="ml-auto">
                            <i class="fas fa-dollar-sign fs-20 text-success"></i>
                        </div>
                    </div>
                    <div>
                        @php
                        $discount_value = \App\Models\Estimate::where('branch_id', Auth::user()->branch_id)->where('estimate_status', '!=', 'ESTIMATE CANCELLED')->get();
                        if (!empty($discount_value)) {
                        $all_discount_value = $discount_value->sum('discount_value');
                        } else {
                        $all_discount_value = 0;
                        }
                        @endphp
                        <h3 class="dash-25">&#8377; {{ $all_discount_value }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Total Sale</p>
                        <div class="ml-auto">
                            <i class="fas fa-signal fs-20 text-info"></i>
                        </div>
                    </div>
                    <div>
                        @php
                        $estimate = \App\Models\Estimate::where('user_id', Auth::id())->where('estimate_status', '!=', 'ESTIMATE CANCELLED')->pluck('id');
                        $estimate_payments = \App\Models\EstimatePaymentList::whereIn('estimate_id', $estimate)->get();
                        $estimate_payments_total = $estimate_payments->sum('total_paid');
                        @endphp
                        <h3 class="dash-25">{{ $estimate_payments_total }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Total Collection</p>
                        <div class="ml-auto">
                            <i class="fas fa-signal fs-20 text-danger"></i>
                        </div>
                    </div>
                    <div>
                        @php
                        $estimate1 = \App\Models\Estimate::where('user_id', Auth::id())
						->where('estimate_status', '!=', 'ESTIMATE CANCELLED')
                        ->where('payment_status', 'PAYMENT DONE')
                        ->pluck('id');
                        $estimate_payments1 = \App\Models\EstimatePaymentList::whereIn('estimate_id',
                        $estimate1)->get();
                        $estimate_payments_total1 = $estimate_payments1->sum('total_paid');
                        @endphp
                        <h3 class="dash-25">{{ $estimate_payments_total1 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End  Row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card custom-card main-content-body-profile">
                <nav class="nav main-nav-line">
                    <a class="nav-link active" data-toggle="tab" href="#tab1over">Branch Transfer In</a>
                    {{-- <a class="nav-link" data-toggle="tab" href="#tab2rev">Branch Transfer Return</a> --}}
                    {{-- <a class="nav-link" data-toggle="tab" href="#tab3out">Branch Transfer Out</a> --}}
                </nav>
                <div class="card-body tab-content h-100">
                    <div class="tab-pane active" id="tab1over">
                        <!-- <div class="main-content-label tx-13 mg-b-20">
                                                Branch Transfer In
                                            </div> -->
                        <div class="table-responsive">
                            <table class="table" id="product_table_1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transfer Id</th>
                                        <th>Product Name</th>
										<th>Category</th>
                                        <th>Model No</th>
										<th>Size</th>
                                        <th>From</th>
                                        <th>Created By</th>
                                        <th>Qty</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="tab-pane" id="tab2rev">
                        <div class="table-responsive">
                            <table class="table" id="product_table_2" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transfer Id</th>
                                        <th>Product Name</th>
                                        <th>Model No.</th>
                                        <th>From</th>
                                        <th>Created By</th>
                                        <th>Qty</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    {{-- <div class="tab-pane" id="tab3out"> --}}
                        {{-- <div class="table-responsive"> --}}
                            {{-- <table class="table" id="product_table_3" style="width: 100%"> --}}
                                {{-- <thead> --}}
                                    {{-- <tr> --}}
                                        {{-- <th>#</th> --}}
                                        {{-- <th>Branch To</th> --}}
                                        {{-- <th>Created By</th> --}}
                                        {{-- <th>Product Name</th> --}}
                                        {{-- <th>Product Code</th> --}}
                                        {{-- <th>Colour</th> --}}
                                        {{-- <th>Size</th> --}}
                                        {{-- <th>Qty</th> --}}
                                        {{-- </tr> --}}
                                    {{-- </thead> --}}
                                {{-- <tbody> --}}
                                    {{-- </tbody> --}}
                                {{-- </table> --}}
                            {{-- </div> --}}
                        {{-- </div> --}}


                </div>
            </div>
        </div>
    </div>

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
                            <label for="branch_transfer_item_qty">Transferred Qty</label>
                            <input class="form-control" placeholder="" name="branch_transfer_item_qty"
                                id="branch_transfer_item_qty" type="text" readonly>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="size">Qty To Accept </label>
                            <input class="form-control" placeholder="" id="qty_to_accept" name="qty_to_accept"
                                type="number" min="1" minlength="1">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="size">Return Reason</label>
                            <input class="form-control" placeholder="" id="return_reason" name="return_reason"
                                type="text">
                        </div>
                    </div>


                    <input type="hidden" name="branch_transfer_item_id" id="branch_transfer_item_id" value="">
                    <input type="hidden" name="branch_transfer_id" id="branch_transfer_id" value="">
                    <input type="hidden" name="product_id" id="product_id" value="">

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


{{-- <div class="modal" id="modal_demo2"> --}}
    {{-- <div class="modal-dialog modal-lg" role="document"> --}}
        {{-- <div class="modal-content"> --}}
            {{-- <div class="modal-header"> --}}
                {{-- <h6 class="modal-title">Return Products</h6> --}}
                {{-- <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span --}} {{--
                        aria-hidden="true">&times;</span></button> --}}
                {{-- </div> --}}
            {{-- <div class="modal-body"> --}}
                {{-- <div class="row"> --}}


                    {{-- <div class="col-md-6"> --}}
                        {{-- <div class="form-group"> --}}
                            {{-- <label for="branch_transfer_item_qty2">Transferred Qty</label> --}}
                            {{-- <input class="form-control" placeholder="" name="branch_transfer_item_qty2" --}} {{--
                                id="branch_transfer_item_qty2" --}} {{-- type="text" readonly> --}}
                            {{-- </div> --}}
                        {{-- </div> --}}


                    {{-- <div class="col-md-6"> --}}
                        {{-- <div class="form-group"> --}}
                            {{-- <label for="size">Qty To Return </label> --}}
                            {{-- <input class="form-control" placeholder="" id="qty_to_return" name="qty_to_return" --}}
                                {{-- type="number" min="1" --}} {{-- minlength="1"> --}}
                            {{-- </div> --}}
                        {{-- </div> --}}


                    {{-- <div class="col-md-6"> --}}
                        {{-- <div class="form-group"> --}}
                            {{-- <label for="size">Return Reason</label> --}}
                            {{-- <input class="form-control" placeholder="" id="return_reason" name="return_reason" --}}
                                {{-- type="text"> --}}
                            {{-- </div> --}}
                        {{-- </div> --}}


                    {{-- <input type="hidden" name="branch_transfer_item_id2" id="branch_transfer_item_id2" value="">
                    --}}
                    {{-- <input type="hidden" name="branch_transfer_id2" id="branch_transfer_id2" value=""> --}}
                    {{-- <input type="hidden" name="product_id2" id="product_id2" value=""> --}}

                    {{-- </div> --}}
                {{-- </div> --}}
            {{-- <div class="modal-footer"> --}}
                {{-- <button class="btn ripple btn-primary returnProductButton" data-count="0" type="button">Submit --}}
                    {{-- </button> --}}
                {{-- <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button> --}}
                {{-- </div> --}}
            {{-- </div> --}}
        {{-- </div> --}}
    {{-- </div> --}}




@endsection
@section('extra-js')
<script src="{{ asset('/vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
<script>
    $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            let table = $('#product_table_1').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('branch.branchDashboard.getBranchTransferInList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'branch_transfer_no',
                        name: 'branch_transfer_no'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
					{
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'model_no',
                        name: 'model_no'
                    },
					{
                        data: 'size',
                        name: 'size'
                    },
                    {
                        data: 'from',
                        name: 'from'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'qty_received',
                        name: 'qty_received'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
            });


            @include('backend.admin.messages.message-jquery-confirm-function')


            //code for open modal for create
            $(document).on('click', '.acceptProduct', function(e) {
                e.preventDefault();

                let branch_transfer_item_id = $(this).data('id');
                let branch_transfer_item_qty = $(this).data('qty');
                let product_id = $(this).data('product_id');

                let check_checked = $(this).is(':checked');
                if (check_checked) {
                    $('#modal_demo1').modal('show');
                    $('#branch_transfer_item_id').val(branch_transfer_item_id);
                    $('#branch_transfer_item_qty').val(branch_transfer_item_qty);
                    $('#product_id').val(product_id);
                    $('#return_reason').val('');
                }
            });
            //code for open modal for create


            //code for create data
            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();


                let branch_transfer_item_qty = $('#branch_transfer_item_qty').val();
                let qty_to_accept = $('#qty_to_accept').val();
                let product_id = $('#product_id').val();
                let branch_transfer_item_id = $('#branch_transfer_item_id').val();
                let return_reason = $('#return_reason').val();


                if (qty_to_accept === '') {
                    alertErrorMessage("{{ __('Accept qty cannot be empty') }}.")
                    return false;
                }

                if (parseInt(qty_to_accept) > parseInt(branch_transfer_item_qty)) {
                    alertErrorMessage(
                        "{{ __('Qty to Accept  cannot be greater than product booked qty') }}.")
                    return false;
                }

                if (parseInt(branch_transfer_item_qty) !== parseInt(qty_to_accept)) {
                    if (return_reason === '') {
                        alertErrorMessage("Must enter return reason");
                        return false;
                    }
                }


                let formData = new FormData();
                formData.append('branch_transfer_item_qty', branch_transfer_item_qty);
                formData.append('branch_transfer_item_id', branch_transfer_item_id);
                formData.append('product_id', product_id);
                formData.append('qty_to_accept', qty_to_accept);
                formData.append('return_reason', return_reason);

                $.ajax({
                    type: "POST",
                    url: "{{ route('branch-dashboard.store') }}",
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
            });
            //code for create data


            //code for open modal for return
            $(document).on('click', '.returnProduct', function (e) {
                e.preventDefault();
            
                let branch_transfer_item_id2 = $(this).data('id');
                let branch_transfer_item_qty2 = $(this).data('qty');
                let product_id2 = $(this).data('product_id');
            
                $('#modal_demo2').modal('show');
            
                $('#branch_transfer_item_id2').val(branch_transfer_item_id2);
                $('#branch_transfer_item_qty2').val(branch_transfer_item_qty2);
                $('#product_id2').val(product_id2);
                $('#return_reason').val('');
            
            });
            //code for open modal for return






            //code for create data
            $(document).on('click', '.returnProductButton', function (e) { 
         e.preventDefault(); 

             let branch_transfer_item_qty = $('#branch_transfer_item_qty2').val(); 
             let branch_transfer_item_id = $('#branch_transfer_item_id2').val(); 
             let branch_transfer_id = $('#branch_transfer_id2').val(); 
             let product_id = $('#product_id2').val(); 
             let qty_to_return = $('#qty_to_return').val(); 
             let return_reason = $('#return_reason').val(); 

             if (qty_to_return === '') { 
             alertErrorMessage("{{__('Return qty cannot be empty')}}.") 
             return false; 
             } else if (return_reason === '') { 
             alertErrorMessage("{{__('Return reason cannot be empty')}}.") 
             return false; 
             } else if (parseInt(qty_to_return) > parseInt(branch_transfer_item_qty)) { 
             alertErrorMessage("{{__('Return qty cannot greater than transferred qty')}}.") 
             return false;
            } else { 

             let formData = new FormData(); 
            formData.append('branch_transfer_item_qty', branch_transfer_item_qty); 
             formData.append('branch_transfer_item_id', branch_transfer_item_id); 
             formData.append('branch_transfer_id', branch_transfer_id); 
            formData.append('product_id', product_id); 
             formData.append('qty_to_return', qty_to_return); 
             formData.append('reason', return_reason); 

             $.ajax 
             ({
             type: "POST", 
             url: "{{route('branch.branchDashboard.postBranchTransferReturn')}}", 
             data: formData, 
             cache: false, 
             contentType: false, 
             processData: false, 
             success: function (data) { 
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
             for (let count = 0; count < data.errors_validation.length; count++) { 
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


             let table2 = $('#product_table_2').DataTable({ 
             lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]], 
             processing: true, 
             serverSide: true, 
             ajax: "{{route('branch.branchDashboard.getBranchTransferReturnList')}}",
             columns: [ 
             {data: 'DT_RowIndex', name: 'DT_RowIndex'}, 
             {data: 'branch_transfer_no', name: 'branch_transfer_no'}, 
             {data: 'product_name', name: 'product_name'},
             {data: 'model_no', name: 'model_no'},
             {data: 'from', name: 'from'}, 
         {data: 'created_by', name: 'created_by'},
            {data: 'qty', name: 'qty'}, 
             {data: 'status', name: 'status'}, 
             {data: 'reason', name: 'reason'}, 
             ], 
             }); 














            {{-- let table3 = $('#product_table_3').DataTable({ --}}
            {{-- lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]], --}}
            {{-- processing: true, --}}
            {{-- serverSide: true, --}}
            {{-- ajax: "{{route('branch.branchDashboard.getBranchTransferOutList')}}", --}}
            {{-- columns: [ --}}
            {{-- {data: 'DT_RowIndex', name: 'DT_RowIndex'}, --}}
            {{-- {data: 'branch_to', name: 'branch_to'}, --}}
            {{-- {data: 'created_by', name: 'created_by'}, --}}
            {{-- {data: 'product_name', name: 'product_name'}, --}}
            {{-- {data: 'product_code', name: 'product_code'}, --}}
            {{-- {data: 'color', name: 'color'}, --}}
            {{-- {data: 'size', name: 'size'}, --}}
            {{-- {data: 'qty', name: 'qty'}, --}}
            {{-- ], --}}
            {{-- }); --}}


















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

            $(document).on('click', '.exportToExcel1', function(e) {
                $("#product_table_1").table2excel({
                    exclude: ".noExl",
                    name: "branchTransferIn",
                    filename: "branchTransferIn-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });


            $(document).on('click', '.exportToExcel2', function (e) {
                $("#product_table_2").table2excel({
                    exclude: ".noExl",
                    name: "branchTransferReturn",
                    filename: "branchTransferReturn-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });
            //
            //
            // $(document).on('click', '.exportToExcel3', function (e) {
            //     $("#product_table_3").table2excel({
            //         exclude: ".noExl",
            //         name: "branchTransferOut",
            //         filename: "branchTransferOut-" + today + ".xls",
            //         fileext: ".xls",
            //         exclude_img: true,
            //         exclude_links: true,
            //         exclude_inputs: true,
            //         preserveColors: false
            //     });
            // });


            $('.livesearch').select2({
                placeholder: 'Select product',
                width: '100%',
                ajax: {
                    url: '{{ route('branch.branchDashboard.getBranchStockListSearch') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.product_code + ', ' + item.product_name + ', '+ item.size+ ', '+ item.color_code + ', '+ item.category.category_name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });


            $(document).on('select2:open', '#product_name', function(e) {
                $('#stockSearchResult').hide();
            });


            $(document).on('click', '.stockSearch', function(e) {
                e.preventDefault;
                e.stopPropagation();

                $('#stockSearchResult').show();
                let product_id = $('#product_name').val();
                if (product_id !== '') {

                    let product_table_search = $('#product_table_search').DataTable({
                        paging: false,
                        searching: false,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{ route('branch.branchDashboard.getBranchStockListBySearch') }}',
                            data: function(d) {
                                d.product_id = product_id;
                            },
                            // success: function (response){
                            //     console.log(response);
                            // }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'branch_name',
                                name: 'branch_name'
                            },
                            {
                                data: 'brand_name',
                                name: 'brand_name'
                            },
                            {
                                data: 'category',
                                name: 'category'
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
                            // {data: 'opening_qty', name: 'opening_quantity'},
                            {
                                data: 'closing_qty',
                                name: 'closing_qty'
                            },
                        ],
                    });
                    product_table_search.destroy();
                }
            });


        });
</script>
@endsection