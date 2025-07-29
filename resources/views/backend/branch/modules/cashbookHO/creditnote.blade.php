@extends('backend.branch.layouts.master')
@section('title')
Credit Note
@endsection
@section('extra-css')
@endsection
@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Credit Note</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Cashbook</a></li>
                <li class="breadcrumb-item active" aria-current="page">Credit Note</li>
            </ol>
        </div>
    </div>
    <!-- End Page Header -->
    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example3" class="table table-striped table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Sl N0.</th>
                                    <th>Date</th>
                                    <th>Branch</th>
                                    <th>Credit ID</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1; @endphp
                                @foreach ($credit_detail as $item)
                                <tr>
                                    <td>{{$no++;}}</td>
                                    <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                                    <td>{{$item->BranchDetail->branch_name}}</td>
                                    <td>{{$item->credit_id}}#0{{$item->id}}</td>
                                    <td>{{$item->amount}}</td>
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
    $('#example3').DataTable( {
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return 'Details for '+data[0]+' '+data[1];
                    }
                } ),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                    tableClass: 'table'
                } )
            }
        }
    } );
</script>
@endsection