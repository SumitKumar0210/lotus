@extends('backend.admin.layouts.master')
@section('title')
    Purchase Edit
@endsection
@section('extra-css')
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2 class="main-content-title tx-24 mg-b-5">Purchase Edit</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Purchase</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Purchase Edit</li>
                </ol>
            </div>
            <div class="btn btn-list">
                <a class="btn ripple btn-primary exportToExcel" href="{{ route('purchase.index') }}"><i
                        class="fe fe-external-link"></i> Go Back</a>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card overflow-hidden">
                    <div class="card-body">
                        @include('backend.admin.messages.message-jquery-confirm')
                        <form action="{{ route('purchase.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>

                                        <tr>
                                            <th>Brand Name</th>
                                            <th>Category</th>
                                            <th>Product Name</th>
                                            <th>Model no</th>
                                            <th>Color</th>
                                            <th>Qty</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $stock->product->brand->brand_name }}</td>
                                            <td>{{ $stock->product->category->category_name }}</td>
                                            <td>{{ $stock->product->product_name }}</td>
                                            <td>{{ $stock->product->product_code }}</td>
                                            <td>{{ $stock->product->color_code }}</td>
                                            <td>
                                                <input type="number" name="qty" value="{{ $stock->qty }}" min="1"
                                                    minlength="1" class="form-control">
                                            </td>
                                            <td>{{ $stock->created_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button class="btn ripple btn-primary" type="submit">Update</button>

                        </form>


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
