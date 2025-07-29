@extends('backend.branch.layouts.master')
@section('title')
Cash Book
@endsection
@section('extra-css')
@endsection
@section('content')
<style>
    .table thead th {
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Cashbook</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Cashbook</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cashbook</li>
            </ol>
        </div>
        <div class="btn btn-list">
            <a class="btn ripple btn-primary" href="{{ route('branch.cashbook.createCashbookList') }}"><i
                    class="fe fe-plus"></i>
                Add</a>
        </div>
    </div>
    <!-- End Page Header -->
    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>Cash Book ID</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cashbook_data as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->total_amount}}</td>
                                    <td>{{$item->status}}</td>
                                    <td>{{$item->remark}}</td>
                                </tr>
                                @endforeach
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
    $('#example2').DataTable({
		responsive: true,
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_ items/page',
		}
	});


</script>
@endsection