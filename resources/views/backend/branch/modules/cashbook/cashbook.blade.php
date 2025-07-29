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
            <!--<a class="btn ripple btn-warning" href="javascript:void(0)" data-target="#modaldemo3" data-toggle="modal"><i-->
            <!--        class="fe fe-plus"></i> Manage Opening and Closing Balance</a>-->
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

    <!-- Cashbook modal -->
    <div class="modal" id="modaldemo3">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Manage Opening and Closing</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p class="mb-2">Select From Date</p>
                        <input type="date" class="form-control" name="from_date" id="from_date" placeholder="Date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="button"
                        onclick="manageOpeningClosing()">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Cashbook modal -->

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

    function manageOpeningClosing()
    {
        let from_date = $('#from_date').val();

        $.ajax({
                url: "{{route('branch.cashbook.manageOpeningClosing')}}",
                method:"POST",
                data:{from_date:from_date},
                cache: false,
                dataType: "json",
                success:function(data)
                {
                   if (data.success) {
                        toastr.success(data.success, 'SUCCESS')
                        window.setTimeout(function() {
                            window.location.href = '{{route('branch.cashbook.cashbookList')}}';
                        }, 2500);
                    } 
                    else if (data.errors_success) 
                    {
                        toastr.warning(data.errors_success, 'WARNING')
                    } 
                    else if (data.errors_validation) 
                    {
                        let html = '';
                        for (let count = 0; count < data.errors_validation
                            .length; count++) {
                            html += '<p>' + data.errors_validation[count] + '</p>';
                        }
                        toastr.warning(html, 'WARNING')
                    }
                    else 
                    {
                        toastr.danger('Something Went Wrong', 'ERROR')
                    }
                }
            })
    }

</script>
@endsection