<!DOCTYPE html>
     <html lang="en">
     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>CSRF Test Form</title>
     </head>
     <body>
         <form method="POST" action="{{ url('/api/save-exam-score') }}">
             @csrf
             <input type="hidden" name="trainee_id" value="1">
             <input type="hidden" name="score" value="10">
             <input type="hidden" name="company_id" value="2">
             <button type="submit">Submit</button>
         </form>
     </body>
     </html>