@extends('backend.branch.layouts.master')
@section('title')
    Create Estimate
@endsection
@section('extra-css')
    <style>
        @media screen and (max-width: 600px) {
            .title_message {
                visibility: hidden;
                clear: both;
                float: left;
                margin: 10px auto 5px 20px;
                width: 28%;
                display: none;
            }
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Create Estimate</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Estimate</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="{{ route('estimate-list.index') }}"><i
                        class="fe fe-external-link"></i> Go Back</a>
            </div>
        </div>
        <!-- End Page Header -->


        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                @php
                                    $payment_type_current_session_value = session('payment_type');
                                    if (empty($payment_type_current_session_value)) {
                                        $payment_type_current_session_value = 'CASH WITH NO DUES';
                                    }
                                @endphp
                                <h6 class="card-title mb-1">SELECT PAYMENT TYPE</h6>
                                <hr>
                                <div class="row row-xs align-items-center mg-b-20">
                                    <div class="col-md-2">
                                        <input type="radio" class="payment_type" data-id="cash_with_no_dues"
                                            id="cash_with_no_dues" name="payment_type" value="CASH WITH NO DUES"
                                            {{ $payment_type_current_session_value == 'CASH WITH NO DUES' ? 'checked' : '' }}>
                                        <label for="cash_with_no_dues">Cash Estimate</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="radio" class="payment_type" data-id="credit_with_dues"
                                            id="credit_with_dues" name="payment_type" value="CREDIT WITH DUES"
                                            {{ $payment_type_current_session_value == 'CREDIT WITH DUES' ? 'checked' : '' }}>
                                        <label for="credit_with_dues">Credit Estimate</label>
                                    </div>
                                    <div class="col-md-3 ">
                                        <input type="radio" class="payment_type" data-id="order_to_make_with_credit"
                                            id="order_to_make_with_credit" name="payment_type"
                                            value="ORDER TO MAKE WITH CREDIT"
                                            {{ $payment_type_current_session_value == 'ORDER TO MAKE WITH CREDIT' ? 'checked' : '' }}>
                                        <label for="order_to_make_with_credit">Order to make Estimate</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card overflow-hidden">
                    @include('backend.branch.messages.message-jquery-confirm')
                    <form enctype="multipart/form-data" action="{{ route('estimate-list.store') }}" method="post"
                        id="submit_form">
                        @method('POST')
                        @csrf
                        <input name="from_branch_id" id="from_branch_id" value="{{ auth()->user()->branch_id }}"
                            type="hidden">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="col-md-12">
                                                <h6 class="card-title mb-1">CLIENT DETAILS</h6>
                                                <hr>
                                                <div class="row row-xs align-items-center mg-b-20">
                                                    <div class="col-md-4">
                                                        <label class="mg-b-0">Mobile*</label>
                                                    </div>
                                                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                        <input class="form-control" name="client_mobile" id="client_mobile"
                                                            type="text" min="10" minlength="10" maxlength="10" max="10"
                                                            value="" required>
                                                    </div>
                                                </div>
                                                <div class="row row-xs align-items-center mg-b-20">
                                                    <div class="col-md-4">
                                                        <label class="mg-b-0">Name*</label>
                                                    </div>
                                                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                        <input class="form-control" name="client_name" id="client_name"
                                                            type="text" value="" required>
                                                    </div>
                                                </div>
                                                <div class="row row-xs align-items-center mg-b-20">
                                                    <div class="col-md-4">
                                                        <label class="mg-b-0">Email</label>
                                                    </div>
                                                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                        <input class="form-control" name="client_email" id="client_email"
                                                            value="" type="email">
                                                    </div>
                                                </div>
                                                <div class="row row-xs align-items-center mg-b-20">
                                                    <div class="col-md-4">
                                                        <label class="mg-b-0">Address*</label>
                                                    </div>
                                                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                        <input class="form-control" name="client_address"
                                                            id="client_address" value="" type="text" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="col-md-12">
                                                <h6 class="card-title mb-1">ESTIMATE</h6>
                                                <hr>
                                                <div class="row row-xs align-items-center mg-b-20">
                                                    <div class="col-md-4">
                                                        <label class="mg-b-0">Estimate No</label>
                                                    </div>
                                                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                        <input class="form-control" name="estimate_no"
                                                            value="{{ $estimate_no }}" id="estimate_no" type="text"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row row-xs align-items-center mg-b-20">
                                                    <div class="col-md-4">
                                                        <label class="mg-b-0">Estimate date*</label>
                                                    </div>
                                                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                        <input name="estimate_date" class="form-control pull-right"
                                                            id="estimate_date" value="{{ old('estimate_date') }}"
                                                            placeholder="MM/DD/YYYY" required>
                                                    </div>
                                                </div>

                                                <div class="row row-xs align-items-center mg-b-20">
                                                    <div class="col-md-4">
                                                        <label class="mg-b-0">Expected Delivery Date*</label>
                                                    </div>
                                                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                        <input name="expected_delivery_date" id="expected_delivery_date"
                                                            class="form-control pull-right"
                                                            value="{{ old('expected_delivery_date') }}"
                                                            placeholder="MM/DD/YYYY" required>
                                                    </div>
                                                </div>


                                                <div class="row row-xs align-items-center mg-b-20">
                                                    <div class="col-md-4">
                                                        <label class="mg-b-0">Sale By*</label>
                                                    </div>
                                                    <div class="col-md-8 mg-t-5 mg-md-t-0">
                                                        <input name="sale_by" id="sale_by" class="form-control pull-right"
                                                            value="" required>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <hr>
                                    <div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">




                                        <!--READY PRODUCT -->
                                        <a aria-controls="collapseExample" aria-expanded="true"
                                            class="btn ripple btn-primary btn-block text-left ready_product_human"
                                            data-toggle="collapse" href="#collapseExample" role="button">READY PRODUCT</a>
                                        <div class="collapse mg-t-5 show ready_product_human" id="collapseExample">
                                            <div class="card-body">
                                                <div class="text-right mb-2 ">
                                                    <a class="btn ripple btn-info btn-sm createProduct"
                                                        href="javascript:void(0);"><i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>SR. NO</th>
                                                                <th>PRODUCT NAME</th>
                                                                <th>MODEL NO.</th>
                                                                <th>COLOR</th>
                                                                <th>SIZE</th>
                                                                <th>QTY</th>
                                                                <th>RATE</th>
                                                                <th>AMOUNT</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="ajax_fetch_ready_product">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--READY PRODUCT -->




                                        <!--ORDER TO MAKE -->
                                        <a aria-controls="collapseExample" aria-expanded="true"
                                            class="btn ripple btn-primary btn-block text-left otm_product_human"
                                            data-toggle="collapse" href="#collapseExample1" role="button">ORDER TO MAKE</a>
                                        <div class="collapse mg-t-5 show otm_product_human" id="collapseExample1">
                                            <div class="card-body">
                                                <div class="text-right mb-2 ">
                                                    <a class="btn ripple btn-info btn-sm createProductCustomized"
                                                        href="javascript:void(0)">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>SR. NO</th>
                                                                <th>PRODUCT NAME</th>
                                                                <th>MODEL NO.</th>
                                                                <th>COLOR</th>
                                                                <th>SIZE</th>
                                                                <th>QTY</th>
                                                                <th>RATE</th>
                                                                <th>AMOUNT</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="ajax_fetch_customized_product">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--ORDER TO MAKE -->








                                        <!--calculation down table -->
                                        <div class="table-responsive mg-t-40">
                                            <table class="table table-invoice table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td class="valign-middle" colspan="2">
                                                            <textarea class="form-control" placeholder="Remarks..."
                                                                name="remarks" id="remarks"></textarea>
                                                        </td>
                                                        <td class="tx-right ">SUBTOTAL</td>
                                                        <td class="tx-right sub_total_text">0.00</td>
                                                        <input type="hidden" name="sub_total" value="" id="sub_total">
                                                    </tr>
                                                    <tr>
                                                        <td class="valign-middle title_message" colspan="2" rowspan="8">
                                                            <div class="invoice-notes">
                                                                <label class="main-content-label tx-13">Notes</label>
                                                                <p>GST, Packing and Cartage will be extra as
                                                                    applicable
                                                                </p>
                                                            </div>
                                                        </td>
                                                        <td class="tx-right">DISCOUNT &#8377;</td>
                                                        <td class="tx-right" colspan="2"><input class="form-control"
                                                                id="discount_percent" name="discount_percent" type="text">
                                                            <input type="hidden" name="discount_value" value=""
                                                                id="discount_value">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tx-right">FREIGHT</td>
                                                        <td class="tx-right" colspan="2"><input class="form-control"
                                                                id="freight_charge" name="freight_charge" type="text">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tx-right">MISC</td>
                                                        <td class="tx-right" colspan="2"><input class="form-control"
                                                                id="misc_charge" name="misc_charge" type="text">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tx-right">GRAND TOTAL</td>
                                                        <td class="tx-right" colspan="2"><input class="form-control"
                                                                id="grand_total" name="grand_total" type="text" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tx-right">PAID IN CASH</td>
                                                        <td class="tx-right" colspan="2"><input class="form-control"
                                                                id="paid_in_cash" name="paid_in_cash" type="number">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tx-right">PAID IN BANK</td>
                                                        <td class="tx-right" colspan="2"><input class="form-control"
                                                                id="paid_in_bank" name="paid_in_bank" type="number">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tx-right">TOTAL PAID</td>
                                                        <td class="tx-right" colspan="2"><input class="form-control"
                                                                required="" type="number" id="total_paid" name="total_paid"
                                                                readonly></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="tx-right">DUES AMOUNT</td>
                                                        <td class="tx-right" colspan="2"><input class="form-control"
                                                                required="" id="dues_amount" name="dues_amount"
                                                                type="number" readonly>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!--calculation down table -->





                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button class="btn ripple btn-primary form_submit_button" type="submit">Submit</button>
                                <a class="btn ripple btn-secondary" href="{{ route('estimate-list.index') }}">Go Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    
    
    <!-- CREATE MODAL START-->
    <div class="modal" id="modal_demo1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Ready Product</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code_choose">Product Code</label>
                                <!--<select class="form-control" id="product_code_choose" name="product_code_choose" required>-->
                                <!--    <option value="" selected>Choose Product Code</option>-->
                                <!--    @if (!empty($products))-->
                                <!--        @foreach ($products as $product)-->
                                <!--            <option value="{{ $product->id }}">{{ $product->product_code }}</option>-->
                                <!--        @endforeach-->
                                <!--    @endif-->
                                <!--</select>-->
                                
                                
                                 <select class="livesearch form-control" name="product_code_choose" id="product_code_choose"
                                                autocomplete="off" required></select>
                                
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" id="product_id" value="">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input class="form-control" name="product_name" id="product_name" type="text" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Code (model no)</label>
                                <input class="form-control" placeholder="" name="product_code" id="product_code" type="text"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code">Color</label>
                                <input class="form-control" placeholder="" name="color" id="color" type="text" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color_code">Size </label><br>
                                <input class="form-control" placeholder="" id="size" name="size" type="text" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Qty </label>
                                <input class="form-control" placeholder="" id="qty" name="qty" type="number" min="1"
                                    minlength="1" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maximum_retail_price">RATE </label>
                                <input class="form-control" placeholder="" id="maximum_retail_price"
                                    name="maximum_retail_price" type="number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maximum_retail_price">Amount</label>
                                <input class="form-control" placeholder="" id="amount" name="amount" type="text" readonly>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size">Stock qty </label>
                                <input class="form-control" id="product_stock_qty" name="product_stock_qty" type="number"
                                    minlength="1" value="" readonly>
                            </div>
                        </div>


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
    <!-- CREATE MODAL END-->







    <!-- CREATE MODAL START-->
    <div class="modal" id="modal_demo_customized">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Order To Make</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code_choose_customized">Product Code</label>
                                
                                
                                <!--<select class="form-control" id="product_code_choose_customized"-->
                                <!--    name="product_code_choose_customized" required>-->
                                <!--    <option value="" selected>Choose Product Code</option>-->
                                <!--    @if (!empty($products))-->
                                <!--        @foreach ($products as $product)-->
                                <!--            <option value="{{ $product->id }}">{{ $product->product_code }}</option>-->
                                <!--        @endforeach-->
                                <!--    @endif-->
                                <!--</select>-->
                                
                                 <select class="livesearch2 form-control" name="product_code_choose_customized" id="product_code_choose_customized"
                                                autocomplete="off" required></select>
                                
                                
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" id="product_id_customized" value="">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name_customized">Product Name</label>
                                <input class="form-control" name="product_name_customized" id="product_name_customized"
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_code_customized">Product Code (model no)</label>
                                <input class="form-control" placeholder="" name="product_code_customized"
                                    id="product_code_customized" type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color_customized">Color</label>
                                <input class="form-control" placeholder="" name="color_customized" id="color_customized"
                                    type="text" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="size_customized">Size </label><br>
                                <input class="form-control" placeholder="" id="size_customized" name="size_customized"
                                    type="text" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="qty_customized">Qty </label>
                                <input class="form-control" placeholder="" id="qty_customized" name="qty_customized"
                                    type="number" min="1" minlength="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maximum_retail_price_customized">RATE </label>
                                <input class="form-control" placeholder="" id="maximum_retail_price_customized"
                                    name="maximum_retail_price_customized" type="number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount_customized">Amount</label>
                                <input class="form-control" placeholder="" id="amount_customized" name="amount_customized"
                                    type="text" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary createProductButtonCustomized" type="button">Submit</button>
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

            @include('backend.admin.messages.message-jquery-confirm-function')

            $('#client_mobile').keyup(function(e) {
                if (/\D/g.test(this.value)) {
                    this.value = this.value.replace(/\D/g, '');
                }
            });

            $(document).on('keyup', '.maximum_retail_price_ready_product_table', function(e) {
                if (/\D/g.test(this.value)) {
                    this.value = this.value.replace(/\D/g, '');
                }
            });

            $(document).on('keyup', '.mrp_order_to_make_table', function(e) {
                if (/\D/g.test(this.value)) {
                    this.value = this.value.replace(/\D/g, '');
                }
            });


            //code for open modal for create
            $(document).on('click', '.createProduct', function() {
                $('#modal_demo1').modal('show');
                $('#color').val('');
                $('#size').val('');
                $('#qty').val('');
                $('#maximum_retail_price').val('');
                $('#amount').val('');
                $('#product_code_choose').val('');
                $('#product_id').val('');
                $('#product_code').val('');
                $('#product_name').val('');

            });
            //code for open modal for create


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



             $('.livesearch2').select2({
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



            //get product details
            $(document).on('change', '#product_code_choose', function() {
                let product_code_choose = $(this).val();
                if (product_code_choose !== '') {

                    let url =
                        "{{ route('branch.estimateList.getReadyProductDetail', ':product_code_choose') }}";
                    url = url.replace(':product_code_choose', product_code_choose);

                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(data) {
                            $('#product_id').val(data.product.id);
                            $('#color').val(data.product.color_code);
                            $('#product_code').val(data.product.product_code);
                            $('#size').val(data.product.size);
                            $('#qty').val(1).attr('readonly', false);
                            $('#maximum_retail_price').val(data.product.maximum_retail_price);
                            $('#amount').val(data.product.maximum_retail_price);
                            $('#product_name').val(data.product.product_name);
                            $('#product_stock_qty').val(data.product_stock_qty);
                        }
                    });
                } else {
                    $('#product_id').val('');
                    $('#product_name').val('');
                    $('#color').val('');
                    $('#product_code').val('');
                    $('#size').val('');
                    $('#qty').val('').attr('readonly', true);
                    $('#maximum_retail_price').val('');
                    $('#amount').val('');
                    $('#product_stock_qty').val('');
                }
            });
            //get product details


            //calculate the amount for ready product maximum_retail_price
            $(document).on('change keyup keydown', '#maximum_retail_price', function() {
                let maximum_retail_price = $(this).val();
                let qty = $('#qty').val();
                let amount = qty * maximum_retail_price;
                $('#amount').val(amount);
            });
            //calculate the amount for ready product maximum_retail_price


            //calculate the amount for ready product
            $(document).on('change keyup keydown', '#qty', function() {
                let qty = $(this).val();
                let maximum_retail_price = $('#maximum_retail_price').val();
                let amount = qty * maximum_retail_price;
                $('#amount').val(amount);
            });
            //calculate the amount for ready product


            //new
            let sl_no = 1;
            let sl_no_counter = 1;
            let sl_no_customized = 1;
            let sl_no_counter_customized = 1;


            let initial_index_start_value = 0;
            let initial_index_start_value_counter = 0;
            let readyProductArray = [];
            //new


            $(document).on('click', '.createProductButton', function(e) {
                e.preventDefault();

                let product_name = $('#product_name').val();
                let product_id = $('#product_id').val();
                let product_code = $('#product_code').val();
                let color = $('#color').val();
                let size = $('#size').val();
                let qty = $('#qty').val();
                let maximum_retail_price = $('#maximum_retail_price').val();
                let amount = $('#amount').val();
                let total = 0;
                let product_type = "READY PRODUCT";

                if (color == '') {
                    color = 'NA';
                }

                if (product_id === '') {
                    alertErrorMessage("{{ __('product id cannot be Empty') }}.")
                    return false;
                } else if (product_name === '') {
                    alertErrorMessage("{{ __('product name cannot be Empty') }}.")
                    return false;
                } else if (product_code === '') {
                    alertErrorMessage("{{ __('product code cannot be Empty') }}.")
                    return false;
                } else if (color === '') {
                    alertErrorMessage("{{ __('color cannot be Empty') }}.")
                    return false;
                } else if (size === '') {
                    alertErrorMessage("{{ __('size cannot be Empty') }}.")
                    return false;
                } else if (qty === '') {
                    alertErrorMessage("{{ __('qty cannot be Empty') }}.")
                    return false;
                } else if (maximum_retail_price === '') {
                    alertErrorMessage("{{ __('rate cannot be Empty') }}.")
                    return false;
                } else if (amount === '') {
                    alertErrorMessage("{{ __('amount cannot be Empty') }}.")
                    return false;
                } else {

                    //check already added or not
                    function findValueInArrayObject(product_id, product_type) {
                        return readyProductArray.some(function(el) {
                            return el.product_id === product_id && el.product_type === product_type;
                        });
                    }

                    let checkAccessory = findValueInArrayObject(product_id, product_type);
                    //check already added or not

                    if (checkAccessory === true) {
                        alertErrorMessage("{{ __('This product is already added') }}.");
                        return false;
                    } else {

                        initial_index_start_value_counter = initial_index_start_value++;
                        sl_no_counter = sl_no++;
                        let html = '<tr id="acc' + initial_index_start_value_counter + '">' +
                            '<td>' + sl_no_counter + '</td>' +
                            '<td>' + product_name + '</td>' +
                            '<td>' + product_code + '</td>' +
                            '<td>' + color + '</td>' +
                            '<td>' + size + '</td>' +
                            '<td><input id="quantity_ready_product_table' + product_id + '"  data-id="' +
                            product_id + '" data-index="' + initial_index_start_value_counter +
                            '" class="quantity_ready_product_table" type="number" min="1" minlength="1" value="' +
                            qty + '"></td>' +

                            '<td><input id="maximum_retail_price_ready_product_table' + product_id +
                            '" data-id="' + product_id + '" data-index="' +
                            initial_index_start_value_counter +
                            '" class="maximum_retail_price_ready_product_table" type="number" min="1" minlength="1" value="' +
                            maximum_retail_price + '"></td>' +

                            '<td class="amount_ready_product_table" id="amount_ready_product_table' +
                            product_id + '">&#8377; ' + amount + '</td>' +
                            '<td><a href="javascript:void(0)" data-index="' +
                            initial_index_start_value_counter + '"  data-product_id ="' + product_id +
                            '"  class="btn-danger btn-sm removeProductButton"><i class="fa fa-trash"></i></a></td>' +
                            '</tr>';
                        $('.ajax_fetch_ready_product').append(html);


                        let readyProductArrayObject = {
                            product_id: product_id,
                            product_name: product_name,
                            product_code: product_code,
                            color: color,
                            size: size,
                            qty: parseInt(qty),
                            maximum_retail_price: parseInt(maximum_retail_price),
                            amount: parseInt(amount),
                            product_type: product_type,
                            index: initial_index_start_value_counter,
                        };
                        readyProductArray.push(readyProductArrayObject);


                        //ready product total
                        readyProductArray.forEach(function(value, index, arry) {
                            total += value.amount;
                        });
                        //ready product total


                        let total_plus_amount = parseFloat(total);
                        $('.sub_total_text').html('&#8377; ' + total_plus_amount);
                        $('#sub_total').val(total_plus_amount);

                        let discount_percent = $('#discount_percent').val();
                        //let discount_value = ((total_plus_amount * discount_percent) / 100);
                        let discount_value = discount_percent;
                        if (discount_value === '') {
                            discount_value = 0;
                        }

                        $('#discount_value').val(discount_value);


                        let freight_charge = $('#freight_charge').val();
                        if (freight_charge == '') {
                            freight_charge = 0;
                        }
                        let misc_charge = $('#misc_charge').val();
                        if (misc_charge == '') {
                            misc_charge = 0;
                        }
                        let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                        //let grand_total = (parseFloat(sub_total) + parseFloat(discount_value))-freight_misc_charge ;
                        let grand_total = (parseFloat(total_plus_amount) + freight_misc_charge) -
                            parseFloat(discount_value);
                        $('#grand_total').val(grand_total.toFixed(2));


                        let paid_in_cash = $('#paid_in_cash').val();
                        if (paid_in_cash == '') {
                            paid_in_cash = 0;
                        }
                        let paid_in_bank = $('#paid_in_bank').val();
                        if (paid_in_bank == '') {
                            paid_in_bank = 0;
                        }

                        let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                        $('#total_paid').val(Math.round(cash_plus_bank));

                        let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                        $('#dues_amount').val(Math.round(dues_amount));

                        toastr["success"]("Ready Product added successfully", "Notification")
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        $('#modal_demo1').modal('hide');
                        console.log(readyProductArray);
                    }


                }
            });
            // code for add to ready product


            //calculate the discount
            $(document).on('change keyup keydown', '#discount_percent', function() {

                let discount_percent = $(this).val();
                let sub_total = $('#sub_total').val();

                //let discount_value = ((sub_total * discount_percent) / 100);
                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                $('#discount_value').val(discount_value);

                let freight_charge = $('#freight_charge').val();
                if (freight_charge == '') {
                    freight_charge = 0;
                }
                let misc_charge = $('#misc_charge').val();
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                //let grand_total = (parseFloat(sub_total) + parseFloat(discount_value))-freight_misc_charge ;
                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                let paid_in_cash = $('#paid_in_cash').val();
                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }
                let paid_in_bank = $('#paid_in_bank').val();
                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));

            });
            //calculate the discount


            //calculate the paid_in_cash
            $(document).on('change keyup keydown', '#paid_in_cash', function() {

                let paid_in_cash = $(this).val();
                let discount_percent = $('#discount_percent').val();
                let sub_total = $('#sub_total').val();

                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                //let discount_value = ((sub_total * discount_percent) / 100);
                $('#discount_value').val(discount_value);


                let freight_charge = $('#freight_charge').val();
                if (freight_charge == '') {
                    freight_charge = 0;
                }
                let misc_charge = $('#misc_charge').val();
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }
                let paid_in_bank = $('#paid_in_bank').val();
                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));

            });
            //calculate the paid_in_cash


            //calculate the paid_in_bank
            $(document).on('change keyup keydown', '#paid_in_bank', function() {

                let paid_in_bank = $(this).val();
                let paid_in_cash = $('#paid_in_cash').val();
                let discount_percent = $('#discount_percent').val();
                let sub_total = $('#sub_total').val();

                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                //let discount_value = ((sub_total * discount_percent) / 100);
                $('#discount_value').val(discount_value);


                let freight_charge = $('#freight_charge').val();
                if (freight_charge == '') {
                    freight_charge = 0;
                }
                let misc_charge = $('#misc_charge').val();
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }

                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));

            });
            //calculate the paid_in_bank


            //calculate the freight_charge
            $(document).on('change keyup keydown', '#freight_charge', function() {

                let freight_charge = $(this).val();
                let paid_in_bank = $('#paid_in_bank').val();
                let paid_in_cash = $('#paid_in_cash').val();
                let discount_percent = $('#discount_percent').val();
                let sub_total = $('#sub_total').val();

                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                //let discount_value = ((sub_total * discount_percent) / 100);
                $('#discount_value').val(discount_value);


                if (freight_charge == '') {
                    freight_charge = 0;
                }
                let misc_charge = $('#misc_charge').val();
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }

                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));

            });
            //calculate the freight_charge


            //calculate the misc_charge
            $(document).on('change keyup keydown', '#misc_charge', function() {

                let misc_charge = $(this).val();
                let freight_charge = $('#freight_charge').val();
                let paid_in_bank = $('#paid_in_bank').val();
                let paid_in_cash = $('#paid_in_cash').val();
                let discount_percent = $('#discount_percent').val();
                let sub_total = $('#sub_total').val();

                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                //let discount_value = ((sub_total * discount_percent) / 100);
                $('#discount_value').val(discount_value);


                if (freight_charge == '') {
                    freight_charge = 0;
                }
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }

                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));

            });
            //calculate the misc_charge


            //quantity change
            $(document).on('change keyup keydown', '.quantity_ready_product_table', function(e) {
                e.preventDefault();
                let product_id = $(this).data('id');
                let this_value = $(this).val();
                let this_index = $(this).data('index');
                console.log(this_value);


                //text updates
                let maximum_retail_price_ready_product_table = $(
                    "#maximum_retail_price_ready_product_table" + product_id).val();
                //maximum_retail_price_ready_product_table = maximum_retail_price_ready_product_table.substring(2);

                let amount_ready_product_table = parseFloat(this_value) * parseFloat(
                    maximum_retail_price_ready_product_table);
                $("#amount_ready_product_table" + product_id).html('&#8377; ' + amount_ready_product_table);
                //text updates


                //update object values
                objIndex = readyProductArray.findIndex((obj => obj.index == this_index));
                readyProductArray[objIndex].qty = this_value;
                readyProductArray[objIndex].amount = amount_ready_product_table;
                //update object values


                //ready product total
                let total_amount = 0;
                readyProductArray.forEach(function(value, index, arry) {
                    total_amount += value.amount;
                });
                //ready product total


                //set sub total value
                $('#sub_total').val(total_amount);
                $('.sub_total_text').html('&#8377; ' + total_amount);
                //set sub total value


                let discount_percent = $('#discount_percent').val();
                let sub_total = $('#sub_total').val();

                //let discount_value = ((sub_total * discount_percent) / 100);
                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                $('#discount_value').val(discount_value);


                let freight_charge = $('#freight_charge').val();
                if (freight_charge == '') {
                    freight_charge = 0;
                }
                let misc_charge = $('#misc_charge').val();
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                console.log(freight_misc_charge);


                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                let paid_in_cash = $('#paid_in_cash').val();
                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }
                let paid_in_bank = $('#paid_in_bank').val();
                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));
            });
            //quantity change


            //maximum_retail_price_ready_product_table change
            $(document).on('change keyup keydown', '.maximum_retail_price_ready_product_table', function(e) {
                //e.preventDefault();
                let product_id = $(this).data('id');
                let this_value = $(this).val();
                let this_index = $(this).data('index');


                //  $('#maximum_retail_price').val(this_value);


                //text updates
                let quantity_ready_product_table = $("#quantity_ready_product_table" + product_id).val();
                let amount_ready_product_table = parseFloat(this_value) * parseFloat(
                    quantity_ready_product_table);
                $("#amount_ready_product_table" + product_id).html('&#8377; ' + amount_ready_product_table);
                //text updates


                //update object values
                objIndex = readyProductArray.findIndex((obj => obj.index == this_index));
                readyProductArray[objIndex].amount = amount_ready_product_table;
                readyProductArray[objIndex].maximum_retail_price = this_value;
                //update object values


                //ready product total
                let total_amount = 0;
                readyProductArray.forEach(function(value, index, arry) {
                    total_amount += value.amount;
                });
                //ready product total


                //set sub total value
                $('#sub_total').val(total_amount);
                $('.sub_total_text').html('&#8377; ' + total_amount);
                //set sub total value


                let discount_percent = $('#discount_percent').val();
                let sub_total = $('#sub_total').val();

                //let discount_value = ((sub_total * discount_percent) / 100);
                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                $('#discount_value').val(discount_value);


                let freight_charge = $('#freight_charge').val();
                if (freight_charge == '') {
                    freight_charge = 0;
                }
                let misc_charge = $('#misc_charge').val();
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                console.log(freight_misc_charge);


                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                let paid_in_cash = $('#paid_in_cash').val();
                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }
                let paid_in_bank = $('#paid_in_bank').val();
                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));
            });
            //maximum_retail_price_ready_product_table change


            //remove ready product
            $(document).on('click', '.removeProductButton', function(e) {
                e.preventDefault();

                let product_id = $(this).data("product_id");
                let this_index = $(this).data("index");

                for (let i = readyProductArray.length - 1; i >= 0; --i) {
                    if (readyProductArray[i].index == this_index) {
                        readyProductArray.splice(i, 1);
                    }
                }


                //ready product total
                let total_amount = 0;
                readyProductArray.forEach(function(value, index, arry) {
                    total_amount += value.amount;
                });
                //ready product total

                $('#sub_total').val(total_amount);

                let sub_total = $('#sub_total').val();
                $('.sub_total_text').html('&#8377; ' + sub_total);


                let discount_percent = $('#discount_percent').val();
                // let discount_value = ((sub_total * discount_percent) / 100);
                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                $('#discount_value').val(discount_value);


                let freight_charge = $('#freight_charge').val();
                if (freight_charge == '') {
                    freight_charge = 0;
                }
                let misc_charge = $('#misc_charge').val();
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                let paid_in_cash = $('#paid_in_cash').val();
                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }
                let paid_in_bank = $('#paid_in_bank').val();
                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));

                $('#acc' + this_index).remove();
                sl_no_counter = sl_no--;

                toastr["warning"]("Ready Product removed successfully", "Notification")
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            });
            //remove ready product
            //**********************************************READY************************************************************


            //*********************************************CUSTOMIZED*************************************************************
            //code for open modal for create customized product
            $(document).on('click', '.createProductCustomized', function() {
                $('#modal_demo_customized').modal('show');
                $('#product_id_customized').val('');
                $('#product_name_customized').val('');
                $('#color_customized').val('');
                $('#size_customized').val('');
                $('#qty_customized').val('');
                $('#maximum_retail_price_customized').val('');
                $('#amount_customized').val('');
                $('#product_code_choose_customized').val('');
            });
            //code for open modal for create customized product


            //get product details
            $(document).on('change', '#product_code_choose_customized', function() {
                let product_id = $(this).val();
                if (product_id !== '') {

                    let url = "{{ route('branch.estimateList.getReadyProductDetail', ':product_id') }}";
                    url = url.replace(':product_id', product_id);

                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(data) {
                            $('#color_customized').val(data.product.color_code);
                            $('#product_code_customized').val(data.product.product_code);
                            $('#size_customized').val(data.product.size);
                            $('#qty_customized').val(1).attr('readonly', false);
                            $('#maximum_retail_price_customized').val(data.product
                                .maximum_retail_price);
                            $('#amount_customized').val(data.product.maximum_retail_price);
                            $('#product_name_customized').val(data.product.product_name);
                            $('#product_id_customized').val(data.product.id);

                        }
                    });
                } else {
                    $('#color_customized').val('');
                    $('#product_code_customized').val('');
                    $('#product_name_customized').val('');
                    $('#size_customized').val('');
                    $('#qty_customized').val('').attr('readonly', true);
                    $('#maximum_retail_price_customized').val('');
                    $('#amount_customized').val('');
                    $('#product_id_customized').val('');
                }
            });
            //get product details


            //calculate the amount for ready product
            $(document).on('change keyup keydown mouseup', '#qty_customized', function() {
                let qty = $(this).val();
                let maximum_retail_price = $('#maximum_retail_price_customized').val();
                let amount = qty * maximum_retail_price;
                $('#amount_customized').val(amount);
            });
            //calculate the amount for ready product


            //calculate the amount for customized product
            $(document).on('change keyup keydown mouseup', '#maximum_retail_price_customized', function() {
                let maximum_retail_price_customized = $(this).val();
                let qty = $('#qty_customized').val();
                let amount = qty * maximum_retail_price_customized;
                $('#amount_customized').val(amount);
            });
            //calculate the amount for customized product


            // code for add to ready product
            $(document).on('click', '.createProductButtonCustomized', function(e) {
                e.preventDefault();

                let product_name = $('#product_name_customized').val();
                let product_id = $('#product_id_customized').val();
                let product_code = $('#product_code_customized').val();
                let color = $('#color_customized').val();
                let size = $('#size_customized').val();
                let qty = $('#qty_customized').val();
                let maximum_retail_price = $('#maximum_retail_price_customized').val();
                let amount = $('#amount_customized').val();
                let total = 0;
                let product_type = 'ORDER TO MAKE';

                if (product_id === '') {
                    alertErrorMessage("{{ __('product id cannot be Empty') }}.")
                    return false;
                } else if (product_name === '') {
                    alertErrorMessage("{{ __('product name cannot be Empty') }}.")
                    return false;
                } else if (product_code === '') {
                    alertErrorMessage("{{ __('product code cannot be Empty') }}.")
                    return false;
                } else if (color == '') {
                    alertErrorMessage("{{ __('color cannot be Empty') }}.")
                    return false;
                } else if (size == '') {
                    alertErrorMessage("{{ __('size cannot be Empty') }}.")
                    return false;
                } else if (qty === '') {
                    alertErrorMessage("{{ __('qty cannot be Empty') }}.")
                    return false;
                } else if (maximum_retail_price === '') {
                    alertErrorMessage("{{ __('rate cannot be Empty') }}.")
                    return false;
                } else if (amount === '') {
                    alertErrorMessage("{{ __('amount cannot be Empty') }}.")
                    return false;
                } else {

                    initial_index_start_value_counter = initial_index_start_value++;


                    //check already added or not
                    function findValueInArrayObject(product_id, product_type) {
                        return readyProductArray.some(function(el) {
                            return el.product_id === product_id && el.product_type === product_type;
                        });
                    }

                    let checkAccessory = findValueInArrayObject(product_id, product_type);
                    //check already added or not

                    if (checkAccessory === true) {
                        alertErrorMessage("{{ __('This product is already added') }}.");
                        return false;
                    } else {


                        //new customize product code************************
                        $.ajax({
                            type: "POST",
                            url: '{{ route('branch.estimateList.checkCustomizeProductIsAddedOrNot') }}',
                            data: {
                                product_id: product_id,
                                product_code: product_code,
                                color: color,
                                size: size,
                                product_name: product_name,
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.error === 'Please Modify') {
                                    alertErrorMessage(
                                        "Please modify the product to add in Order to Make");
                                } else if (response.error === 'Model no already exists') {
                                    alertErrorMessage(
                                        "Model no already exists, please try different model no , either removed -ORD"
                                    )
                                } else if (response.success === 'Product Created') {

                                    product_name = response.data.product_name;
                                    product_id = response.data.id;
                                    product_code = response.data.product_code;
                                    color = response.data.color_code;
                                    size = response.data.size;
                                    sl_no_counter_customized = sl_no_customized++;
                                    let html = '<tr id="acc' +
                                        initial_index_start_value_counter + '">' +
                                        '<td>' + sl_no_counter_customized + '</td>' +
                                        '<td>' + product_name + '</td>' +
                                        '<td>' + product_code + '</td>' +
                                        '<td><input data-id="' + product_id + '" data-index="' +
                                        initial_index_start_value_counter +
                                        '" class="color_order_to_make_table" type="text"  value="' +
                                        color + '" style="width: 80px;"></td>' +
                                        '<td><input data-id="' + product_id + '" data-index="' +
                                        initial_index_start_value_counter +
                                        '" class="size_order_to_make_table" type="text"  value="' +
                                        size + '" style="width: 80px;"></td>' +
                                        '<td><input data-id="' + product_id + '" data-index="' +
                                        initial_index_start_value_counter +
                                        '" class="quantity_order_to_make_table" type="number" min="1" minlength="1"  value="' +
                                        qty + '" id="quantity_order_to_make_table' +
                                        product_id + '" style="width: 80px;"></td>' +
                                        '<td><input id="maximum_retail_price_order_to_make_table' +
                                        product_id + '" data-id="' + product_id +
                                        '" data-index="' + initial_index_start_value_counter +
                                        '" class="mrp_order_to_make_table" type="number" min="1" minlength="1"  value="' +
                                        maximum_retail_price + '" style="width: 80px;"></td>' +
                                        '<td class="amount_ready_product_table" id="amount_order_to_make_table' +
                                        product_id + '">&#8377; ' + amount + '</td>' +
                                        '<td><a href="javascript:void(0)" data-index="' +
                                        initial_index_start_value_counter +
                                        '"   data-product_id ="' + product_id +
                                        '"  class="btn-danger btn-sm removeProductButtonCustomized"><i class="fa fa-trash"></i></a></td>' +
                                        '</tr>';
                                    $('.ajax_fetch_customized_product').append(html);


                                    let readyProductArrayObject = {
                                        product_id: product_id,
                                        product_name: product_name,
                                        product_code: product_code,
                                        color: color,
                                        size: size,
                                        qty: parseInt(qty),
                                        maximum_retail_price: parseInt(
                                            maximum_retail_price),
                                        amount: parseInt(amount),
                                        product_type: product_type,
                                        index: initial_index_start_value_counter,
                                    };
                                    readyProductArray.push(readyProductArrayObject);


                                    //ready product total
                                    readyProductArray.forEach(function(value, index, arry) {
                                        total += value.amount;
                                    });
                                    //ready product total


                                    let total_plus_amount = parseFloat(total);
                                    $('.sub_total_text').html('&#8377; ' + total_plus_amount);
                                    $('#sub_total').val(total_plus_amount);

                                    let discount_percent = $('#discount_percent').val();
                                    //let discount_value = ((total_plus_amount * discount_percent) / 100);
                                    let discount_value = discount_percent;
                                    if (discount_value === '') {
                                        discount_value = 0;
                                    }
                                    $('#discount_value').val(discount_value);

                                    let sub_total = $('#sub_total').val();


                                    let freight_charge = $('#freight_charge').val();
                                    if (freight_charge == '') {
                                        freight_charge = 0;
                                    }
                                    let misc_charge = $('#misc_charge').val();
                                    if (misc_charge == '') {
                                        misc_charge = 0;
                                    }
                                    let freight_misc_charge = parseFloat(freight_charge) +
                                        parseFloat(misc_charge);
                                    let grand_total = (parseFloat(sub_total) +
                                        freight_misc_charge) - parseFloat(discount_value);
                                    $('#grand_total').val(grand_total.toFixed(2));


                                    let paid_in_cash = $('#paid_in_cash').val();
                                    if (paid_in_cash == '') {
                                        paid_in_cash = 0;
                                    }
                                    let paid_in_bank = $('#paid_in_bank').val();
                                    if (paid_in_bank == '') {
                                        paid_in_bank = 0;
                                    }

                                    let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(
                                        paid_in_bank);
                                    $('#total_paid').val(Math.round(cash_plus_bank));

                                    let dues_amount = parseFloat(grand_total) - parseFloat(
                                        cash_plus_bank);
                                    $('#dues_amount').val(Math.round(dues_amount));


                                    //toaster
                                    toastr["success"](
                                        "Order to make product added successfully",
                                        "Notification")
                                    toastr.options = {
                                        "closeButton": false,
                                        "debug": false,
                                        "newestOnTop": false,
                                        "progressBar": false,
                                        "positionClass": "toast-top-right",
                                        "preventDuplicates": false,
                                        "onclick": null,
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "5000",
                                        "extendedTimeOut": "1000",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                    }
                                    //toaster
                                    $('#modal_demo_customized').modal('hide');
                                    console.log(readyProductArray);

                                } else if (response.success === 'Product Exists') {


                                    let html = '<tr id="acc' +
                                        initial_index_start_value_counter + '">' +
                                        '<td>' + 1 + '</td>' +
                                        '<td>' + product_name + '</td>' +
                                        '<td>' + product_code + '</td>' +
                                        '<td><input data-id="' + product_id + '" data-index="' +
                                        initial_index_start_value_counter +
                                        '" class="color_order_to_make_table" type="text"  value="' +
                                        color + '" style="width: 80px;"></td>' +
                                        '<td><input data-id="' + product_id + '" data-index="' +
                                        initial_index_start_value_counter +
                                        '" class="size_order_to_make_table" type="text"  value="' +
                                        size + '" style="width: 80px;"></td>' +
                                        '<td><input data-id="' + product_id + '" data-index="' +
                                        initial_index_start_value_counter +
                                        '" class="quantity_order_to_make_table" type="number" min="1" minlength="1"  value="' +
                                        qty + '" id="quantity_order_to_make_table' +
                                        product_id + '" style="width: 80px;"></td>' +
                                        '<td><input id="maximum_retail_price_order_to_make_table' +
                                        product_id + '" data-id="' + product_id +
                                        '" data-index="' + initial_index_start_value_counter +
                                        '" class="mrp_order_to_make_table" type="number" min="1" minlength="1"  value="' +
                                        maximum_retail_price + '" style="width: 80px;"></td>' +
                                        '<td class="amount_ready_product_table" id="amount_order_to_make_table' +
                                        product_id + '">&#8377; ' + amount + '</td>' +
                                        '<td><a href="javascript:void(0)" data-index="' +
                                        initial_index_start_value_counter +
                                        '"   data-product_id ="' + product_id +
                                        '"  class="btn-danger btn-sm removeProductButtonCustomized"><i class="fa fa-trash"></i></a></td>' +
                                        '</tr>';
                                    $('.ajax_fetch_customized_product').append(html);


                                    let readyProductArrayObject = {
                                        product_id: product_id,
                                        product_name: product_name,
                                        product_code: product_code,
                                        color: color,
                                        size: size,
                                        qty: parseInt(qty),
                                        maximum_retail_price: parseInt(
                                            maximum_retail_price),
                                        amount: parseInt(amount),
                                        product_type: product_type,
                                        index: initial_index_start_value_counter,
                                    };
                                    readyProductArray.push(readyProductArrayObject);


                                    //ready product total
                                    readyProductArray.forEach(function(value, index, arry) {
                                        total += value.amount;
                                    });
                                    //ready product total


                                    let total_plus_amount = parseFloat(total);
                                    $('.sub_total_text').html('&#8377; ' + total_plus_amount);
                                    $('#sub_total').val(total_plus_amount);

                                    let discount_percent = $('#discount_percent').val();
                                    //let discount_value = ((total_plus_amount * discount_percent) / 100);
                                    let discount_value = discount_percent;
                                    if (discount_value === '') {
                                        discount_value = 0;
                                    }
                                    $('#discount_value').val(discount_value);

                                    let sub_total = $('#sub_total').val();


                                    let freight_charge = $('#freight_charge').val();
                                    if (freight_charge == '') {
                                        freight_charge = 0;
                                    }
                                    let misc_charge = $('#misc_charge').val();
                                    if (misc_charge == '') {
                                        misc_charge = 0;
                                    }
                                    let freight_misc_charge = parseFloat(freight_charge) +
                                        parseFloat(misc_charge);
                                    let grand_total = (parseFloat(sub_total) +
                                        freight_misc_charge) - parseFloat(discount_value);
                                    $('#grand_total').val(grand_total.toFixed(2));


                                    let paid_in_cash = $('#paid_in_cash').val();
                                    if (paid_in_cash == '') {
                                        paid_in_cash = 0;
                                    }
                                    let paid_in_bank = $('#paid_in_bank').val();
                                    if (paid_in_bank == '') {
                                        paid_in_bank = 0;
                                    }

                                    let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(
                                        paid_in_bank);
                                    $('#total_paid').val(Math.round(cash_plus_bank));

                                    let dues_amount = parseFloat(grand_total) - parseFloat(
                                        cash_plus_bank);
                                    $('#dues_amount').val(Math.round(dues_amount));


                                    //toaster
                                    toastr["success"](
                                        "Order to make product added successfully",
                                        "Notification")
                                    toastr.options = {
                                        "closeButton": false,
                                        "debug": false,
                                        "newestOnTop": false,
                                        "progressBar": false,
                                        "positionClass": "toast-top-right",
                                        "preventDuplicates": false,
                                        "onclick": null,
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "5000",
                                        "extendedTimeOut": "1000",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                    }
                                    //toaster
                                    $('#modal_demo_customized').modal('hide');
                                    console.log(readyProductArray);


                                }
                            }
                        });
                        //new customize product code************************


                    }
                }
            });
            // code for add to ready product


            //color_order_to_make_table change
            $(document).on('keyup', '.color_order_to_make_table', function(e) {
                e.preventDefault();
                let product_id = $(this).data('id');
                let this_value = $(this).val();
                let this_index = $(this).data('index');

                //update object values
                objIndex = readyProductArray.findIndex((obj => obj.index == this_index));
                readyProductArray[objIndex].color = this_value;
                //update object values
                console.log(readyProductArray);

            });
            //color_order_to_make_table change


            //size_order_to_make_table change
            $(document).on('keyup', '.size_order_to_make_table', function(e) {
                e.preventDefault();
                let product_id = $(this).data('id');
                let this_value = $(this).val();
                let this_index = $(this).data('index');

                //update object values
                objIndex = readyProductArray.findIndex((obj => obj.index == this_index));
                readyProductArray[objIndex].size = this_value;
                //update object values
            });
            //size_order_to_make_table change


            //quantity change
            $(document).on('change keyup keydown', '.quantity_order_to_make_table', function(e) {
                e.preventDefault();
                let product_id = $(this).data('id');
                let this_value = $(this).val();
                let this_index = $(this).data('index');


                //text updates
                let maximum_retail_price_ready_product_table = $(
                    "#maximum_retail_price_order_to_make_table" + product_id).val();
                let amount_ready_product_table = parseFloat(this_value) * parseFloat(
                    maximum_retail_price_ready_product_table);
                $("#amount_order_to_make_table" + product_id).html('&#8377; ' + amount_ready_product_table);
                //text updates


                //update object values
                objIndex = readyProductArray.findIndex((obj => obj.index == this_index));
                readyProductArray[objIndex].qty = this_value;
                readyProductArray[objIndex].amount = amount_ready_product_table;
                //update object values


                //ready product total
                let total_amount = 0;
                readyProductArray.forEach(function(value, index, arry) {
                    total_amount += value.amount;
                });
                //ready product total


                //set sub total value
                $('#sub_total').val(total_amount);
                $('.sub_total_text').html('&#8377; ' + total_amount);
                //set sub total value


                let discount_percent = $('#discount_percent').val();
                let sub_total = $('#sub_total').val();

                //let discount_value = ((sub_total * discount_percent) / 100);
                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                $('#discount_value').val(discount_value);


                let freight_charge = $('#freight_charge').val();
                if (freight_charge == '') {
                    freight_charge = 0;
                }
                let misc_charge = $('#misc_charge').val();
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                let paid_in_cash = $('#paid_in_cash').val();
                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }
                let paid_in_bank = $('#paid_in_bank').val();
                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));
            });
            //quantity change


            //mrp_order_to_make_table change
            $(document).on('change keyup keydown', '.mrp_order_to_make_table', function(e) {
                //e.preventDefault();
                let product_id = $(this).data('id');
                let this_value = $(this).val();
                let this_index = $(this).data('index');

                // $('#maximum_retail_price_customized').val(this_value);

                //text update
                let quantity_order_to_make_table = $('#quantity_order_to_make_table' + product_id).val();
                let amount_ready_product_table = parseFloat(this_value) * parseFloat(
                    quantity_order_to_make_table);
                $("#amount_order_to_make_table" + product_id).html('&#8377; ' + amount_ready_product_table);
                //text update


                //update object values
                objIndex = readyProductArray.findIndex((obj => obj.index == this_index));
                readyProductArray[objIndex].maximum_retail_price = this_value;
                readyProductArray[objIndex].amount = amount_ready_product_table;
                //update object values


                //ready product total
                let total_amount = 0;
                readyProductArray.forEach(function(value, index, arry) {
                    total_amount += value.amount;
                });
                //ready product total


                //set sub total value
                $('#sub_total').val(total_amount);
                $('.sub_total_text').html('&#8377; ' + total_amount);
                //set sub total value

                let discount_percent = $('#discount_percent').val();
                let sub_total = $('#sub_total').val();

                //let discount_value = ((sub_total * discount_percent) / 100);
                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                $('#discount_value').val(discount_value);


                let freight_charge = $('#freight_charge').val();
                if (freight_charge == '') {
                    freight_charge = 0;
                }
                let misc_charge = $('#misc_charge').val();
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                let paid_in_cash = $('#paid_in_cash').val();
                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }
                let paid_in_bank = $('#paid_in_bank').val();
                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));
            });
            //mrp_order_to_make_table change


            //remove ready product
            $(document).on('click', '.removeProductButtonCustomized', function(e) {
                e.preventDefault();

                let product_id = $(this).data("product_id");
                let this_index = $(this).data("index");

                for (let i = readyProductArray.length - 1; i >= 0; --i) {
                    if (readyProductArray[i].index == this_index) {
                        readyProductArray.splice(i, 1);
                    }
                }


                //ready product total
                let total_amount = 0;
                readyProductArray.forEach(function(value, index, arry) {
                    total_amount += value.amount;
                });
                //ready product total

                $('#sub_total').val(total_amount);
                let sub_total = $('#sub_total').val();
                $('.sub_total_text').html('&#8377; ' + sub_total);


                let discount_percent = $('#discount_percent').val();
                //let discount_value = ((sub_total * discount_percent) / 100);
                let discount_value = discount_percent;
                if (discount_value === '') {
                    discount_value = 0;
                }
                $('#discount_value').val(discount_value);


                let freight_charge = $('#freight_charge').val();
                if (freight_charge == '') {
                    freight_charge = 0;
                }
                let misc_charge = $('#misc_charge').val();
                if (misc_charge == '') {
                    misc_charge = 0;
                }
                let freight_misc_charge = parseFloat(freight_charge) + parseFloat(misc_charge);
                // let grand_total = (sub_total + freight_misc_charge) - discount_value;
                let grand_total = (parseFloat(sub_total) + freight_misc_charge) - parseFloat(
                    discount_value);
                $('#grand_total').val(grand_total.toFixed(2));


                let paid_in_cash = $('#paid_in_cash').val();
                if (paid_in_cash == '') {
                    paid_in_cash = 0;
                }
                let paid_in_bank = $('#paid_in_bank').val();
                if (paid_in_bank == '') {
                    paid_in_bank = 0;
                }
                let cash_plus_bank = parseFloat(paid_in_cash) + parseFloat(paid_in_bank);
                $('#total_paid').val(Math.round(cash_plus_bank));

                let dues_amount = parseFloat(grand_total) - parseFloat(cash_plus_bank);
                $('#dues_amount').val(Math.round(dues_amount));

                $('#acc' + this_index).remove();
                sl_no_counter_customized = sl_no--;

                toastr["warning"]("Ready Product removed successfully", "Notification")
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            });
            //remove ready product



            //payment_type****************************************************************************
            let payment_type_current_selected = $('input[name="payment_type"]:checked').val();
            if (payment_type_current_selected === 'CASH WITH NO DUES') {
                $('.createProductCustomized').prop("disabled", true);
                $('.createProduct').prop("disabled", false);


                $('.otm_product_human').hide();
                $('.ready_product_human').show();



            }
            if (payment_type_current_selected === 'CREDIT WITH DUES') {
                $('.createProductCustomized').prop("disabled", true);
                $('.createProduct').prop("disabled", false);

                $('.otm_product_human').hide();
                $('.ready_product_human').show();

            }
            if (payment_type_current_selected === 'ORDER TO MAKE WITH CREDIT') {
                $('.createProductCustomized').prop("disabled", false);
                $('.createProduct').prop("disabled", true);

                $('.otm_product_human').show();
                $('.ready_product_human').hide();

            }

            function setPaymentTypeInSession(payment_type) {
                $.ajax({
                        type: 'POST',
                        url: "{{ route('branch.estimateList.setPaymentTypeInSession') }}",
                        data: {
                            payment_type: payment_type
                        },
                        success: function(data) {
                            if (data.success) {
                                location.reload();
                            }
                        }
                    })
                    .done(function(data) {
                        console.log(data);
                    })
                    .fail(function() {
                        console.log("error");
                    });
            }

            $('input[type=radio][name=payment_type]').change(function() {
                if (this.value === 'CASH WITH NO DUES') {
                    $('.createProductCustomized').prop("disabled", true);
                    $('.createProduct').prop("disabled", false);
                    setPaymentTypeInSession('CASH WITH NO DUES');

                    $('.otm_product_human').hide();
                    $('.ready_product_human').show();


                } else if (this.value === 'CREDIT WITH DUES') {
                    $('.createProductCustomized').prop("disabled", true);
                    $('.createProduct').prop("disabled", false);
                    setPaymentTypeInSession('CREDIT WITH DUES');

                    $('.otm_product_human').hide();
                    $('.ready_product_human').show();

                } else if (this.value === 'ORDER TO MAKE WITH CREDIT') {
                    $('.createProductCustomized').prop("disabled", false);
                    $('.createProduct').prop("disabled", true);
                    setPaymentTypeInSession('ORDER TO MAKE WITH CREDIT');

                    $('.otm_product_human').show();
                    $('.ready_product_human').hide();

                }
            });
            //payment_type****************************************************************************


            //form submit
            $(document).on('submit', '#submit_form', function(e) {
                e.preventDefault();


                //payment_type****************************************************************************
                let payment_type_current_selected = $('input[name="payment_type"]:checked').val();
                if (payment_type_current_selected === 'CASH WITH NO DUES') {
                    let dues_amount = $('#dues_amount').val();
                    dues_amount = parseInt(dues_amount);
                    if (dues_amount > 0) {
                        alertErrorMessage(
                            "CASH WITH ESTIMATE SELECTED, DUES MUST BE ZERO IN THIS SELECTION")
                        return false;
                    }
                }
                //payment_type****************************************************************************


                let from_branch_id = $('#from_branch_id').val();
                let client_name = $('#client_name').val();
                let client_email = $('#client_email').val();
                let client_mobile = $('#client_mobile').val();
                let client_address = $('#client_address').val();
                let estimate_no = $('#estimate_no').val();
                let estimate_date = $('#estimate_date').val();
                let expected_delivery_date = $('#expected_delivery_date').val();
                let sub_total = $('#sub_total').val();
                let discount_percent = $('#discount_percent').val();
                let discount_value = $('#discount_value').val();
                let freight_charge = $('#freight_charge').val();
                let misc_charge = $('#misc_charge').val();
                let grand_total = $('#grand_total').val();
                let dues_amount = $('#dues_amount').val();
                let paid_in_cash = $('#paid_in_cash').val();
                let paid_in_bank = $('#paid_in_bank').val();
                let total_paid = $('#total_paid').val();
                let remarks = $('#remarks').val();
                let sale_by = $('#sale_by').val();

                if (from_branch_id === '') {
                    alertErrorMessage("{{ __('from_branch_id cannot be empty') }}.")
                    return false;
                } else if (client_name === '') {
                    alertErrorMessage("{{ __('client name cannot be empty') }}.")
                    return false;
                } else if (client_mobile === '') {
                    alertErrorMessage("{{ __('client mobile cannot be empty') }}.")
                    return false;
                } else if (client_address === '') {
                    alertErrorMessage("{{ __('client address cannot be empty') }}.")
                    return false;
                } else if (estimate_no === '') {
                    alertErrorMessage("{{ __('estimate no cannot be empty') }}.")
                    return false;
                } else if (estimate_date === '') {
                    alertErrorMessage("{{ __('estimate date cannot be empty') }}.")
                    return false;
                } else if (expected_delivery_date === '') {
                    alertErrorMessage("{{ __('expected delivery date cannot be empty') }}.")
                    return false;
                } else if (sale_by === '') {
                    alertErrorMessage("{{ __('sale by cannot be empty') }}.")
                    return false;
                } else if (Object.entries(readyProductArray).length === 0) {
                    alertErrorMessage("{{ __('products list cannot be empty') }}.")
                    return false;
                } else {


                    let productFormObject = {
                        from_branch_id: from_branch_id,
                        client_name: client_name,
                        client_email: client_email,
                        client_mobile: client_mobile,
                        client_address: client_address,
                        estimate_no: estimate_no,
                        estimate_date: estimate_date,
                        expected_delivery_date: expected_delivery_date,
                        sub_total: sub_total,
                        discount_percent: discount_percent,
                        discount_value: discount_value,
                        freight_charge: freight_charge,
                        misc_charge: misc_charge,
                        grand_total: grand_total,
                        dues_amount: dues_amount,
                        paid_in_cash: paid_in_cash,
                        paid_in_bank: paid_in_bank,
                        total_paid: total_paid,
                        remarks: remarks,
                        sale_by: sale_by,
                        products: readyProductArray
                    };
                    let jsonArrayObject = JSON.stringify(productFormObject);
                    $.ajax({
                        type: "POST",
                        url: "{{ route('estimate-list.store') }}",
                        data: jsonArrayObject,
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            if (data.success) {

                                let estimate_id = data.estimate.id;
                                let url =
                                    "{{ route('branch.estimateList.estimatePrint', ':estimate_id') }}";
                                url = url.replace(':estimate_id', estimate_id);

                                //page redirect to print page
                                $.confirm({
                                    theme: 'modern',
                                    title: 'Hello!',
                                    icon: 'fa fa-question-circle',
                                    content: 'Click on print now button to print estimate!',
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        heyThere: {
                                            text: '{{ __('Print Now!') }}',
                                            action: function() {
                                                window.open(url, '_blank');

                                                $('.ajax_fetch_ready_product').html(
                                                    '');
                                                $('.ajax_fetch_customized_product')
                                                    .html('');
                                                initial_index_start_value = 0;
                                                initial_index_start_value_counter =
                                                    0;
                                                readyProductArray = [];
                                                window.setTimeout(function() {
                                                    location.reload();
                                                }, 1000)
                                            }
                                        },
                                        Close: {
                                            btnClass: 'btn-red',
                                            text: 'Close!',
                                            action: function() {
                                                location.reload();
                                            }
                                        }
                                    }
                                });
                                //page redirect to print page

                                //alertSuccessMessage(data.success);


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
                        },
                        error: function(data) {
                            alertErrorMessage("Something Went Wrong Dev")
                            console.log('error ' + data);
                        }
                    });
                }
            });
            //from submit


            $("#estimate_date").datepicker().datepicker("setDate", new Date());
            $("#expected_delivery_date").datepicker().datepicker("setDate", new Date());

            //get client details
            $(document).on('change', '#client_mobile', function(e) {
                e.preventDefault();

                let client_mobile = $(this).val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('branch.estimateList.getClientDetails') }}",
                    data: {
                        client_mobile: client_mobile
                    },
                    success: function(data) {
                        // console.log(data);
                        if (data.success) {
                            $('#client_name').val(data.success.client_name);
                            $('#client_email').val(data.success.client_email);
                            $('#client_address').val(data.success.client_address);

                            //console.log(data);
                        } else if (data.errors_success == 'errors_success') {
                            $('#client_name').val('');
                            $('#client_email').val('');
                            $('#client_address').val('');
                            //console.log("hii");
                        } else {
                            console.log('went wrong');
                        }
                    },
                    error: function(data) {
                        console.log('error ' + data);
                    }
                });

            });
            //get client details


            $('#submit_form').on('keyup keypress', function(e) {
                let keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });


            //****************************************************************************************
        });
    </script>
@endsection
