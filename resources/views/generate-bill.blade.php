<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .bill {
            width: 100%;
            height: 90%;
            padding: 0;
            box-sizing: border-box;
            background-color: #f7f7f7;
        }
        .header {
            text-align: center;
            margin: 20px 0;
        }
        .order-info {
            margin: 0 0 20px;
            display: flex;
            justify-content: space-between;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="bill">
        <div class="header">
            <h2>Order Invoice</h2>
        </div>
        <div class="order-info">
            <div class="order-details">
                <p><strong>Order ID:</strong> {{ $orderId }}</p>
                <p><strong>Date:</strong> {{ $orderDate }}</p>
            </div>
            <p><strong>Order Name:</strong> {{ $orderName }}</p>
            <p><strong>cashier:</strong> {{ $name }}</p>
            <p><strong>Payment Mehthod:</strong> {{ $paymentMthd }}</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderItems as $item)
                <tr>
                    <td>{{ $item['productName'] }}</td>
                    <td>{{ $item['productQuantity'] }}</td>
                    <td>{{ number_format($item['productPrice']) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total">
                    <td colspan="2"><strong>Total:</strong></td>
                    <td><strong>Rp {{ number_format($totalPrice) }}</strong></td>
                </tr>
            </tfoot>
        </table>
        <div class="footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>
