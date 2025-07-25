@extends('backend.warehouse.layouts.master')
@section('title')
    WAREHOUSE DASHBOARD
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">WareHouse Dashboard</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
            <div class="d-flex">
                <div class="mr-2">
                    <a class="btn ripple btn-outline-primary dropdown-toggle mb-0" href="#" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="true">
                        <i class="fe fe-external-link"></i> Export <i class="fas fa-caret-down ml-1"></i>
                    </a>
                    <div class="dropdown-menu tx-13">
                        <a class="dropdown-item receivedJob exportToExcel1" href="javascript:void(0);"><i
                                class="far fa-file-excel mr-2"></i>Branch Transfer Return</a>
                        <a class="dropdown-item scheduledJob exportToExcel2" href="javascript:void(0);"><i
                                class="far fa-file-excel mr-2"></i>Branch Transfer Out</a>
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


        {{-- <div id="stockSearchResult" style="display: none">
      <div class="row">
         <div class="col-lg-12">
            <div class="card custom-card overflow-hidden">
               <div class="card-body">
                  <div>
                     <table class="table" id="product_table_search">
                        <thead>
                           <tr>
                              <th class="wd-20p">Sr No.</th>
                              <th class="wd-20p">Product Name</th>
                              <th class="wd-20p">Model No</th>
                              <th class="wd-20p">Category</th>
                              <th class="wd-20p">Colour</th>
                              <th class="wd-20p">Size</th>
                              <th class="wd-20p">Opening Qty</th>
                              <th class="wd-20p">Closing Qty</th>
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
   </div> --}}



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
                                            <th class="wd-20p">Product Name</th>
                                            <th class="wd-20p">Model No</th>
                                            <th class="wd-20p">Category</th>
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
                            <p class="mb-1 tx-inverse">Total Purchased Items</p>
                            <div class="ml-auto">
                                <i class="fas fa-chart-line fs-20 text-primary"></i>
                            </div>
                        </div>
                        <div>
                            @php
                                $stock_purchased = \App\Models\Stock::where('type', 'WAREHOUSE STOCK')
                                    ->where('status', 'IN STOCK')
                                    ->where('reason', 'PURCHASE')
                                    ->get();
                                $stock_purchased_count = $stock_purchased->sum('qty');
                            @endphp
                            <h3 class="dash-25">{{ $stock_purchased_count }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl col-lg">
                <div class="card custom-card">
                    <div class="card-body dash1">
                        <div class="d-flex">
                            <p class="mb-1 tx-inverse">In Transit Items</p>
                            <div class="ml-auto">
                                <i class="fas fa-rupee-sign fs-20 text-success"></i>
                            </div>
                        </div>
                        <div>
                            @php
                                $stock_in_transit = \App\Models\Stock::where('type', 'IN TRANSIT')
                                    ->where('status', 'OUT STOCK')
                                    ->where('reason', 'BRANCH TRANSFER')
                                    ->where('branch_out', Auth::user()->branch_id)
                                    ->get();
                                $stock_in_transit_count = $stock_in_transit->sum('qty');
                            @endphp
                            <h3 class="dash-25">{{ $stock_in_transit_count }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl col-lg">
                <div class="card custom-card">
                    <div class="card-body dash1">
                        <div class="d-flex">
                            <p class="mb-1 tx-inverse">Total Returned Items</p>
                            <div class="ml-auto">
                                <i class="fab fa-rev fs-20 text-secondary"></i>
                            </div>
                        </div>
                        <div>
                            @php
                                $stock_transfered_returned = \App\Models\Stock::where('type', 'BRANCH STOCK')
                                    ->where('status', 'IN STOCK')
                                    ->where('reason', 'BRANCH TRANSFER')
                                    ->where('is_returned', 'RETURNED')
                                    ->where('branch_out', Auth::user()->branch_id)
                                    ->get();
                                $stock_transfered_returned_count_sum = $stock_transfered_returned->sum('qty');
                                $stock_transfered_returned_count_accepted_qty = $stock_transfered_returned->sum('accepted_qty');
                                $new_qty = $stock_transfered_returned_count_sum - $stock_transfered_returned_count_accepted_qty;
                            @endphp
                            <h3 class="dash-25">{{ $new_qty }}</h3>
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
                        <a class="nav-link active" data-toggle="tab" href="#tab2rev">Branch Transfer Return</a>
                        <a class="nav-link" data-toggle="tab" href="#tab3out">Branch Transfer Out</a>
                        <a class="nav-link" data-toggle="tab" href="#tab4out">Branch Transfer In</a>
                    </nav>
                    <div class="card-body tab-content h-100">
                        <div class="tab-pane active" id="tab2rev">
                            <div class="table-responsive">
                                <table class="table" id="product_table_1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Transfer Id</th>
                                            <th>Product Name</th>
                                            <th>Model No.</th>
                                            <th>From</th>
											<th>Category</th>
											<th>Size</th>
                                            <th>Created By</th>
											
                                            <th>Qty</th>
                                            <th>Status</th>
                                            <th>Reason</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab3out">
                            <div class="table-responsive">
                                <table class="table" id="product_table_2" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Branch To</th>
                                            <!--<th>Created By</th> -->
                                            <th>Product Name</th>
											<!--<th>Category</th> -->
                                            <th>Product Code</th>
                                            <th>Colour</th>
                                            <th>Size</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab4out">
                            <div class="table-responsive">
                                <table class="table" id="product_table_4" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Transfer Id</th>
                                            <th>Product Name</th>
                                            <th>Model No</th>
                                            <th>From</th>
											<th>Category</th>
											<th>Size</th>
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

            @include('backend.admin.messages.message-jquery-confirm-function')


            let table1 = $('#product_table_1').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse.wareHouseDashboard.getBranchTransferReturnList') }}",
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
                        data: 'product_code',
                        name: 'product_code'
                    },
                    {
                        data: 'from',
                        name: 'from'
                    },
					{
                        data: 'category',
                        name: 'category'
                    },
					{
                        data: 'size',
                        name: 'size'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'reason',
                        name: 'color'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });


            let table2 = $('#product_table_2').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse.wareHouseDashboard.getBranchTransferOutList') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'branch_to',
                        name: 'branch_to'
                    },
                   {
                        data: 'product_name',
                        name: 'product_name'
                    },
					/*{
                        data: 'category',
                        name: 'category'
                    },*/
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
                ],
            });


            //code for conformProduct data
            $(document).on('click', '.conformProduct', function() {

                let stock_row_id = $(this).data('id');
                let branch_transfered_return_user = $(this).data('branch_transfered_return_user');
                let branch_transfered_return_date = $(this).data('branch_transfered_return_date');
                let branch_transfered_return_branch_in = $(this).data('branch_transfered_return_branch_in');
                let product_id = $(this).data('product_id');
                let return_qty = $(this).data('return_qty');

                $.confirm({
                    title: "{{ __('Hello!') }}",
                    content: "{{ __('Are you sure to accept this product?') }}",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {

                                {{-- let url = "{{route('category.destroy',':delete_id')}}"; --}}
                                {{-- url = url.replace(':delete_id', delete_id); --}}

                                let formData = new FormData();
                                formData.append('stock_row_id', stock_row_id);
                                formData.append('branch_transfered_return_user',
                                    branch_transfered_return_user);
                                formData.append('branch_transfered_return_date',
                                    branch_transfered_return_date);
                                formData.append('branch_transfered_return_branch_in',
                                    branch_transfered_return_branch_in);
                                formData.append('product_id', product_id);
                                formData.append('return_qty', return_qty);

                                $.ajax({
                                    url: '{{ route('warehouse-resource.store') }}',
                                    type: "POST",
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(data) {
                                        console.log(data);

                                        if (data.success) {
                                            alertSuccessMessage(data.success)
                                            table1.draw();
                                        } else if (data.errors_success) {
                                            alertErrorMessage(data.errors_success)
                                            return false;
                                        } else if (data.errors_validation) {
                                            let html = '';
                                            for (let count = 0; count < data
                                                .errors_validation.length; count++
                                            ) {
                                                html += '<p>' + data
                                                    .errors_validation[count] +
                                                    '</p>';
                                            }
                                            alertErrorMessage(html)
                                            return false;
                                        } else {
                                            alertErrorMessage(
                                                "Something Went Wrong")
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
            //code for conformProduct data


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
                    name: "branchTransferReturn",
                    filename: "branchTransferReturn-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });



            $(document).on('click', '.exportToExcel2', function(e) {
                $("#product_table_2").table2excel({
                    exclude: ".noExl",
                    name: "branchTransferOut",
                    filename: "branchTransferOut-" + today + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true,
                    preserveColors: false
                });
            });

            $('.livesearch').select2({
                placeholder: 'Select product',
                width: '100%',
                ajax: {
                    url: '{{ route('warehouse.wareHouseDashboard.getWarehouseStockListSearch') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                // return {
                                //     text: item.product_code + ', ' + item.product_name,
                                //     id: item.id
                                // }
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
                            url: '{{ route('warehouse.wareHouseDashboard.getWarehouseStockListBySearch') }}',
                            data: function(d) {
                                d.product_id = product_id;
                            },
                        },
                        columns: [
                            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                            // {data: 'product_name', name: 'product_name'},
                            // {data: 'product_code', name: 'product_code'},
                            // {data: 'category', name: 'category'},
                            // {data: 'color', name: 'color'},
                            // {data: 'size', name: 'size'},
                            // {data: 'opening_qty', name: 'opening_quantity'},
                            // {data: 'closing_qty', name: 'closing_qty'},
                            {
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
                                data: 'product_name',
                                name: 'product_name'
                            },
                            {
                                data: 'product_code',
                                name: 'product_code'
                            },
                            {
                                data: 'category',
                                name: 'category'
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
                                data: 'closing_qty',
                                name: 'closing_qty'
                            },
                        ],
                    });
                    product_table_search.destroy();
                }
            });





            //tab three branch transfer to warehouse*************************************************************************
            let table = $('#product_table_4').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse.warehouseDashboard.getWarehouseTransferInList') }}",
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
                        data: 'model_no',
                        name: 'model_no'
                    },
                    {
                        data: 'from',
                        name: 'from'
                    },
					{
                        data: 'category',
                        name: 'category'
                    },
					{
                        data: 'size',
                        name: 'size'
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
                    url: "{{ route('warehouse.warehouseDashboard.postWarehouseTransferInList') }}",
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
            //tab three branch transfer to warehouse*************************************************************************

        });
    </script>
@endsection
