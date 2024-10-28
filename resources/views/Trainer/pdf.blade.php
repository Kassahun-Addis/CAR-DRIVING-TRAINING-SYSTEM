<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainers List PDF</title>
     <style>
        @font-face {
            font-family: 'Nyala';
            src: url('{{ asset('fonts/nyala.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'Nyala', sans-serif;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            word-wrap: break-word;
            width: auto;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Trainers List</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Experience (Years)</th>
                <th>Training Type</th>
                <th>Category</th>
                <th>Car Name</th>
                <th>Plate No</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainers as $key => $trainer)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trainer->trainer_name }}</td>
                    <td>{{ $trainer->phone_number }}</td>
                    <td>{{ $trainer->email }}</td>
                    <td>{{ $trainer->experience }}</td>
                    <td>{{ $trainer->training_type }}</td>
                    <td>{{ $trainer->category }}</td>
                    <td>{{ $trainer->car_name }}</td>
                    <td>{{ $trainer->plate_no }}</td>
                    <td>{{ $trainer->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
