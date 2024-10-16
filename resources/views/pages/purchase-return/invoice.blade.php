<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .invoice-box {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            background-color: #ffffff;
        }
        .print-button {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .print-button button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .header .logo {
            width: 150px;
        }
        .header .company-details {
            text-align: right;
        }
        .header .company-details h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header .company-details p {
            margin: 5px 0;
            color: #777;
        }
        .invoice-details {
            padding: 20px;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .invoice-details h1 {
            font-size: 28px;
            margin: 0 0 10px;
            color: #333;
            border: 1px solid #ddd;
            padding: 5px 10px;
            background: white;
            border-radius: 5px;
            width: fit-content;
            float: center;
            margin: auto;
        }
        .invoice-details p {
            margin: 5px 0;
            color: #555;
        }
        .invoice-details .customer-details {
            margin-top: 10px;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-table th, .invoice-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        .invoice-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .total-section .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        table tfoot tr th{
          padding: 5px !important;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
        }
        @media print {
            .print-button {
                display: none;
            }
            table tfoot, table tfoot tr, table tfoot tr th{
              border: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="print-button">
            <button onclick="window.print()">Print</button>
        </div>
        <div class="header">
            <img src="/upload/{{ setting()->logo }}" alt="Company Logo" class="logo">
            <div class="company-details">
                <h2>{{ setting()->name ?? null }}</h2>
                <p>{{ setting()->address ?? null }}</p>
                <p>Email: {{ setting()->email ?? null }} | Phone: {{ setting()->contact ?? null }}</p>
            </div>
        </div>
        <div class="invoice-details">
            <h1>Invoice</h1>
            <p>Date: {{ $sale->created_at->format('d-M-Y') }}</p>
            <div class="customer-details">
                <p><strong>Bill To:</strong></p>
                <p>{{ $sale->customer->name ?? null }}</p>
                <p>{{ $sale->customer->address ?? null }}</p>
                <p>{{ $sale->customer->district->name ?? null }}, Zip Code: {{ $sale->customer->district->code }}</p>
                <p>Phone: {{ $sale->customer->contact ?? null }}</p>
            </div>
        </div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($sale->product_sale as $product)
              <tr>
                  <td>{{ $product->product->name ?? null }}</td>
                  <td>{{ $product->qty }}</td>
                  <td>{{ $product->unit_price }}</td>
                  <td>{{ $product->total_price }}</td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" style="text-align: right">Total Amount :</th>
                <th>{{ $sale->total_amount }}</th>
              </tr>
              <tr>
                <th colspan="3" style="text-align: right">Discount :</th>
                <th>{{ $sale->discount ?? 0 }}</th>
              </tr>
              <tr>
                <th colspan="3" style="text-align: right">Grand Total :</th>
                <th>{{ $sale->grand_total }}</th>
              </tr>
              <tr>
                <th colspan="3" style="text-align: right">Paid :</th>
                <th>{{ $sale->paid }}</th>
              </tr>
              <tr>
                <th colspan="3" style="text-align: right">Due :</th>
                <th>{{ $sale->grand_total - $sale->paid }}</th>
              </tr>
            </tfoot>
        </table>
        <div class="footer">
            <p>Thank you for Shopping!</p>
        </div>
    </div>
    <script>
      window.onafterprint = function() {
        window.close();
      };
    </script>
</body>
</html>
