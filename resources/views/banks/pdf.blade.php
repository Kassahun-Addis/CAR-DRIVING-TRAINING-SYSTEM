<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bank List PDF</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Bank List</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bank Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banks as $key => $bank)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $bank->bank_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>