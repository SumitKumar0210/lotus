@extends('backend.admin.layouts.master')
@section('title')
Branch Wise Report
@endsection
@section('extra-css')
@endsection
@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Branch Wise Report</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Cashbook</a></li>
                <li class="breadcrumb-item active" aria-current="page">Branch Wise Report</li>
            </ol>
        </div>
    </div>
    <!-- End Page Header -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="from">From</label>
                                <input class="form-control fc-datepicker pull-right" placeholder="MM/DD/YYYY"
                                    id="date_from" name="date_from" type="text">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="To">To</label>
                                <input class="form-control fc-datepicker pull-right" placeholder="MM/DD/YYYY"
                                    id="date_to" name="date_to" type="text">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mt-4 pt-1">
                                <button class="form-control btn btn-primary" id="btnFiterSubmitSearch"
                                    type="button">Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Total Cash</p>
                        <div class="ml-auto"> <i class="fas fa-chart-line fs-20 text-primary"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25">1428400</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Bank</p>
                        <div class="ml-auto"> <i class="fab fa-rev fs-20 text-secondary"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25">33686926</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Bank</p>
                        <div class="ml-auto"> <i class="fas fa-dollar-sign fs-20 text-success"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25">₹ 11675507 </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl col-lg">
            <div class="card custom-card">
                <div class="card-body dash1">
                    <div class="d-flex">
                        <p class="mb-1 tx-inverse">Bank</p>
                        <div class="ml-auto"> <i class="fas fa-signal fs-20 text-info"></i> </div>
                    </div>
                    <div>
                        <h3 class="dash-25">38262009</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card custom-card">
                <div class="card-body">
                    <h6 class="card-title">Table data</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Particular</th>
                                <th scope="col">Mode</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Opening Balance</td>
                                <td>Cash</td>
                                <td>5000</td>
                            </tr>
                            <tr>
                                <td>Opening Balance</td>
                                <td>Cash</td>
                                <td>5000</td>
                            </tr>
                            <tr>
                                <td>Opening Balance</td>
                                <td>Cash</td>
                                <td>5000</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right">Total</td>
                                <td>5000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card custom-card">
                <div class="card-body">
                    <h6 class="card-title">Table data2</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Reason</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Opening Balance</td>
                                <td>5000</td>
                            </tr>
                            <tr>
                                <td>Opening Balance</td>
                                <td>5000</td>
                            </tr>
                            <tr>
                                <td>Opening Balance</td>
                                <td>5000</td>
                            </tr>
                            <tr>
                                <td class="text-right">Total</td>
                                <td>5000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->

</div>
@endsection