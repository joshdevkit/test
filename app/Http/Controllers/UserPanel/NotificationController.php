<?php

namespace App\Http\Controllers\UserPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function removeNotification($index)
    {
        $notifications = session('notifications', []);

        if (isset($notifications[$index])) {
            unset($notifications[$index]);
            session(['notifications' => $notifications]);
        }

        return response()->json(['success' => true]);
    }


    public function markAllAsRead()
    {
        session()->forget('notifications');
        return back()->with('success', 'All notifications marked as read.');
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }
}
