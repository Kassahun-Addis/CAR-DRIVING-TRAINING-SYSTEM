<?php

   // app/Http/Middleware/IsSuperAdmin.php

   namespace App\Http\Middleware;

   use Closure;
   use Illuminate\Support\Facades\Auth;

   class IsSuperAdmin
   {
       public function handle($request, Closure $next)
       {
           if (Auth::check() && Auth::user()->isSuperAdmin()) {
               return $next($request);
           }

           return redirect('/home')->with('error', 'Unauthorized access.');
       }
   }
