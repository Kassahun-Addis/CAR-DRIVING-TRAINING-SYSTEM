<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Report</title>
    <style>
        body {
            font-family: 'Nyala', sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Payment Report</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Full Name</th>
                <th>TIN No</th>
                <th>Custom ID</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>Bank ID</th>
                <th>Transaction No</th>
                <th>Sub Total</th>
                <th>VAT</th>
                <th>Total</th>
                <th>Amount Paid</th>
                <th>Remaining Balance</th>
                <th>Payment Status</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $payment)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $payment->full_name }}</td>
                    <td>{{ $payment->tin_no }}</td>
                    <td>{{ $payment->custom_id }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>{{ $payment->bank_id }}</td>
                    <td>{{ $payment->transaction_no }}</td>
                    <td>{{ number_format($payment->sub_total, 2) }}</td>
                    <td>{{ number_format($payment->vat, 2) }}</td>
                    <td>{{ number_format($payment->total, 2) }}</td>
                    <td>{{ number_format($payment->amount_paid, 2) }}</td>
                    <td>{{ number_format($payment->remaining_balance, 2) }}</td>
                    <td>{{ $payment->payment_status }}</td>
                    <td>{{ $payment->created_at }}</td>
                    <td>{{ $payment->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>