<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainer Report</title>
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
    <h2>Trainer Report</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Trainer Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Experience (Years)</th>
                <th>Training Type</th>
                <th>Car Name</th>
                <th>Plate Number</th>
                <th>Category</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $trainer)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trainer->trainer_name }}</td>
                    <td>{{ $trainer->phone_number }}</td>
                    <td>{{ $trainer->email }}</td>
                    <td>{{ $trainer->experience }}</td>
                    <td>{{ $trainer->training_type }}</td>
                    <td>{{ $trainer->car_name }}</td>
                    <td>{{ $trainer->plate_no }}</td>
                    <td>{{ $trainer->category }}</td>
                    <td>{{ $trainer->status }}</td>
                    <td>{{ $trainer->created_at }}</td>
                    <td>{{ $trainer->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>