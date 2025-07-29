@extends('backend.admin.layouts.master')
@section('title')
Opening Closing
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
            <h2 class="main-content-title tx-24 mg-b-5">Report: {{date('d-m-Y',strtotime($current_date))}}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Opening & Closing</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report</li>
            </ol>
        </div>
        <div class="btn btn-list">
            <a class="btn ripple btn-primary" href="javascript:void(0)" data-target="#modaldemo1" data-toggle="modal"><i
                    class="fe fe-plus"></i>
                Branch</a>
            <a class="btn ripple btn-primary" href="javascript:void(0)" data-target="#modaldemo2" data-toggle="modal"><i
                    class="fe fe-plus"></i>
                ADD OPENING BALANCE</a>
            <a class="btn ripple btn-warning" href="javascript:void(0)" data-target="#modaldemo3" data-toggle="modal"><i
                    class="fe fe-plus"></i> Manage Opening and Closing Balance</a>
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
                                    <th>S.No</th>
                                    <th>Branch</th>
                                    <th>Opening Balance</th>
                                    <th>Closing Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no =1; @endphp
                                @foreach ($opening_closing_data as $item)
                                @if($item->branch_id != '0')
                                <tr>
                                    <td>{{$no++;}}</td>
                                    <td>
                                        {{$item->BranchDetail->branch_name}}
                                    </td>
                                    <td>{{$item->opening_balance}}</td>
                                    <td>{{$item->closing_balance}}</td>
                                </tr>
                                @endif
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
    <div class="modal" id="modaldemo1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Branch</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="mb-2">Branch Name</label>
                        <select name="branch" class="form-control select2 branch">
                            <option value="">Select Branch</option>
                            @foreach ($branchList as $item)

                            <option value="{{$item->id}}">{{$item->branch_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="button" onclick="getBranch()">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Cashbook modal -->
    <!-- Add Opening Balance modal -->
    <div class="modal" id="modaldemo2">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Opening Balance</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="mb-2">Branch Name</label>
                        <select name="branch" class="form-control select2 branch" id="branch">
                            <option value="">Select Branch</option>
                            @foreach ($branchList as $item)
                            @if($item->id != 1)
                            <option value="{{$item->id}}">{{$item->branch_name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="mb-2">Opening Balance</label>
                        <input type="number" class="form-control" id="opening_balance" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="button" onclick="addOpeningBalance()">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Opening Balance modal -->
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
                    {{-- <div class="form-group">
                        <p class="mb-2">Select type</p>
                        <select class="form-control" id="type">
                            <option value="Admin">Admin</option>
                            <option value="Branch">Branch</option>
                            <option value="All">All</option>
                        </select>
                    </div> --}}

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
    $(document).ready(function() {
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

    function getBranch()
    {
        let branch = $('.branch').val();
        let url = "{{route('admin.cashbook.cashbookBranch',':slip')}}";
        url = url.replace(':slip', branch);
        window.location.href = url;
    }

    //add opening balance of branch
    function addOpeningBalance()
    {
        let branch = $('#branch').val();
        let opening_balance = $('#opening_balance').val();

        $.confirm({
                    title: "{{ __('Hello!') }}",
                    content: "{{ __('Are You Sure to Update Opening Balance. Once You Update the Opening Balance. You cannot change it. ') }}",
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    theme: 'supervan',
                    buttons: {
                        ok: {
                            action: function() {

                                $.ajax({
                                    url: "{{route('admin.cashbook.updateOpeningBalance')}}",
                                    type: "POST",
                                    data: {branch:branch,opening_balance:opening_balance},
                                    cache: false,
                                    dataType: "json",
                                    success: function(data) {
                                        console.log(data);
                                        if (data.success) {
                                            toastr.success(data.success, 'SUCCESS')
                                            window.setTimeout(function() {
                                            window.location.href = '{{route('admin.cashbook.cashbookList')}}';
                                            }, 2500);
                                        } else if (data.errors_success) {
                                            toastr.warning(data.errors_success, 'WARNING')
                                        } else {
                                            toastr.error('Something Went Wrong', 'ERROR')
                                        }
                                    }
                                });
                            },
                            btnClass: 'btn-green'
                        },
                        cancel: function() {

                        },
                    }
                });
    }

    function manageOpeningClosing()
    {
        let from_date = $('#from_date').val();
        // let type = $('#type').val();

        $.ajax({
                url: "{{route('admin.cashbook.adminManageOpeningClosing')}}",
                method:"POST",
                data:{from_date:from_date},
                cache: false,
                dataType: "json",
                success:function(data)
                {
                   if (data.success) {
                        toastr.success(data.success, 'SUCCESS')
                        window.setTimeout(function() {
                            window.location.href = '{{route('admin.cashbook.cashbookList')}}';
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