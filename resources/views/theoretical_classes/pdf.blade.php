<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Class Assigning List PDF</title>
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
    <h2>Class Assigning List</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Trainee Name</th>
                <th>Trainer Name</th>
                <th>Class Name</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($theoretical_classes as $key => $theoreticalClass)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $theoreticalClass->trainee_name }}</td>
                    <td>{{ $theoreticalClass->trainer_name }}</td>
                    <td>{{ $theoreticalClass->class_name }}</td>
                    <td>{{ $theoreticalClass->start_date }}</td>
                    <td>{{ $theoreticalClass->end_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>