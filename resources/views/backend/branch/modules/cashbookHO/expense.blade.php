@extends('backend.branch.layouts.master')
@section('title')
Expense
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
            <h2 class="main-content-title tx-24 mg-b-5">Expense</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Cashbook</a></li>
                <li class="breadcrumb-item active" aria-current="page">Expense</li>
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
                        <table id="example2" class="table table-striped table-bordered text-nowrap">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Branch</th>
                                    <th>Amount</th>
                                    <th>Reason</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenses as $item)
                                <tr>
                                    <td>{{date('d-m-Y',strtotime($item->datetime))}}</td>
                                    <td>{{$item->BranchDetail->branch_name ?? 'Admin'}}</td>
                                    <td>{{$item->amount}}</td>
                                    <td>{{$item->remark}}</td>
                                    <td>{{$item->amount}}</td>
                                    <td>
                                        <nav class="nav">
                                            <div class="dropdown-menu dropdown-menu-right shadow">

                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    data-target="#modaldemo2" data-toggle="modal"
                                                    onclick="getData({{$item->id}})"><i
                                                        class="fe fe-edit text-info"></i>
                                                    Edit</a>

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
    <!-- Expense modal -->
    @php
    $current_date = date('Y-m-d');
    @endphp
    <!-- End Expense modal -->
    <!-- Edit Expense modal -->
    <div class="modal" id="modaldemo2">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Edit Expense</h6><button aria-label="Close" class="close"
                        data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p class="mb-2">Date</p>
                        <input type="hidden" class="form-control" name="id">
                        <input type="date" class="form-control" name="edit_date" placeholder="Date" readonly>
                    </div>
                    <div class="form-group">
                        <p class="mb-2">Amount</p>
                        <input type="number" class="form-control" name="edit_amount" placeholder="Amount">
                    </div>
                    <div class="form-group">
                        <p class="mb-2">Reason</p>
                        <textarea class="form-control edit_reason" name="reason" placeholder="Reason"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="mb-2">Type</label>
                        <select name="mode" class="form-control select2 edit_mode"
                            onchange="editPaymentMode(this.value)">
                            <option value="NEFT">NEFT</option>
                            <option value="CHEQUE">CHEQUE</option>
                            <option value="PAYTM">PAYTM</option>
                            <option value="GOOGLE PAY">GOOGLE PAY</option>
                            <option value="CASH">CASH</option>
                        </select>
                    </div>
                    <div class="form-group edit-transaction-detail" style="display: none;">
                        <p class="mb-2">Transaction ID</p>
                        <input type="text" class="form-control transaction_id" name="edit_transaction_id"
                            placeholder="Transaction Number">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" type="button" onclick="updateExpenses()">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Expense modal -->
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
		
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
			lengthMenu: '_MENU_ items/page',
		}
	});

    function paymentMode(x)
    {
        if(x == 'CASH' || x == '')
        {
            $('.transaction-detail').css('display','none');
        } else{
            $('.transaction-detail').css('display','block');
        }
    }

// chk edit mode
    function editPaymentMode(x)
    {
        if(x == 'CASH')
        {
            $('.edit-transaction-detail').css('display','none');
        }else{
            $('.edit-transaction-detail').css('display','block');
        }
    }

    //get expenses
    function getData(id)
    {
        let url = "{{route('branch.cashbookHO.editExpense',':slip')}}";
        url = url.replace(':slip', id);

        $.ajax({
            url: url,
            method:"GET",
            data:{id:id},
            cache: false,
            dataType: "json",
            success:function(data)
            {
                $("input[name='id']").val(data.id);
                $("input[name='edit_amount']").val(data.amount);
                $("input[name='edit_date']").val(data.datetime);
                $(".edit_reason").val(data.remark);
                $(".edit_mode option").each(function()
                {
                    if($(this).val() == data.mode)
                    {
                        $(this).attr("selected","selected");
                    }
                });
                if(data.transaction_id != null)
                {
                    $('.edit-transaction-detail').css('display','block');
                } else {
                    $('.edit-transaction-detail').css('display','none');
                }
                $(".transaction_id").val(data.transaction_id);
            } 
        });
    }
    

    //update expenses
    function updateExpenses()
    {
        let id = $("input[name='id']").val();
        let date = $("input[name='edit_date']").val();
        let amount = $("input[name='edit_amount']").val();
        let reason = $(".edit_reason").val();
        let mode = $(".edit_mode").val();
        let transaction_id = $("input[name='edit_transaction_id']").val();
        if(amount < 1)
        {
            toastr.warning('Enter Amount', 'WARNING');
        }
        else if(reason == '')
        {
            toastr.warning('Reason is Required', 'WARNING');
        }
        else if(mode != 'CASH' && transaction_id == '')
        {
            toastr.warning('Transaction Number is Required', 'WARNING');
        }
        else{

           $.ajax({
                url: "{{route('branch.cashbookHO.updateExpense')}}",
                method:"POST",
                data:{id:id,date:date,amount:amount,reason:reason,mode:mode,transaction_id:transaction_id},
                cache: false,
                dataType: "json",
                success:function(data)
                {
                    if(data.success) {
                        toastr.success(data.success, 'SUCCESS')
                        window.setTimeout(function() {
                            window.location.href = '{{route('branch.cashbookHO.expense')}}';
                        }, 2500);
                    } 
                    else if(data.errors_success) 
                    {
                        toastr.warning(data.errors_success, 'WARNING')
                    } 
                    else if(data.errors_validation) 
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
            });
        }
    }

    //approved Expenses
    function approveExpenses(x)
    {
        let url = "{{route('branch.cashbookHO.approveExpense',':slip')}}";
        url = url.replace(':slip', x);

        $.ajax({
            url: url,
            method:"GET",
            data:{x:x},
            cache: false,
            dataType: "json",
            success:function(data)
            {
                if(data.success) {
                        toastr.success(data.success, 'SUCCESS')
                        window.setTimeout(function() {
                            window.location.href = '{{route('branch.cashbookHO.expense')}}';
                        }, 2500);
                    } 
                    else if(data.errors_success) 
                    {
                        toastr.warning(data.errors_success, 'WARNING')
                    } 
                    else if(data.errors_validation) 
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
        });
    }
</script>
@endsection