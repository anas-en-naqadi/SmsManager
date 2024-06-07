<?php

namespace App\Http\Controllers;

use App\Helpers\CleanInputs;
use App\Http\Requests\NotificationGroupRequest;
use App\Http\Requests\NotificationRequest;
use App\Models\ContactModel;
use App\Models\NotificationGroupModel;
use App\Models\Notifications;
use App\Models\ServiceCredentialsModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{



    public function scheduledNotif(NotificationRequest $request)
    {
        $notification = $request->validated();
        cleanInputs($notification);

        $notification['status'] = 'pending';
        $notification['user_id'] = Auth::id();

        $notification['service_id'] = ServiceCredentialsModel::where('user_id', Auth::id())->where('service_name', $notification['service'])->select('id')->first()->id ?? null;
        $now = Carbon::now();
        $scheduledDate = Carbon::parse($notification['scheduled_date'] . ' ' . $notification['scheduled_time']);

        if ($scheduledDate->lessThan($now)) 
        return response()->json(['status' => 'error', 'message' => 'you can\'t schedule this message in an old date !!'], 422);
        else
        $notification['scheduled_at'] = $notification['scheduled_date'] . ' ' . $notification['scheduled_time'];


        Notifications::create($notification);
        return response()->json(['status' => 'success', 'message' => 'Notification will be sent at ' . $notification['scheduled_date'] . ' ' . $notification['scheduled_time']]);
    }
    public function scheduledNotifGroup(NotificationGroupRequest $request)
    {
        $notification = $request->validated();
        cleanInputs($notification);

        $service = "";
        if (isset($notification['category'])) $service = $notification['category'] ? ContactModel::where('category', $notification['category'])->first()->service_name : null;
        $notification['status'] = 'pending';
        $notification['user_id'] = Auth::id();
        $notification['service_id'] = $service ? ServiceCredentialsModel::where('user_id', Auth::id())->where('service_name', $service)->select('id')->first()->id  : ServiceCredentialsModel::where('user_id', Auth::id())->where('service_name', $notification['service'])->select('id')->first()->id;
        if ($notification['scheduled_date'] >= now())
            $notification['scheduled_at'] = $notification['scheduled_date'] . ' ' . $notification['scheduled_time'];
        else
            return response()->json(['status' => 'error', 'message' => 'you can\'t schedule this message in an old date !!'], 422);

        $notification['to'] = json_encode($notification['to']);
        NotificationGroupModel::create($notification);
        return response()->json(['status' => 'success', 'message' => 'Notification will be sent to all contacts at ' . $notification['scheduled_date'] . ' ' . $notification['scheduled_time']]);
    }
    public function exportNotifications()
    {
        // Fetch data from your database
        $notifications = Notifications::all();
        $notificationGroups = NotificationGroupModel::all();

        // Convert data to arrays
        $notificationData = $notifications->toArray();
        $notificationGroupData = $notificationGroups->toArray();

        // Merge data
        $data = array_merge($notificationData, $notificationGroupData);

        // Define CSV header
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=export.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // Define the callback function for converting data to CSV format
        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');

            // Add CSV header row
            fputcsv($handle, array_keys($data[0]));

            // Add data rows
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        // Capture the CSV content in a variable
        ob_start();
        $callback();
        $csvContent = ob_get_clean();

        // Encode the CSV content as a base64 string
        $csvBase64 = base64_encode($csvContent);

        // Return JSON response with CSV data
        return response()->json([
            'message' => 'CSV File Exported Successfully !!',
            'csv' => $csvBase64
        ]);
    }
}
