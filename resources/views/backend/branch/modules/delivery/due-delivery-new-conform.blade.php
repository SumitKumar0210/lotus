@extends('backend.branch.layouts.master')
@section('title')
    Deliver & print Challan
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Deliver & Print Challan</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Deliver & print Challan</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary" href="{{ route('dues-delivery-list-new.index') }}"><i
                        class="fe fe-external-link"></i> Go Back</a>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Row -->
        @include('backend.admin.messages.message-jquery-confirm')
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <p class="h6">CUSTOMER DETAILS:</p>
                                <hr>
                                <address>
                                    Name: {{ $estimate->client_name }}<br>
                                    Mobile: {{ $estimate->client_mobile }}<br>
                                    Address: {{ $estimate->client_email }}<br>
                                    Email: {{ $estimate->client_address }}<br>
                                </address>
                            </div>
                            <div class="col-lg-6 text-right">
                                <p class="h6">ESTIMATE DETAILS:</p>
                                <hr>
                                <address>
                                    Estimate No: {{ $estimate->estimate_no }}<br>
                                    Estimate Date: {{ $estimate->estimate_date }}<br>
                                    Excepted Delivery Date: {{ $estimate->expected_delivery_date }}<br>
                                </address>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">


                            <form action="{{ route('branch.duesDeliveryNewList.postDuesDeliveryListNew') }}"
                                enctype="multipart/form-data" method="POST" target="_blank" id="master_form">
                                @csrf
                                <input type="hidden" name="estimate_id" value="{{ $estimate->id }}">

                                <h6 class="card-title mb-3 text-primary">PRODUCT LISTS</h6>
                                <hr />
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>DELIVERED</th>
                                            <th>DELIVERING</th>
                                            <th>TYPE</th>
                                            <th>NAME</th>
                                            <th>MODEL</th>
                                            <th>COLOR</th>
                                            <th>SIZE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($estimate->EstimateProductLists))
                                            @php
                                                $counter = 1;
                                            @endphp
                                            @foreach ($estimate->EstimateProductLists as $key => $product)
                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>
                                                        @php
                                                            $estimate_product_delivery_status_product = App\Models\EstimateProductDeliveryStatus::where('estimate_product_list_id', $product->id)->get();
                                                            $deliverd_qty_product = $estimate_product_delivery_status_product->sum('qty');
                                                            $undeliverd_qty_product = $product->qty - $deliverd_qty_product;
                                                        @endphp
                                                        {{ $deliverd_qty_product }}
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" max="{{ $undeliverd_qty_product }}" id="qty_to_mark_delivers{{$key}}"
                                                            name="qty_to_mark_delivers[]"
                                                            value="{{ $undeliverd_qty_product }}"
                                                            {{ $undeliverd_qty_product == 0 ? 'disabled' : '' }}>

                                                        <input type="hidden" name="estimate_product_list_ids[]" id="estimate_product_list_ids{{$key}}"
                                                            value="{{ $product->id }}"
                                                            {{ $undeliverd_qty_product == 0 ? 'disabled' : '' }}>

                                                        <input type="hidden" name="product_ids[]" id="product_ids{{$key}}"
                                                            value="{{ $product->product_id }}"
                                                            {{ $undeliverd_qty_product == 0 ? 'disabled' : '' }}>

                                                    </td>
                                                    <td>{{ $product->product_type }}</td>
                                                    <td>{{ $product->product_name }}</td>
                                                    <td>{{ $product->product_code }}</td>
                                                    <td>{{ $product->color }}</td>
                                                    <td>{{ $product->size }}</td>
                                                </tr>



                                                <script>
                                                        $(document).ready(function() {


                                    
                                                            $("#qty_to_mark_delivers{{$key}}").on('change', function(e) {
                                                                e.preventDefault();
                                                                let value  = $(this).val();
                                                                if(value == 0){
                                                                    $("#qty_to_mark_delivers{{$key}}").attr('disabled',true);
                                                                    $("#estimate_product_list_ids{{$key}}").attr('disabled',true);
                                                                    $("#product_ids{{$key}}").attr('disabled',true);
                                                                }
                                                            })
                                                        });
                                                </script>




                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                <hr />
                                <div class="text-right">
                                    <button class="btn ripple btn-primary form_submit_button" type="submit">Submit</button>

                                </div>
                            </form>


                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-primary">REMARKS</h6>
                            <p>{{ $estimate->remarks }}</p>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-primary">PAYMENTS</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered scrolldown">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Paid In Cash</th>
                                            <th>Paid In Bank</th>
                                            <th>Total Paid</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($estimate->EstimatePaymentLists))
                                            @php $count2 = 1; @endphp
                                            @foreach ($estimate->EstimatePaymentLists as $EstimatePaymentList)
                                                <tr>
                                                    <td>{{ $count2++ }}</td>
                                                    <td>{{ $EstimatePaymentList->paid_in_cash }}</td>
                                                    <td>{{ $EstimatePaymentList->paid_in_bank }}</td>
                                                    <td>{{ $EstimatePaymentList->total_paid }}</td>
                                                    <td>{{ $EstimatePaymentList->date_time }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <div class="table-responsive">
                        <table class="table table-invoice table-bordered">
                            <thead>
                            <tbody>
                                <tr>
                                    <td class="valign-middle" colspan="2" rowspan="9">
                                        <div class="invoice-notes">
                                            <label class="main-content-label tx-10">Notes</label>
                                            <p class="tx-9-f">GST, Packing and Cartage will be extra as applicable</p>
                                        </div>
                                    </td>
                                    <td class="tx-right tx-11">Sub Total</td>
                                    <td class="tx-right tx-12" colspan="2">&#8377; {{ $estimate->sub_total }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-11">Discount</td>
                                    <td class="tx-right tx-12" colspan="2">&#8377; {{ $estimate->discount_value }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-11">Freight</td>
                                    <td class="tx-right tx-12" colspan="2">&#8377; {{ $estimate->freight_charge }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-11">Misc</td>
                                    <td class="tx-right tx-12" colspan="2">&#8377; {{ $estimate->misc_charge }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-11">Grand Total</td>
                                    <td class="tx-right tx-12" colspan="2">&#8377; {{ $estimate->grand_total }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-11">Paid In Cash</td>
                                    <td class="tx-right tx-12" colspan="2">&#8377; {{ $paid_in_cash }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-11">Paid In Bank</td>
                                    <td class="tx-right tx-12" colspan="2">&#8377; {{ $paid_in_bank }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-uppercase tx-bold tx-inverse">TOTAL PAID</td>
                                    <td class="tx-right tx-11" colspan="2">
                                        <h4 class="tx-bold tx-12">&#8377; {{ $total_paid }}</h4>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="tx-right tx-11">Due</td>
                                    <td class="tx-right tx-12" colspan="2">&#8377; {{ $estimate->dues_amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!-- End Row -->
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


            $(document).on('click', '.form_submit_button', function(e) {
                e.preventDefault();


                $.confirm({
                    title: "Hello!",
                    content: "Are you sure want to mark deliver selected items?",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        somethingElse: {
                            text: 'Submit',
                            btnClass: 'btn-blue',
                            keys: ['enter', 'shift'],
                            action: function() {
                                $('#master_form').submit();
                                $.confirm({
                                    title: 'Close',
                                    content: 'Close this popup',
                                    buttons: {
                                        somethingElse: {
                                            text: 'Close',
                                            btnClass: 'btn-blue',
                                            keys: ['enter', 'shift'],
                                            action: function() {
                                                location.reload();
                                            }
                                        }
                                    }
                                });
                            }
                        },
                        cancel: function() {

                        },
                    }
                });


            })
        });
    </script>
@endsection
