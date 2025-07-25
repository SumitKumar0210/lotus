<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Print</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <style type="text/css">
        body {
            font-family: 'Roboto', sans-serif;
        }

        .card-content {
            padding: 24px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
            text-align: center;
            padding: 10px 0;
        }

        table,
        th {
            font-size: 14px;
        }

        table,
        td {
            font-size: 12px;
        }

        .description-list {
            text-align: left;
            font-size: 11px;
            margin: 0;
        }

        td.valign-middle {
            text-align: left;
            vertical-align: initial;
            padding-left: 5px;
        }

        label.main-content-label {
            font-weight: bold;
        }

        h5.table-title {
            margin: 15px 0;
            font-size: 16px;
        }

        .table-text {
            position: relative;
            width: 100%;
            margin-bottom: 150px;
        }

        table.left-table,
        th,
        td {
            < !-- text-align: left !important;
            -->padding: 5px 0;
        }

        table.right-table,
        th,
        td {
            padding: 5px 0;
        }

    </style>

    <script>
        window.print();
    </script>
</head>

<body>


    <div class="card-content ">
        <div style="text-align:right;">
            <img src="{{ asset('backend/assets/logo-lotus.png') }}" width="100px">
        </div>
        <h4>Consignor :</h4>
        <div class="table-text" style="  margin-bottom: 110px;">
            <div style="position: absolute;
   left: 0;">
                <table class="left-table" style="border: none; text-align: left">

                    <tr style="border: none; text-align: left">
                        <td style="border: none; text-align: left"><strong>Address:</strong>
                            {{ $current_branch->address }}
                        </td>
                    </tr>
                    <tr style="border: none; text-align: left">
                        <td style="border: none; text-align: left"><strong>Phone:</strong>
                            {{ $current_branch->phone }}
                        </td>
                    </tr>
                    <tr style="border: none; text-align: left">
                        <td style="border: none; text-align: left"><strong>Email:</strong>
                            {{ $current_branch->email }}
                        </td>
                    </tr>
                </table>
            </div>
            <div style="position: absolute;
  right: 0;">
                <table class="right-table" style="border: none; text-align: right;">

                    <tr style="border: none; text-align: right;">
                        <td style="border: none; text-align: right;"><strong>Estimate #:</strong>
                            {{ $estimate->estimate_no }}
                        </td>
                    </tr>
                    <tr style="border: none; text-align: right;">
                        <td style="border: none; text-align: right;"><strong>Date:</strong>
                            {{ $estimate->estimate_date }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <hr style="width: 100%;">

        <div class="table-text">
            <div style="position: absolute;
       left: 0;">
                <table class="left-table" style="border: none; text-align: left">
                    <tr style="border: none; text-align: left">
                        <th style="border: none; text-align: left;font-size: 1.3em;">Consignee :</th>
                    </tr>
                    <tr style="border: none; text-align: left">
                        <td style="border: none; text-align: left"><strong>Name:</strong> {{ $estimate->client_name }}
                        </td>
                    </tr>
                    <tr style="border: none; text-align: left">
                        <td style="border: none; text-align: left"><strong>Mobile:</strong>
                            {{ $estimate->client_mobile }}
                        </td>
                    </tr>
                    <tr style="border: none; text-align: left">
                        <td style="border: none; text-align: left"><strong>Address:</strong>
                            {{ $estimate->client_address }}
                        </td>
                    </tr>
                    <tr style="border: none; text-align: left">
                        <td style="border: none; text-align: left">
                            <strong>Email:</strong>{{ $estimate->client_email }}
                        </td>
                    </tr>
                </table>
            </div>
            <div style="position: absolute;right: 0;">
                <table class="right-table" style="border: none; text-align: right;">
                    <tr style="border: none; text-align: right;">
                        <th style="border: none; text-align: right;">Executive :</th>
                    </tr>
                    <tr style="border: none; text-align: right;">
                        <td style="border: none; text-align: right;"><strong>Sale By:</strong>
                            {{ $estimate->sale_by }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <h5 class="table-title">Item Details</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Code</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    
                </tr>
            </thead>
            <tbody>
                @if (!empty($estimate->EstimateProductLists))
                    @php $count1 = 1; @endphp
                    @foreach ($estimate->EstimateProductLists as $EstimateProductList)
                        <tr>
                            <td>{{ $count1++ }}</td>
                            <td>{{ $EstimateProductList->product_name }}</td>
                            <td>{{ $EstimateProductList->Product->Category->category_name ?? '' }}</td>
                            <td>{{ $EstimateProductList->product_code }}</td>
                            <td>{{ $EstimateProductList->color }}</td>
                            <td>{{ $EstimateProductList->size }}</td>
                            <td>{{ $EstimateProductList->qty }}</td>
                            <td>{{ $EstimateProductList->mrp }}</td>
                            <td>{{ $EstimateProductList->amount }}</td>
                           
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <h5 class="table-title">Payment Details</h5>
        <table class="table table-invoice table-striped table-bordered scrolldown">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paid In Cash</th>
                    <th>Paid In Bank</th>
                    <th>Total Paid</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($estimate->EstimatePaymentLists))
                    @php $count2 = 1; @endphp
                    @foreach ($estimate->EstimatePaymentLists as $EstimatePaymentList)
                        <tr>
                            <td>{{ $count2++ }}</td>
                            <td>{{ $EstimatePaymentList->paid_in_cash }}</td>
                            <td>{{ $EstimatePaymentList->paid_in_bank }}</td>
                            <td>{{ $EstimatePaymentList->total_paid }}</td>
                            <td>{{ $EstimatePaymentList->date_time }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <table>
            <thead>
            <tbody>
                <tr>
                    <td class="valign-middle" colspan="2" rowspan="9">
                        <div class="invoice-notes">
                            <label class="main-content-label tx-10">Terms & Conditions:</label>
                            <p class="tx-9-f">
                                1. Delivery Against Full Payment Delivery Charges Extra.
                                <br>2. GST 18% Extra.
                                <br>3. No Warranty on Leather, Leatherette, Fabric Rexine & Polish.
                                <br>4. This is not Final It's a Estimate.
                                <br>5. Warranty Against any Manufacturing Defect.
                                <br>6. Goods Once Sold Cannot be Returned /Exchanged.
                                <br>7. All Disputes are Subject to Patna Jurisdiction.
                            </p>

                        </div><!-- invoice-notes -->
                    </td>
                    <td>Sub Total</td>
                    <td colspan="2">&#8377; {{ $estimate->sub_total }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td colspan="2">&#8377; {{ $estimate->discount_value }}</td>
                </tr>
                <tr>
                    <td>Freight</td>
                    <td colspan="2">&#8377; {{ $estimate->freight_charge }}</td>
                </tr>
                <tr>
                    <td>Mise</td>
                    <td colspan="2">&#8377; {{ $estimate->misc_charge }}</td>
                </tr>
                <tr>
                    <td>Grand Total</td>
                    <td colspan="2">&#8377; {{ $estimate->grand_total }}</td>
                </tr>
                <tr>
                    <td>Paid In Cash</td>
                    <td colspan="2">&#8377; {{ $paid_in_cash }}</td>
                </tr>
                <tr>
                    <td>Paid In Bank</td>
                    <td colspan="2">&#8377; {{ $paid_in_bank }}</td>
                </tr>
                <tr>
                    <td><strong>Total Paid</strong></td>
                    <td colspan="2">
                        <h4 style="margin:0;">&#8377; {{ $total_paid }}</h4>
                    </td>
                </tr>
                <tr>
                    <td>Due</td>
                    <td colspan="2">&#8377; {{ $estimate->dues_amount }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
