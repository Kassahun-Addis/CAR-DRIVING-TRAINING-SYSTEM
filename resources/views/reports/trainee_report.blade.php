<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainee Report</title>
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
    <h2>Trainee Report</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Yellow Card No</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Nationality</th>
                <th>Birth Date</th>
                <th>Phone Number</th>
                <th>City</th>
                <th>License Type</th>
                <th>Education Level</th>
                <th>Blood Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $trainee)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trainee->yellow_card }}</td>
                    <td>{{ $trainee->full_name }}</td>
                    <td>{{ $trainee->gender }}</td>
                    <td>{{ $trainee->nationality }}</td>
                    <td>{{ $trainee->dob }}</td>
                    <td>{{ $trainee->phone_no }}</td>
                    <td>{{ $trainee->city }}</td>
                    <td>{{ $trainee->category }}</td>
                    <td>{{ $trainee->education_level }}</td>
                    <td>{{ $trainee->blood_type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>