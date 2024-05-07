<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationGroupResource;
use App\Http\Resources\NotificationResource;
use App\Models\ServiceCredentialsModel;
use App\Models\NotificationGroupModel;

use App\Models\Notifications;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function getDashboard()
    {
        $user = Auth::user();

        $totalNotif = Notifications::where("user_id", $user->id)->where("status",'sent')->count() ?? 0 + NotificationGroupModel::where("user_id", $user->id)->where("status", 'sent')->count() ?? 0;
        $usedService = $user->serviceCredentials()->orderBy('created_at', 'desc')->take(3)->get() ?? null;
        $latestContacts = $user->contacts()->orderBy('created_at', 'desc')->take(3)->get() ?? null;
        $lastNotif = Notifications::where("user_id", $user->id)->latest()->first() ?? null;
        $totalService = $user->serviceCredentials->count() ?? 0;
        $totalContact = $user->contacts->count() ?? 0;
        $totalScheduled_at = Notifications::where("user_id", $user->id)->whereNotNull('scheduled_at')->get()->count() + NotificationGroupModel::where("user_id", $user->id)->whereNotNull('scheduled_at')->get()->count();
        $notif =
            Notifications::where("user_id", $user->id)->whereNotNull('scheduled_at')->orderBy('created_at', 'desc')->take(2)->get()->toArray();
        $notif_group =
            NotificationGroupModel::where("user_id", $user->id)->whereNotNull('scheduled_at')->orderBy('created_at', 'desc')->take(2)->get()->toArray();
        $latestScheduledNotif = array_merge($notif, $notif_group);
        return response()->json([
            'totalNotif' => $totalNotif,
            'usedService' => $usedService,
            'latestNotif' => $lastNotif ? NotificationResource::make($lastNotif) : null,
            'totalService' => $totalService,
            'totalContact' => $totalContact,
            'latestContacts' => $latestContacts,
            'latestScheduledNotif' => $latestScheduledNotif,
            'totalScheduled_at' => $totalScheduled_at ?? 0

        ]);
    }


    public function allNotifications()
    {
        $notifications = Notifications::where('user_id', Auth::id())->get();
        return response()->json(NotificationResource::collection($notifications));
    }

    public function getAllNotifications()
    {
        $notifications = Notifications::where('user_id', Auth::id())->paginate(5);
        $notificationGroups = NotificationGroupModel::where('user_id', Auth::id())->paginate(5);

        // Return paginated data as a JSON response
        return response()->json([
            'notifications' => $notifications,
            'notificationGroups' => $notificationGroups,
        ]);
    }


    public function allServices()
    {
        $services = ServiceCredentialsModel::where('user_id', Auth::id())->paginate(10);
        return response()->json($services);
    }

    public function destroyNotif(Notifications $notification)
    {
        $user = Auth::user();
        if ($user->id !== $notification->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Oops something went wrong']);
        }

        $notification->delete();
        return response()->json(['status' => 'success', 'message' => 'deleted Successeffully !!']);
    }

    public function destroyService(ServiceCredentialsModel $service)
    {
        $user = Auth::user();

        if ($user->id !== $service->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Oops something went wrong'], 404);
        }

        $service->delete();
        return response()->json(['status' => 'success', 'message' => 'deleted Successeffully']);
    }

    public function getSingleService(ServiceCredentialsModel $service)
    {
        return response()->json($service);
    }

    public function getNotifGroup()
    {
        $notification_group =  NotificationGroupModel::where('user_id', Auth::id())->get();
        return response()->json(NotificationGroupResource::collection($notification_group));
    }
}
