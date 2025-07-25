@extends('backend.admin.layouts.master')
@section('title')
    View Due Delivery Estimate
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">View Due Delivery Estimate</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Estimate</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Due Delivery Estimate</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary" href="{{ route('dues-delivery-list.index') }}"><i
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
                                    Name: {{ $estimate->estimate->client_name }}<br>
                                    Mobile: {{ $estimate->estimate->client_mobile }}<br>
                                    Address: {{ $estimate->estimate->client_email }}<br>
                                    Email: {{ $estimate->estimate->client_address }}<br>
                                </address>
                            </div>
                            <div class="col-lg-6 text-right">
                                <p class="h6">ESTIMATE DETAILS:</p>
                                <hr>
                                <address>
                                    Estimate No: {{ $estimate->estimate->estimate_no }}<br>
                                    Estimate Date: {{ $estimate->estimate->estimate_date }}<br>
                                    Excepted Delivery Date: {{ $estimate->estimate->expected_delivery_date }}<br>
                                </address>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-3 text-primary">PRODUCT ITEM</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered scrolldown">
                                    <thead>
                                        <tr>
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
                                        @if (!empty($estimate))
                                            <tr>
                                                <td>{{ $estimate->product_type }}</td>
                                                <td>{{ $estimate->product_name }}</td>
                                                <td>{{ $estimate->product_code }}</td>
                                                <td>{{ $estimate->color }}</td>
                                                <td>{{ $estimate->size }}</td>
                                                <td>{{ $estimate->qty }}</td>
                                                <td>{{ $estimate->mrp }}</td>
                                                <td>{{ $estimate->amount }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
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
                                        @if (!empty($estimate->estimate->EstimatePaymentLists))
                                            @php $count2 = 1; @endphp
                                            @foreach ($estimate->estimate->EstimatePaymentLists as $EstimatePaymentList)
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
