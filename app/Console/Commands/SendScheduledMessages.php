<?php

namespace App\Console\Commands;

use App\Events\TriggerNotifications;
use App\Models\NotificationGroupModel;
use App\Models\Notifications;
use App\Models\ServiceCredentialsModel;
use App\Models\User;
use App\Notifications\smStatusNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client as VonageClient;
use Vonage\SMS\Message\SMS as VonageMessage;


class SendScheduledMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'app:send-scheduled-messages {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled messages that are due';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('userId');
        // Retrieve messages that are scheduled to be sent and haven't been sent yet
        $messages = Notifications::where('scheduled_at', '<=', now())
            ->where('status', 'pending')
            ->where('user_id', $userId)
            ->get();
        $groupMessages = NotificationGroupModel::where('scheduled_at', '<=', now())
            ->where('status', 'pending')
            ->where('user_id', $userId)

            ->get();


        if (count($messages) > 0) {
            // Loop through the messages and send them

            foreach ($messages as $message) {

                // Replace the sendSms method with your actual sending logic
                $this->sendSms($message);
            }
            $this->info('All scheduled messages sent.');
        }
        if (count($groupMessages) > 0) {
            foreach ($groupMessages as $group) {

                // Replace the sendSmsToGroup method with your actual sending logic
                $this->sendSmsToGroup($group);
            }
            $this->info('All scheduled group messages sent.');
        }
    }

    /**
     * Send SMS message (replace this with your actual sending logic)
     *
     * @param \App\Models\Notifications $message
     * @return bool
     */

    private function notif($notif)
    {
        // Find the user by user ID
        $userId = $this->argument('userId');
        $user = User::find($userId);


        // Create an instance of the custom notification class
        $user->notify(new smStatusNotification($notif));
    }
    private function sendSmsToGroup(NotificationGroupModel $group)
    {
        $userId = $this->argument('userId');
        // Retrieve the service credentials associated with the message
        $serviceCredentials = ServiceCredentialsModel::where('user_id', $userId)->where('id', $group->service_id)->first();

        if ($serviceCredentials && $serviceCredentials->service_name === 'Twilio') {
            $twilioCredentials = ServiceCredentialsModel::where('service_name', 'Twilio')
                ->where('user_id', $userId)
                ->first();


            if (isset($twilioCredentials)) {

                try {
                    // Create a Twilio client instance using the stored credentials
                    $twilio = new Client($serviceCredentials->service_key, $serviceCredentials->service_token);

                    // Retrieve all contacts for the user
                    $contacts = json_decode($group['to']);

                    // Loop through the contacts and send SMS to each contact
                    foreach ($contacts as $contact) {
                        // Send the SMS message
                        $message = $twilio->messages->create(
                            $contact,
                            [
                                'from' => $twilioCredentials->phone_number,
                                'body' => $group['body'],
                            ]
                        );

                        if ($message->status !== 'sent') {
                            $this->saveStatus('not sent', $group);

                            return false;
                        }
                    }
                    $this->saveStatus('sent', $group);


                    return true;
                } catch (\Exception $e) {
                    $this->saveStatus('not sent', $group);

                    // Handle exceptions
                    return false;
                }
            } else {
                $this->saveStatus('not sent', $group);

                // If Twilio service credentials not found, return error response
                return false;
            }
        } else if ($serviceCredentials && $serviceCredentials->service_name === 'Vonage') {
            $vonageCredentials = ServiceCredentialsModel::where('service_name', 'Vonage')
                ->where('user_id', $userId)
                ->first();

            if (isset($vonageCredentials)) {
                try {
                    // Create a Vonage client instance using the stored credentials
                    $vonage = new VonageClient(new Basic($serviceCredentials->service_key, $serviceCredentials->service_token));

                    // Retrieve all contacts for the user
                    $contacts = json_decode($group['to']);

                    // Loop through the contacts and send SMS to each contact
                    foreach ($contacts as $contact) {
                        // Send the SMS message
                        info('group ' . json_encode($group));
                        $response = $vonage->sms()->send(
                            new VonageMessage($contact, $group['from'], $group['body'])
                        );

                        $message = $response->current();

                        if ($message->getStatus() !== 0) {

                            $this->saveStatus('not sent', $group);

                            return false;
                        }
                    }

                    $this->saveStatus('sent', $group);
                    return true;
                } catch (\Exception $e) {
                    // Handle exceptions

                    $this->saveStatus('not sent', $group);

                    return false;
                }
            } else {

                // If Vonage service credentials not found, return error response
                $this->saveStatus('not sent', $group);
                return false;
            }
        }
    }

    public function saveStatus($status, $message)
    {
        $message->status = $status;
        $message->save();

        $this->notif($message);
    }
    private function sendSms(Notifications $message)
    {
        $userId = $this->argument('userId');

        // Retrieve the service credentials associated with the message
        $serviceCredentials = ServiceCredentialsModel::where('id', $message->service_id)->where('user_id', $userId)->first();

        // Check if Twilio service credentials exist for the user
        if ($serviceCredentials && $serviceCredentials->service_name === 'Twilio') {
            // Create a Twilio client instance using the stored credentials
            $twilio = new Client($serviceCredentials->service_key, $serviceCredentials->service_token);

            try {
                // Send SMS using Twilio client
                $TwilioResponse = $twilio->messages->create($message->to, [
                    "body" => $message->body,
                    "from" => $serviceCredentials->phone_number
                ]);

                // Check the status of the message
                if ($TwilioResponse->status == 'queued' || $TwilioResponse->status == 'sent') {
                    // // Update the status of the message to 'sent'
                    $this->saveStatus('sent', $message);





                    return true;
                } else {
                    // The message failed to send
                    $this->saveStatus('not sent', $message);
                    return false;
                }
            } catch (\Exception $e) {
                // Handle any exceptions that occur during the sending process
                $this->saveStatus('not sent', $message);
                return false;
            }
        } elseif ($serviceCredentials && $serviceCredentials->service_name === 'Vonage') {
            // Create a Vonage client instance using the stored credentials
            $vonage = new VonageClient(new Basic($serviceCredentials->service_key, $serviceCredentials->service_token));

            try {
                // Send the SMS message
                $response = $vonage->sms()->send(
                    new VonageMessage($message['to'], $message['from'], $message['body'])
                );

                $VonageResponse = $response->current();

                if ($VonageResponse->getStatus() == 0) {
                    // // Update the status of the message to 'sent'
                    $this->saveStatus('sent', $message);
                    return true;
                } else {
                    // The message failed to send
                    $this->saveStatus('not sent', $message);
                    return false;
                }
            } catch (\Exception $e) {
                // Handle exceptions
                $this->saveStatus('not sent', $message);
                return false;
            }
        }
    }
}
