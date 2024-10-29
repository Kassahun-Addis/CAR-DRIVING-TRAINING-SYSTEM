<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments List PDF</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Payments List</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Full Name</th>
                <th>TIN No</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>Bank Name</th>
                <th>Transaction No</th>
                <th>Sub Total</th>
                <th>VAT</th>
                <th>Total</th>
                <th>Paid Amount</th>
                <th>Remaining Balance</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $key => $payment)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $payment->full_name }}</td>
                    <td>{{ $payment->tin_no }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>{{ $payment->bank ? $payment->bank->bank_name : '' }}</td>
                    <td>{{ $payment->transaction_no }}</td>
                    <td>{{ $payment->sub_total }}</td>
                    <td>{{ $payment->vat }}</td>
                    <td>{{ $payment->total }}</td>
                    <td>{{ $payment->amount_paid }}</td>
                    <td>{{ $payment->remaining_balance }}</td>
                    <td>{{ $payment->payment_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>