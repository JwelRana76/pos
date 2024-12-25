<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .invoice-container {
            max-width: 400px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #777;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
            font-size: 14px;
            color: #333;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items th, .items td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }
        .items th {
            background-color: #f4f4f4;
            color: #333;
        }
        .totals {
            margin-bottom: 20px;
        }
        .totals p {
            font-size: 14px;
            margin: 5px 0;
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        .footer p {
            font-size: 12px;
            color: #777;
        }
        .print-btn {
            display: block;
            margin: 10px auto;
            width: 100px;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
@php
        $digit = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        $setting = DB::table('settings')->first();
    @endphp
<body>
    <button class="print-btn" onclick="window.print()">Print</button>
    <div class="invoice-container">
        <div class="header">
            <h1>{{ $setting->name }}</h1>
            <p>{{ $setting->address }}</p>
            <p>Phone: {{ $setting->contact }}</p>
        </div>
        <div class="details">
            <p><strong>Invoice No:</strong> {{ $sale->voucher_no }}</p>
            <p><strong>Date:</strong> {{ $sale->created_at->format('d-M-Y') }}</p>
            <p><strong>Customer:</strong> {{ $sale->customer->name }}</p>
        </div>
        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($sale->product_sale as $item)
                  <tr>
                    <td>{{ $item->product->name }} {{ $item->product->size->name }} {{ $item->product->unit->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->unit_price }}</td>
                    <td>{{ $item->qty * $item->unit_price }}</td>
                  </tr>
              @endforeach
            </tbody>
        </table>
        <div class="totals">
            <p><strong>Subtotal:</strong> {{ number_format($sale->total_amount,2) }}</p>
            <p><strong>Tax (5%):</strong> 0.00</p>
            <p><strong>Discount:</strong> {{ number_format($sale->discount,2) }}</p>
            <p><strong>Total:</strong> {{ number_format($sale->grand_total,2) }}</p>
            <p><strong>Paid:</strong> {{ number_format($sale->paid,2) }}</p>
            <p><strong>Change:</strong> {{ number_format($sale->grand_total - $sale->paid,2) }}</p>
        </div>
        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>Powered by </p>
            <a href="https://www.facebook.com/jwel.rana.1029" target="_blank">
                <img src="https://api.qrserver.com/v1/create-qr-code/?data=https://www.facebook.com/jwel.rana.1029&size=150x150" 
                    alt="Facebook QR Code" style="width:50px; height:50px;">
            </a>
        </div>
    </div>
</body>
</html>
