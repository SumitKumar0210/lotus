<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale</title>
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

        <div style="text-align:center;">
            <h2>Challan</h2>
        </div>

        <h4>Consignor :</h4>
        <h5 style="margin: 0 0 4px 0;">BWC</h5>
        <div class="table-text" style="    margin-bottom: 110px;">
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
                        <td style="border: none; text-align: left">
                            <strong>Address:</strong>{{ $estimate->client_address }}
                        </td>
                    </tr>
                    <tr style="border: none; text-align: left">
                        <td style="border: none; text-align: left"><strong>Email:</strong>
                            {{ $estimate->client_email }}
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
                    <th>Code</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Qty</th>
                    <th>Description</th>
                </tr>
            </thead>
            {{-- <tbody>
                @if (!empty($estimate_product_lists))
                    @php $count1 = 1; @endphp
                    @foreach ($estimate_product_lists as $EstimateProductList)
                        <tr>
                            <td>{{ $count1++ }}</td>
                            <td>{{ $EstimateProductList->product_name }}</td>
                            <td>{{ $EstimateProductList->product_code }}</td>
                            <td>{{ $EstimateProductList->color }}</td>
                            <td>{{ $EstimateProductList->size }}</td>
                            <td>{{ $EstimateProductList->qty }}</td>
                            <td>{{ $EstimateProductList->Product->description }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody> --}}



            <tbody>
                @if (!empty($estimate_product_list_delivery_status_lists))
                    @php $count1 = 1; @endphp
                    @foreach ($estimate_product_list_delivery_status_lists as $EstimateProductList)
                        <tr>
                            <td>{{ $count1++ }}</td>
                            <td>{{ $EstimateProductList->ProductList->product_name }}</td>
                            <td>{{ $EstimateProductList->ProductList->product_code }}</td>
                            <td>{{ $EstimateProductList->ProductList->color }}</td>
                            <td>{{ $EstimateProductList->ProductList->size }}</td>
                            <td>{{ $EstimateProductList->qty }}</td>
                            <td>{{ $EstimateProductList->ProductList->Product->description }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>




        </table>
        <br>
        <div class="table-text" style="margin-top: 80px;">
            <div style="position: absolute;left: 50px;">
                <table class="left-table" style="border: none; text-align: left">
                    <tr style="border: none; text-align: left">
                        <td style="border: none; text-align: left"><strong>Consignor Sign.:</strong> </td>
                    </tr>
                </table>
            </div>
            <div style="position: absolute;
  right: 50px;">
                <table class="right-table" style="border: none; text-align: right;">
                    <tr style="border: none; text-align: right;">
                        <td style="border: none; text-align: right;"><strong style="margin-right:40px">Consignee
                                Sign.:</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>


    </div>
</body>

</html>
