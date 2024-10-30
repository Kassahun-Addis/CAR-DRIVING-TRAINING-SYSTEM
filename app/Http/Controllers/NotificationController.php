<?php

   // app/Http/Controllers/NotificationController.php

   namespace App\Http\Controllers;

   use App\Models\Notification;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Auth;

   class NotificationController extends Controller
   {
       // app/Http/Controllers/NotificationController.php

       public function index(Request $request)
        {
            $user = Auth::user();
            $search = $request->input('search');

            // Base query for unread notifications
            $unreadQuery = Notification::whereDoesntHave('users', function ($query) use ($user) {
                $query->where('trainee_id', $user->id);
            })->where('is_active', true);

            // Base query for read notifications
            $readQuery = Notification::whereHas('users', function ($query) use ($user) {
                $query->where('trainee_id', $user->id);
            })->where('is_active', true);

            // Apply search filters if a search term is provided
            if ($search) {
                $unreadQuery->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('created_at', 'like', "%{$search}%");
                });

                $readQuery->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('created_at', 'like', "%{$search}%");
                });
            }

            // Paginate the results
            $unreadNotifications = $unreadQuery->orderBy('created_at', 'desc')->paginate(10);
            $readNotifications = $readQuery->orderBy('created_at', 'desc')->paginate(10);

            return view('notifications.index', compact('unreadNotifications', 'readNotifications'));
        }

       // app/Http/Controllers/NotificationController.php

    public function markAsRead(Notification $notification)
    {
        $user = Auth::guard('trainee')->user();

        // Attach the notification to mark it as read for this user
        $user->notifications()->attach($notification->id);

        return response()->json(['success' => true]);
    }
       public function create()
       {
           return view('notifications.create');
       }

       public function store(Request $request)
       {
           $request->validate([
               'title' => 'required|string|max:255',
               'content' => 'required|string',
           ]);

           Notification::create($request->all());

           return redirect()->route('notifications.index')->with('success', 'Notification created successfully.');
       }

            public function edit(Notification $notification)
        {
            return view('notifications.edit', compact('notification'));
        }


        public function update(Request $request, Notification $notification)
        {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            // Update the notification
            $notification->update($request->all());

            // Reset the read status for all users
            $notification->users()->detach();

            return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
        }

        public function destroy(Notification $notification)
        {
            try {
            $notification->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error deleting notification: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete notification'], 500);
            }
        }   

   }
