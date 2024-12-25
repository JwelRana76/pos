<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title id="title">Money Receipt</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .container {
            margin-top: 20px;
        }

        .receipt{
            width: 850px;
            height: 450px;
            border: 1px solid lightgray;
            /* box-shadow: 5px 5px 5px 5px gray; */
            margin: auto;
            margin-top: 30px;
            box-sizing: border-box;
        }

        .receipt-body{
            box-sizing: border-box;
            width: 800px;
            height: 300px;
            /* border: 1px solid red; */
            margin: auto;
            margin-top: 20px;
            font-size: 16px;
            font-weight: 600;
        }
        p{
            margin: 0;
        }

        .receipt-header {
            width: 100%;
            height: 120px;
            /* border-bottom: 1px solid black; */
        }
        #div1 {
            position: relative;
            display: inline-block;
            overflow: hidden;
            color: #fff;
            width: 100%;
            height: 100%;
        }

        #div1:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #018843;
            -webkit-transform-origin: 100% 0;
            -ms-transform-origin: 100% 0;
            transform-origin: 100% 0;
            -webkit-transform: skew(-40deg);
            -ms-transform: skew(-40deg);
            transform: skew(-40deg);
            z-index: -1;
        }

        #div2 {
            position: relative;
            display: inline-block;
            overflow: hidden;
            color: #140101;
            width: 100%;
            height: 100%;
        }

        #div2:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #018843;
            -webkit-transform-origin: 100% 0;
            -ms-transform-origin: 100% 0;
            transform-origin: 100% 0;
            -webkit-transform: skew(40deg);
            -ms-transform: skew(40deg);
            transform: skew(40deg);
            z-index: -1;
        }
        table tr td{
            padding: 5px !important;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
            #print_btn{
                content: '';
                display: none;
            }
            #printableArea2{
                display:block;
            }
            #title{
                display: none;
            }

            /* #d1 {
                overflow: hidden;
                float: left;
            } */
            #printableArea{
                /* box-shadow: px 0px 5px gray; */
            }
            #div1 {
                position: relative;
                display: inline-block;
                overflow: hidden;
                color: #fff;
                width: 100%;
                height: 100%;
            }

            #div1:after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: #018843;
                -webkit-transform-origin: 100% 0;
                -ms-transform-origin: 100% 0;
                transform-origin: 100% 0;
                -webkit-transform: skew(-40deg);
                -ms-transform: skew(-40deg);
                transform: skew(-40deg);
                z-index: -1;
            }

            #div2 {
                position: relative;
                display: inline-block;
                overflow: hidden;
                color: #fff;
                width: 100%;
                height: 100%;
            }

            #div2:after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: #018843;
                -webkit-transform-origin: 100% 0;
                -ms-transform-origin: 100% 0;
                transform-origin: 100% 0;
                -webkit-transform: skew(40deg);
                -ms-transform: skew(40deg);
                transform: skew(40deg);
                z-index: -1;
            }
        }
    </style>
</head>

<body>
    @php
        $digit = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        $setting = DB::table('settings')->first();
    @endphp
    <div class="container">
        <div class="row col-12">
            <div class="col-lg-4 mx-auto text-center">
                <a type="button" href="{{  url()->previous() }}" class="btn btn-info" id="print_btn" >Return Back</a>
                <button type="button" class="btn btn-primary" id="print_btn" onclick="printDiv('printableArea')">Print Receipt</button>
            </div>
        </div>
    </div>
    <div class="receipt" id="printableArea">
        <div class="receipt-header" >
            <div id="d1" style="width: 40%;height:100%;float:left;">
                <div class="div1" id="div1" style="padding-left: 14px;padding-top:10px">
                    <h3 style="font-size:20px; text-decoration:underline">Supplier Detail</h3>
                    <p>{{ $purchase->supplier->name }}</p>
                    <p>{{ $purchase->supplier->address }}</p>
                    <p>{{ $purchase->supplier->contact }}</p>
                </div>
            </div>
            <div style="width: 20%;height:100%;float:left;text-align: center;">
                <h1 style="margin-top: 35px;color: #018843;font-size:30px;font-weight:700">Chalan <br>{{ $purchase->voucher_no }}</h1>
            </div>
            <div style="width: 40%;height:100%;float:right;">
                <div class="div2" id="div2" style="color: white;text-align: right;padding-right:14px">
                    <h3 style="font-size:20px; text-decoration:underline">Company Details</h3>
                    <p>{{ $setting->name }}</p>
                    <p>{{ $setting->address }}</p>
                    <p>{{ $setting->contact }}</p>
                </div>
            </div>
        </div>
        <div class="receipt-body" style="padding: 5px;padding-right: 10px;">
            <table class="table" style="font-size: 12px !important">
                <thead>
                    <tr>
                        <td>SL</td>
                        <td>Product</td>
                        <td>Unit Price</td>
                        <td>Quantity</td>
                        <td>Total</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase->product_purchase as $key=>$item)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $item->product->brand->name }} {{ $item->product->name }} {{ $item->product->size->name }} {{ $item->product->unit->name }}</td>
                            <td>{{ $item->unit_cost }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->qty * $item->unit_cost }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right">Sub Total : </td>
                        <td>{{ $purchase->total_amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right">Discount : </td>
                        <td>{{ $purchase->discount }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right">Grand Total : </td>
                        <td>{{ $purchase->grand_total }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right">Paid : </td>
                        <td>{{ $purchase->paid }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right">Due : </td>
                        <td>{{ $purchase->grand_total - $purchase->paid }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="text-align: center;padding-top:10px">
            <p>Powered By : </p>
            <a href="https://www.facebook.com/jwel.rana.1029" target="_blank">
                <img src="https://api.qrserver.com/v1/create-qr-code/?data=https://www.facebook.com/jwel.rana.1029&size=150x150" 
                    alt="Facebook QR Code" style="width:50px; height:50px;">
            </a>
        </div>

    </div>

    <script>
        // printDiv('x');
        function printDiv(divId) {


            window.print();

        }
    </script>
</body>

</html>
