<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainers Assigning List PDF</title>
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
    <h2>Trainers Assigning List</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Trainee Name</th>
                <th>Trainer Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Category</th>
                <th>Car Name</th>
                <th>Plate No</th>
                <th>Total Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainers_assigning as $key => $trainer_assigning)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trainer_assigning->trainee_name }}</td>
                    <td>{{ $trainer_assigning->trainer_name }}</td>
                    <td>{{ $trainer_assigning->start_date }}</td>
                    <td>{{ $trainer_assigning->end_date }}</td>
                    <td>{{ $trainer_assigning->category_id }}</td>
                    <td>{{ $trainer_assigning->car_name }}</td>
                    <td>{{ $trainer_assigning->plate_no }}</td>
                    <td>{{ $trainer_assigning->total_time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>