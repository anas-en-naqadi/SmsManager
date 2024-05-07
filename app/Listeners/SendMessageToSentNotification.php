<?php


namespace App\Listeners;

use App\Models\User;
use App\Notifications\smStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageToSentNotification
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationSent $event)
    {
        // Retrieve the notification instance from the event
        $notification = $event->notification;

        // Retrieve the message from the notification
        $message = $notification->message;

        // Check if the message is sent successfully
        if ($message->status == 'sent') {
            // Update the status of the message to 'sent'
            $message->status = 'sent';
            $message->save();

            // Find the user by user ID
            $user = User::find($message->user_id);

            // Create an instance of the custom notification class
            $notification = new smStatusNotification($message);

            // Send the notification to the user
            $user->notify($notification);
        }
    }

}
