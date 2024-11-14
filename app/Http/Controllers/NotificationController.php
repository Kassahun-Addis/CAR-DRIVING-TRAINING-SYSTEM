<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyId = app('currentCompanyId'); // Retrieve the current company ID
        $search = $request->input('search');

        // Unread notifications for the specific company
        $unreadQuery = Notification::whereDoesntHave('users', function ($query) use ($user) {
                $query->where('trainee_id', $user->id);
            })
            ->where('company_id', $companyId) // Filter by company ID
            ->where('is_active', true);

        // Read notifications for the specific company
        $readQuery = Notification::whereHas('users', function ($query) use ($user) {
                $query->where('trainee_id', $user->id);
            })
            ->where('company_id', $companyId) // Filter by company ID
            ->where('is_active', true);

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

        public function indexTrainee(Request $request)
    {
        $user = Auth::guard('trainee')->user();
        $companyId = app('currentCompanyId'); // Retrieve the current company ID
        $search = $request->input('search');

        // Unread notifications for the specific company
        $unreadQuery = Notification::whereDoesntHave('users', function ($query) use ($user) {
                $query->where('trainee_id', $user->id);
            })
            ->where('company_id', $companyId) // Filter by company ID
            ->where('is_active', true);

        // Read notifications for the specific company
        $readQuery = Notification::whereHas('users', function ($query) use ($user) {
                $query->where('trainee_id', $user->id);
            })
            ->where('company_id', $companyId) // Filter by company ID
            ->where('is_active', true);

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

        // Return the trainee-specific view
        return view('notifications.index_trainee', compact('unreadNotifications', 'readNotifications'));
    }

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

        // Attach the notification to the current company
        $companyId = app('currentCompanyId');
        Notification::create([
            'title' => $request->title,
            'content' => $request->content,
            'company_id' => $companyId,
        ]);

        return redirect()->route('notifications.index')->with('success', 'Notification created successfully.');
    }

    public function edit(Notification $notification)
    {
        // Ensure the notification belongs to the current company
        $this->authorizeCompany($notification);

        return view('notifications.edit', compact('notification'));
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Ensure the notification belongs to the current company
        $this->authorizeCompany($notification);

        // Update the notification
        $notification->update($request->all());

        // Reset the read status for all users
        $notification->users()->detach();

        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
    }

    public function destroy(Notification $notification)
    {
        // Ensure the notification belongs to the current company
        $this->authorizeCompany($notification);

        try {
            $notification->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error deleting notification: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete notification'], 500);
        }
    }

    private function authorizeCompany(Notification $notification)
    {
        $companyId = app('currentCompanyId');
        if ($notification->company_id !== $companyId) {
            abort(403, 'Unauthorized action.');
        }
    }
}