@extends('backend.branch.layouts.master')
@section('title')
Create Cash Book
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
            <h2 class="main-content-title tx-24 mg-b-5">Create Cashbook</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('branch.cashbookHO.cashbookList')}}">Cashbook</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Cashbook</li>
            </ol>
        </div>
    </div>
    <!-- End Page Header -->
    <!-- Row -->
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 ">
            <div class="card custom-card">
                <div class="card-body">
                    <div>
                        <h5 class=" card-title mb-1">Transfer Cash To Branch</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <div class="">
                                <div class="row row-xs align-items-center mt-2">
                                    <div class="col-md-2">
                                        <label>Branch Name</label>
                                    </div>
                                    <div class="col-md-12">
                                        <select class="form-control" id="branch">
                                            <option value="">Select Branch</option>
                                            @foreach($branchList as $branch)
                                            <option value="{{$branch->id}}">{{$branch->branch_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12 mt-2 mb-2">
                                        <h5 class="card-title">Add Note Denomination</h5>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table" style="border: 1px solid #e1e6f1;">
                                            <thead>
                                                <tr>
                                                    <th colspan="2" class="text-center">Currency</th>
                                                    <th colspan="2" class="text-center">No.Of Notes</th>
                                                    <th class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input class="form-control" type="number" name="currency[]"
                                                            readonly value="500"></td>
                                                    <td class="pt-3">X</td>
                                                    <td><input class="form-control" type="number" name="no_of_note[]"
                                                            value="0" onchange="calcAmount(this.value,'500')">
                                                    </td>
                                                    <td class="pt-3">=</td>
                                                    <td><input class="form-control total_500" type="number"
                                                            name="total[]" readonly value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-control" type="number" name="currency[]"
                                                            readonly value="100"></td>
                                                    <td class="pt-3">X</td>
                                                    <td><input class="form-control" type="number" name="no_of_note[]"
                                                            value="0" onchange="calcAmount(this.value,'100')">
                                                    </td>
                                                    <td class="pt-3">=</td>
                                                    <td><input class="form-control total_100" type="number"
                                                            name="total[]" readonly value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-control" type="number" name="currency[]"
                                                            readonly value="50"></td>
                                                    <td class="pt-3">X</td>
                                                    <td><input class="form-control" type="number" name="no_of_note[]"
                                                            value="0" onchange="calcAmount(this.value,'50')">
                                                    </td>
                                                    <td class="pt-3">=</td>
                                                    <td><input class="form-control total_50" type="number"
                                                            name="total[]" readonly value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-control" type="number" name="currency[]"
                                                            readonly value="20"></td>
                                                    <td class="pt-3">X</td>
                                                    <td><input class="form-control" type="number" name="no_of_note[]"
                                                            value="0" onchange="calcAmount(this.value,'20')">
                                                    </td>
                                                    <td class="pt-3">=</td>
                                                    <td><input class="form-control total_20" type="number"
                                                            name="total[]" readonly value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-control" type="number" name="currency[]"
                                                            readonly value="10"></td>
                                                    <td class="pt-3">X</td>
                                                    <td><input class="form-control" type="number" name="no_of_note[]"
                                                            value="0" onchange="calcAmount(this.value,'10')">
                                                    </td>
                                                    <td class="pt-3">=</td>
                                                    <td><input class="form-control total_10" type="number"
                                                            name="total[]" readonly value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-control" type="number" name="currency[]"
                                                            readonly value="5"></td>
                                                    <td class="pt-3">X</td>
                                                    <td><input class="form-control" type="number" name="no_of_note[]"
                                                            value="0" onchange="calcAmount(this.value,'5')">
                                                    </td>
                                                    <td class="pt-3">=</td>
                                                    <td><input class="form-control total_5" type="number" name="total[]"
                                                            readonly value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-control" type="number" name="currency[]"
                                                            readonly value="2"></td>
                                                    <td class="pt-3">X</td>
                                                    <td><input class="form-control" type="number" name="no_of_note[]"
                                                            value="0" onchange="calcAmount(this.value,'2')">
                                                    </td>
                                                    <td class="pt-3">=</td>
                                                    <td><input class="form-control total_2" type="number" name="total[]"
                                                            readonly value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-control" type="number" name="currency[]"
                                                            value="1" readonly></td>
                                                    <td class="pt-3">X</td>
                                                    <td><input class="form-control" type="number" name="no_of_note[]"
                                                            value="0" onchange="calcAmount(this.value,'1')">
                                                    </td>
                                                    <td class="pt-3">=</td>
                                                    <td><input class="form-control total_1" type="number" name="total[]"
                                                            readonly value="0"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <p class="text-right pt-2"><strong>Total<strong></p>
                                                    </td>
                                                    <td class="pt-3">=</td>
                                                    <td><input class="form-control total_amount" type="number"
                                                            name="total_amount" value="0" readonly></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="text-right">
                                    {{-- <div class="form-group mg-b-20">
                                        <label class="ckbox">
                                            <input checked type="checkbox"><span class="tx-13">Notify Me</span>
                                        </label>
                                    </div> --}}
                                    <button class="btn ripple btn-main-primary" type="button"
                                        onclick="addCashbook()">Submit</button>
                                </div>
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

    //calcute amount
    function calcAmount(amount,currency)
    {
        let total = 0;
        let calc = parseInt(amount) * parseInt(currency);
        $('.total_'+currency).val(calc);

        $('input[name="total[]"]').each(function(){
            total = parseFloat(total) + parseFloat($(this).val());
            //console.log(total);
        });
        $('.total_amount').val(total);
    }

    //create cashbook
    function addCashbook()
    {
        let branch = $('#branch').val();
        let amount = $('.total_amount').val();
        let currency = $("input[name='currency[]']").map(function () {return $(this).val();}).get();
        let no_of_note = $("input[name='no_of_note[]']").map(function () {return $(this).val(); }).get();
        let total = $("input[name='total[]']").map(function () {return $(this).val();}).get();
        if(branch == '')
        {
            toastr.warning('Select Branch', 'ERROR')
        }
        else if(amount < 1)
        {
            toastr.warning('Enter No Of Notes');
        }
        else{
            $.ajax({
                url: "{{route('branch.cashbookHO.store')}}",
                method:"POST",
                data:{branch:branch,amount:amount,currency:currency,no_of_note:no_of_note,total:total},
                cache: false,
                dataType: "json",
                success:function(data)
                {
                    console.log(data);
                    if (data.success) {
                        toastr.success(data.success, 'SUCCESS')
                        window.setTimeout(function() {
                            window.location.href = '{{route('branch.cashbookHO.cashbookList')}}';
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
            });
        }
    }

</script>
@endsection