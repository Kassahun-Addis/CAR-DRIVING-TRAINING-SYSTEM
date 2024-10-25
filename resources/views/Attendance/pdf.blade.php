<!DOCTYPE html>
     <html>
     <head>
         <title>Attendance List</title>
         <style>
             table {
                 width: 100%;
                 border-collapse: collapse;
             }
             th, td {
                 border: 1px solid black;
                 padding: 8px;
                 text-align: left;
             }
             th {
                 background-color: #f2f2f2;
             }
         </style>
     </head>
     <body>
         <h2>Attendance L</h2>
         <table>
             <thead>
                 <tr>
                     <th>No</th>
                     <th>Date</th>
                     <th>Start Time</th>
                     <th>Finish Time</th>
                     <th>Difference</th>
                     <th>Trainee Name</th>
                     <th>Trainer Name</th>
                     <th>Status</th>
                     <th>Comments</th>
                 </tr>
             </thead>
             <tbody>
                 @foreach($attendances as $key => $attendance)
                     <tr>
                         <td>{{ $key + 1 }}</td>
                         <td>{{ $attendance->date }}</td>
                         <td>{{ $attendance->start_time }}</td>
                         <td>{{ $attendance->finish_time }}</td>
                         <td>{{ $attendance->difference }}</td>
                         <td>{{ $attendance->trainee_name }}</td>
                         <td>{{ $attendance->trainer_name }}</td>
                         <td>{{ $attendance->status }}</td>
                         <td>{{ $attendance->comment }}</td>
                     </tr>
                 @endforeach
             </tbody>
         </table>
     </body>
     </html>