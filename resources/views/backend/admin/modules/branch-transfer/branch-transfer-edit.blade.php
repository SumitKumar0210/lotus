@extends('backend.admin.layouts.master')
@section('title')
    Branch Transfer Edit
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Branch Transfer Edit</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Branch Transfer</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Branch Transfer Edit</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="{{ route('in-transit.index') }}"><i
                        class="fe fe-external-link"></i> GO BACK</a>
            </div>
        </div>
        <!-- End Page Header -->


        <!-- Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card overflow-hidden">

                    <form action="{{ route('branch-transfer-admin.update', $stock->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">


                            @include('backend.admin.messages.message-jquery-confirm')
                            <div class="row">
                                <div class="col-6">
                                    <h4>From : {{ $stock->fromTwo->branch_name }}</h4>
                                </div>
                                <div class="col-6 float-right pull-right text-right">
                                    <h4>To: {{ $stock->branchTo->branch_name }}</h4>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>PRODUCT NAME</th>
                                            <th>MODEL NO.</th>
                                            <th>CATEGORY</th>
                                            <th>QTY SENT</th>
                                            <th>DATE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($stock))
                                            <tr>
                                                <td>{{ $stock->product->product_name }}</td>
                                                <td>{{ $stock->product->product_code }}</td>
                                                <td>{{ $stock->product->category->category_name }}</td>
                                                <td>
                                                    <input type="number" id="qty" name="qty" class="form-control"
                                                        style="width:40%" minlength="1" min="1" value="{{ $stock->qty }}"
                                                        required>
                                                </td>
                                                <td>{{ $stock->created_at }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <input type="hidden" name="product_id" value="{{ $stock->product_id }}">
                            <input type="hidden" name="branch_out" value="{{ $stock->branch_out }}">
                            <button class="btn ripple btn-primary " type="submit">Submit</button>
                            <button class="btn ripple btn-secondary" type="button">Close</button>
                        </div>
                    </form>


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
