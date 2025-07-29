@extends('backend.branch.layouts.master')
@section('title')
Receive Cash
@endsection
@section('extra-css')
<style>
    .table thead th {
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Receive Cash</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Cashbook</a></li>
                <li class="breadcrumb-item active" aria-current="page">Receive Cash</li>
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
                        <table id="example2" class="table table-striped table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Sl N0.</th>
                                    <th>Date</th>
                                    <th>Branch</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=1; @endphp
                                @foreach ($cashbooks as $item)
                                <tr>
                                    <td>{{$no++;}}</td>
                                    <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                                    <td>{{$item->BranchDetail->branch_name}}</td>
                                    <td>{{$item->total_amount}}</td>
                                    <td>{{$item->status}}</td>
                                    <td>
                                        <nav class="nav">
                                            <div class="dropdown-menu dropdown-menu-right shadow">
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    data-target="#modaldemo3" data-toggle="modal"
                                                    onclick="receivedData({{$item->id}})"><i
                                                        class="fe fe-eye text-info"></i>
                                                    View</a>
                                                @if($item->status == 'Pending')
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    data-target="#modaldemo2" data-toggle="modal"
                                                    onclick="receivedData({{$item->id}})"><i
                                                        class="fe fe-check text-primary"></i>
                                                    Approve</a>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    data-target="#modaldemo1" data-toggle="modal"
                                                    onclick="receivedData({{$item->id}})"><i
                                                        class="fe fe-x text-danger"></i> Decline </a>
                                                @endif
                                            </div>
                                            <button class="btn ripple btn-outline-primary btn-rounded "
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i
                                                    class="fe fe-more-vertical"></i></button>
                                        </nav>
                                    </td>
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
    <!--Start Approve modal -->
    <div class="modal" id="modaldemo2">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Approve Cashbook</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <h5 class="card-title mb-4">Preview Note Denomination</h5>
                    <table class="table" style="border: 1px solid #e1e6f1;">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">Currency</th>
                                <th colspan="2" class="text-center">No.Of Notes</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="data-receiveCash"></tbody>
                    </table>
                    <input type="hidden" class="cashbook_id" />
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="button" onclick="approveCash()">Approve</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Approve modal -->
    <!-- Decline modal -->
    <div class="modal" id="modaldemo1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Decline Reason</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <table class="table" style="border: 1px solid #e1e6f1;">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">Currency</th>
                                <th colspan="2" class="text-center">No.Of Notes</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="data-receiveCash"></tbody>
                    </table>
                    <div class="form-group">
                        <input type="hidden" class="cashbook_id" />
                        <p class="mb-2">Enter Remarks</p>
                        <textarea class="form-control remark" name="example-text-input" placeholder="Remark"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="button" onclick="declineReceiveCash();">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Decline modal -->
    <!-- View modal -->
    <div class="modal" id="modaldemo3">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">View</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <table class="table" style="border: 1px solid #e1e6f1;">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">Currency</th>
                                <th colspan="2" class="text-center">No.Of Notes</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="data-receiveCash"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End View modal -->
</div>
@endsection
@section('extra-js')
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $('#example2').DataTable({
		responsive: true,
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_ items/page',
		}
	});

    function receivedData(id)
    {
        let url = "{{route('branch.cashbookHO.receiveCashData',':slip')}}";
        url = url.replace(':slip', id);

        $.ajax({
            url: url,
            method:"GET",
            data:{id:id},
            cache: false,
            dataType: "json",
            success:function(data)
            {
                $('.data-receiveCash').html(data);
                $('.cashbook_id').val(id);
            } 
        });
    }

    //approve Cash
    function approveCash()
    {
        let id = $('.cashbook_id').val();
        $.ajax({
            url: "{{ route('branch.cashbookHO.approveReceiveCash') }}",
            method:"POST",
            data:{id:id},
            cache: false,
            dataType: "json",
            success:function(data)
            {
                if (data.success) {
                    toastr.success(data.success, 'SUCCESS')
                    $('#modaldemo2').modal('hide');
                } else if (data.errors_success) {
                    toastr.warning(data.errors_success, 'WARNING')
                }
                else { 
                    toastr.danger('Something Went Wrong', 'ERROR')
                }
            }
        });

    }
    
    //decline cash
    function declineReceiveCash()
    {
        let id = $('.cashbook_id').val();
        let remark = $('.remark').val();
        $.ajax({
            url: "{{ route('branch.cashbookHO.declineReceiveCash') }}",
            method:"POST",
            data:{id:id,remark:remark},
            cache: false,
            dataType: "json",
            success:function(data)
            {
                if (data.success) {
                    toastr.success(data.success, 'SUCCESS')
                    $('#modaldemo1').modal('hide');
                } else if (data.errors_success) {
                    toastr.warning(data.errors_success, 'WARNING')
                }
                else {
                    toastr.danger('Something Went Wrong', 'ERROR')
                }
            }
        });
    }
</script>
@endsection