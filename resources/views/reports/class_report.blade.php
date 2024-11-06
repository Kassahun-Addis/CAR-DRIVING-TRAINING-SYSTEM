<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Theoretical Class Report</title>
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
    <h2>Theoretical Class Report</h2>
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
            @foreach($data as $key => $class)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $class->trainee_name }}</td>
                    <td>{{ $class->trainer_name }}</td>
                    <td>{{ $class->class_name }}</td>
                    <td>{{ $class->start_date }}</td>
                    <td>{{ $class->end_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>