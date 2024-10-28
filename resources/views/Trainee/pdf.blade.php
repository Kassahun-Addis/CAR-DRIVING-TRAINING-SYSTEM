<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainee List PDF</title>
    <style>
        @font-face {
            font-family: 'Nyala';
            src: url('{{ asset('fonts/nyala.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
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
            word-wrap: break-word;
            width: auto;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Trainee List</h2>
    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>Yellow Card No</th>
            <th>Full Name</th>
            <th>ሙሉ_ስም</th>
            <th>Gender</th>
            <th>ጾታ</th>
            <th>Nationality</th>
            <th>ዜግነት</th>
            <th>City</th>
            <th>ከተማ</th>
            <th>Sub City</th>
            <th>ክፍለ_ከተማ</th>
            <th>Woreda</th>
            <th>ወረዳ</th>
            <th>House No</th>
            <th>Phone No</th>
            <th>PO.Box</th>
            <th>Birth Place</th>
            <th>የትዉልድ_ቦታ</th>
            <th>Education Level</th>
            <th>Blood Type</th>
            <th>Receipt No</th>
            <th>DOB</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
            @foreach($trainees as $key => $trainee)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trainee->yellow_card }}</td>
                    <td>{{ $trainee->full_name }}</td>
                    <td>{{ $trainee->ሙሉ_ስም }}</td>
                    <td>{{ $trainee->gender }}</td>
                    <td>{{ $trainee->ጾታ }}</td>
                    <td>{{ $trainee->nationality }}</td>
                    <td>{{ $trainee->ዜግነት }}</td>
                    <td>{{ $trainee->city }}</td>
                    <td>{{ $trainee->ከተማ }}</td>
                    <td>{{ $trainee->sub_city }}</td>
                    <td>{{ $trainee->ክፍለ_ከተማ }}</td>
                    <td>{{ $trainee->woreda }}</td>
                    <td>{{ $trainee->ወረዳ }}</td>
                    <td>{{ $trainee->house_no }}</td>
                    <td>{{ $trainee->phone_no }}</td>
                    <td>{{ $trainee->po_box }}</td>
                    <td>{{ $trainee->birth_place }}</td>
                    <td>{{ $trainee->የትዉልድ_ቦታ }}</td>
                    <td>{{ $trainee->education_level }}</td>
                    <td>{{ $trainee->blood_type }}</td>
                    <td>{{ $trainee->receipt_no }}</td>
                    <td>{{ \Carbon\Carbon::parse($trainee->dob)->format('Y-m-d') }}</td>
                    <td>{{ $trainee->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>