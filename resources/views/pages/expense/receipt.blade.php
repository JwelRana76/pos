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

        .receipt-header {
            width: 100%;
            height: 100px;
            /* border-bottom: 1px solid black; */
        }
        input:disabled{
            background: #fff;
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
            <div id="d1" style="width: 30%;height:100%;float:left;">
                <div class="div1" id="div1">
                    <h3 style="margin-left: -50px;margin-top:40px;text-align: center;font-size:20px;">Expense Payment</h3>
                </div>
            </div>
            <div style="width: 40%;height:100%;float:left;text-align: center;">
                <h1 style="margin-top: 35px;color: #018843;font-size:30px;font-weight:700">Money Receipt</h1>
            </div>
            <div style="width: 30%;height:100%;float:right;">
                <div class="div2" id="div2" style="color: white;text-align: right;">
                    <h2 style="margin-right: 14px;font-size:25px;margin-top:10px">{{ $setting->name }}</h2>
                    <p>{{ $setting->address }}</p>
                    <p>{{ $setting->contact }}</p>
                </div>
            </div>
        </div>
        <div class="receipt-body" style="padding: 5px;padding-right: 10px;">
            <div style="width: 100% ; float: left; box-sizing: border-box;padding-right: 15px;">
                <div style="width:10%;float: left;line-height: 30px;margin-right:8px;">
                    Memo No
                </div>
                <div style="width:20%;float: left;">
                    <input type="text" style="border:0px;border-bottom:3px dotted #018843;padding-left:10px" value="{{ $expense->voucher_no ?? null }}" disabled>
                </div>
                <div style="width:20%;float: left;">

                </div>
                <div style="width:22.5%;float: right;">
                    <input type="text" style="border:0px;border-bottom:3px dotted #018843;" value="{{date('d-M-Y',strtotime($expense->created_at))}}" disabled>
                </div>
                <div style="width:6%;float: right;line-height: 30px;">
                    Date
                </div>

            </div>
            <div style="width: 100% ; float: left;margin-top: 10px;">
                <div style="width:28%;float: left;line-height: 30px;">
                    Received with thanks from
                </div>
                <div style="width:72%;float: left;">
                    <input type="text" style="border:0px;border-bottom:3px dotted #018843;width: 100%;" value="{{$expense->name ?? null}}" disabled>
                </div>
            </div>
            <div style="width: 100% ; float: left;margin-top: 10px;">
                <div style="width:17.5%;float: left;line-height: 30px;">
                    Amount in word
                </div>
                <div style="width:82.5%;float: left;">
                    <input type="text" style="border:0px;border-bottom:3px dotted #018843;width: 100%;"  value="{{ ucwords($digit->format($expense->amount)) }} Taka Only" disabled>
                </div>
            </div>
            <div style="width: 100% ; float: left;margin-top: 10px;">
                <div style="width:16.5%;float: left;line-height: 30px;">
                    By cash/chaque
                </div>
                <div style="width:25.5%;float: left;">
                    <input type="text" style="border:0px;border-bottom:3px dotted #018843;width: 100%;" value="Cash" disabled>
                </div>
                <div style="width:6.5%;float: left;line-height: 30px;margin-left: 10px;">
                    Bank
                </div>
                <div style="width:21.5%;float: left;">
                    <input type="text" style="border:0px;border-bottom:3px dotted #018843;width: 100%;" disabled>
                </div>
                <div style="width:6%;float: left;line-height: 30px;margin-left: 10px;" >
                    Date
                </div>
                <div style="width:21.4%;float: right;">
                    <input type="text" style="border:0px;border-bottom:3px dotted #018843;width: 100%;"  value="{{date('d-M-Y',strtotime($expense->created_at))}}" disabled>
                </div>
            </div>
            <div style="width: 100% ; float: left;margin-top: 10px;">
                <div style="width:19.5%;float: left;line-height: 30px;">
                    For the purpose of
                </div>
                <div style="width:40.5%;float: left;">
                    <input type="text" style="border:0px;border-bottom:3px dotted #018843;width: 100%;" value="Salary Money Receipt" disabled>
                </div>
                <div style="width:6.5%;float: left;line-height: 30px;margin-left: 10px;">
                    Contact
                </div>
                <div style="width:29.5%;float: right;">
                    <input type="text" style="border:0px;border-bottom:3px dotted #018843;width: 100%;" value="{{$expense->employee ?? null}}" disabled>
                </div>

            </div>
            <div style="width: 100% ; float: left;margin-top: 20px;">
                <div style="width:9.5%;float: left;line-height: 30px;">
                    Amount
                </div>
                <div style="width:25.5%;float: left;">
                    <input type="text" style="border:1px solid gray;width: 70%;height:28px;" value="{{$expense->amount}} /-" disabled>
                </div>
                <div style="width:24.5%;float: left;margin-left: 00px;text-align:center">

                    <input type="text" style="border:0px;border-bottom:1px solid gray;width: 100%;height:28px;margin-top: -30px;" disabled><br>
                    <label for="">Received By</label>
                </div>

                <div style="width:30.5%;float: left;margin-left: 50px;text-align:center">

                    <input type="text" style="border:0px;border-bottom:1px solid gray;width: 100%;height:28px;margin-top: -30px;" disabled><br>
                    <label for="">Authorized Signature</label>
                </div>

            </div>
        </div>

    </div>

    <script>
        printDiv('x');
        function printDiv(divId) {


            window.print();

        }
    </script>
</body>

</html>
