<?php

namespace App\Http\Controllers;


use App\Models\NotificationGroupModel;
use App\Models\Notifications;

use App\Http\Resources\EventsResource;
use Carbon\Carbon;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\NotificationGroupResource;
use Illuminate\Http\Request;


class EventsController extends Controller
{
    public function updateSmsEvent(Request $request)
    {
        $formFields = $request->validate([
            'id' => 'numeric|required',
            'type' => 'string|required',
            'start' => 'date|required',
        ]);
        cleanInputs($formFields);


        $id = $formFields['id'];
        if ($formFields['type'] === 'one') {
            if (Notifications::find($id)) {
                $notification = Notifications::find($id);
                $notification->scheduled_at = Carbon::parse($formFields['start'])->toDateTimeString();
                $notification->save();
                return response()->json(['status' => 'success', 'message' => 'Message Updated to be sent at ' . $formFields['start'], 'events' => $this->getEvents()]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Message can not be updated to be sent at ' . $formFields['start']]);
            }
        } else if ($formFields['type'] === 'many') {
            if (NotificationGroupModel::find($id)) {
                $notification = NotificationGroupModel::find($id);
                $notification->scheduled_at
                    = Carbon::parse($formFields['start'])->toDateTimeString();

                $notification->save();
                return response()->json(['status' => 'success', 'message' => 'Message Updated to be sent at ' . $formFields['start'], 'events' => $this->getEvents()]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Message can not be updated to be sent at ' . $formFields['start']]);
            }
        }
    }
    public function getEvents()
    {
        $events1 = Notifications::whereNotNull('scheduled_at')->where('user_id',auth()->id())->get();
        $events2 = NotificationGroupModel::whereNotNull('scheduled_at')->where('user_id', auth()->id())->get();

        // Combine the collections directly
        $events = $events1->concat($events2);
        return $events;
    }
    public function getSmsEvents()
    {

        // Combine the collections directly
        $events = $this->getEvents();

        return response()->json([
            'status' => 'success',
            'events' => count($events) > 0 ? EventsResource::collection($events) : $events

        ]);
    }
    public function destroyEventById(Request $request)
    {
        $formFields = $request->validate(['type' => 'string|required', 'id' => 'numeric|required']);

        $id = $formFields['id'];
        if ($formFields['type'] === 'one') {

            $notification = Notifications::find($id);
            $notification->delete();

            return response()->json(['status' => 'success', 'message' => 'Message Deleted Successfully !!']);
        } else if ($formFields['type'] === 'many') {

            $notification = NotificationGroupModel::find($id);
            $notification->delete();
            return response()->json(['status' => 'success', 'message' => 'Message Deleted Successfully !!']);
        }
        return response()->json(['status' => 'error', 'message' => 'Message Deleted Successfully !!']);
    }
    public function getEventById(Request $request)
    {
        $formFields = $request->validate(['type' => 'string|required', 'id' => 'numeric|required']);
        $id = $formFields['id'];
        if ($formFields['type'] === 'one') {

            $notification = Notifications::find($id);
            
            return response()->json(new NotificationResource($notification));
        } else if ($formFields['type'] === 'many') {

            $notification = NotificationGroupModel::find($id);

            return response()->json(new NotificationGroupResource($notification));
        }
    }
}
