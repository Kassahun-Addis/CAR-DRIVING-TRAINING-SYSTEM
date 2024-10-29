<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicles List PDF</title>
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
    <h2>Vehicles List</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Vehicle Name</th>
                <th>Category</th>
                <th>Model</th>
                <th>Year</th>
                <th>Plate No</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainingCars as $key => $car)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $car->name }}</td>
                    <td>{{ $car->category }}</td>
                    <td>{{ $car->model }}</td>
                    <td>{{ $car->year }}</td>
                    <td>{{ $car->plate_no }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>