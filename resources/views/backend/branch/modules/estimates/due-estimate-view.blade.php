@extends('backend.branch.layouts.master')
@section('title')
    View Due Estimate
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">View Due Estimate</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Due Estimate</li>
                </ol>
            </div>

            <div class="btn btn-list">
                <a class="btn ripple btn-primary" href="{{ route('dues-estimate-list.index') }}"><i
                        class="fe fe-external-link"></i> Go Back</a>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Row -->
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
                            <h6 class="card-title mb-3 text-primary">PRODUCT ITEMS</h6>
                            <div class="table-responsive">
                                <table class="table  table-bordered scrolldown">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Type</th>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Color</th>
                                            <th>Size</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($estimate->EstimateProductLists))
                                            @php $count1 = 1; @endphp
                                            @foreach ($estimate->EstimateProductLists as $EstimateProductList)
                                                <tr>
                                                    <td>{{ $count1++ }}</td>
                                                    <td>{{ $EstimateProductList->product_type }}</td>
                                                    <td>{{ $EstimateProductList->product_name }}</td>
                                                    <td>{{ $EstimateProductList->product_code }}</td>
                                                    <td>{{ $EstimateProductList->color }}</td>
                                                    <td>{{ $EstimateProductList->size }}</td>
                                                    <td>{{ $EstimateProductList->qty }}</td>
                                                    <td>{{ $EstimateProductList->mrp }}</td>
                                                    <td>{{ $EstimateProductList->amount }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
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
                                        </div><!-- invoice-notes -->
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

        });
    </script>
@endsection
