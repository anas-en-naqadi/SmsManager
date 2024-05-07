<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class NotifiableController extends Controller
{
    public function setRead_at(Request $request)
    {
        $date = $request->validate(['now' => 'date']);
        $user = Auth::user();

        $notifications = $user->notifications->where('read_at', null);
        foreach ($notifications as $notif) {
            $notif->read_at = Carbon::parse($date);
            $notif->save(); // Don't forget to save the changes to the database
        }
    }
    public function deleteAllNotifiable()
    {
        $user = Auth::user();
        foreach ($user->notifications as $notif) {
            $notif->delete();
        }
        return response()->json(['status' => 'success', 'message' => 'All Notifications Deleted Successfully !!']);
    }
    public function getSmStatusNotifications()
    {
        // Find the user by user ID
        $user = Auth::user();

        $status_notifications = $user->notifications ?? null;

        return response()->json($status_notifications);
    }
}
