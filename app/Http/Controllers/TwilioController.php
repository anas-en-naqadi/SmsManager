<?php

namespace App\Http\Controllers;

use App\Helpers\CleanInputs;

use App\Http\Requests\NotificationGroupRequest;
use App\Http\Requests\NotificationRequest;
use App\Http\Requests\TwilioRequest;
use App\Models\NotificationGroupModel;
use App\Models\Notifications;
use App\Models\User;

use App\Models\ServiceCredentialsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

class TwilioController extends Controller
{
    private User $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function checkTwilio(TwilioRequest $request)
    {
        // Validate the request data
        $credentials = $request->validated();
        cleanInputs($credentials);

        // Create a new Twilio client instance
        $twilio = new Client($credentials['service_key'], $credentials['service_token']);

        // If successful, save the credentials to the database
        $credentials['user_id'] = $this->user->id;
        $credentials['service_name'] = $credentials['service'];
        if ($credentials['service_name'] === 'Twilio' && !str_contains($credentials['phone_number'], '+')) {
            $credentials['phone_number'] = '+' . $credentials['phone_number'];
        }

        ServiceCredentialsModel::create($credentials);

        // Return success response
        return response(['success']);
    }

    public function sendSmsWithTwilio(NotificationRequest $request)
    {
        $validatedData = $request->validated();
        cleanInputs($validatedData);

        unset($validatedData['server']);

        $validatedData['user_id'] = $this->user->id;
        $validatedData['service_id'] = ServiceCredentialsModel::where('user_id', $this->user->id)->where('service_name', $validatedData['service'])->select('id')->first()?->id;
        $serviceCredentials = ServiceCredentialsModel::where('service_name', 'Twilio')->where('user_id', $this->user->id)->first();
        $validatedData['from'] = $serviceCredentials->phone_number;

        // Check if Twilio service credentials exist for the user
        if ($serviceCredentials) {
            // Create a Twilio client instance using the stored credentials
            $twilio = new Client($serviceCredentials->service_key, $serviceCredentials->service_token);

            try {
                // Send SMS using Twilio client
                $message = $twilio->messages->create($validatedData['to'], [
                    "body" => $validatedData['body'],
                    "from" => $serviceCredentials->phone_number
                ]);

                // Check the status of the message
                if ($message->status == 'queued' || $message->status == 'sent') {
                    // The message was sent successfully
                    $validatedData['status'] = 'sent';

                    Notifications::create($validatedData);
                    return response(['success' => 'SMS sent successfully', 'message_sid' => $message->sid]);
                } else {
                    // The message failed to send
                    return response(['error' => 'Failed to send SMS'], 500);
                }
            } catch (\Exception $e) {
                // Handle any exceptions that occur during the sending process
                return response(['error' => 'Failed to send SMS: ' . $e->getMessage() . ', Pls check Your Service Credentials'], 500);
            }
        } else {
            // If Twilio service credentials not found, return error response
            return response(['error' => 'Service credentials not found for Twilio'], 404);
        }
    }

    public function updateTwilioService(ServiceCredentialsModel $service, Request $request)
    {
        $formFields = $request->all();
        if (Auth::id() !== $service->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Oops something went wrong']);
        }
        $service->update($formFields);
        return response()->json(['status' => 'success', 'message' => 'Updated Successeffully']);
    }

    public function sendSmsToAllContactsWithTwilio(NotificationGroupRequest $request)
    {
        // Retrieve Twilio service credentials for the user
        $serviceCredentials = ServiceCredentialsModel::where('service_name', 'Twilio')
            ->where('user_id', $this->user->id)
            ->first();

        // Validate the request data
        $validatedData = $request->validated();
        cleanInputs($validatedData);

        // Check if Twilio service credentials exist for the user
        if ($serviceCredentials) {
            try {
                // Create a Twilio client instance using the stored credentials
                $twilio = new Client($serviceCredentials->service_key, $serviceCredentials->service_token);

                // Retrieve all contacts for the user
                $contacts = $validatedData['to'];

                // Loop through the contacts and send SMS to each contact
                foreach ($contacts as $contact) {
                    // Send the SMS message
                    $message = $twilio->messages->create(
                        $contact,
                        [
                            'from' => $serviceCredentials->phone_number,
                            'body' => $validatedData['body'],
                        ]
                    );

                    if ($message->status !== 'sent') {
                        // SMS failed to send
                        return response()->json(['status' => 'error', 'message' => 'SMS failed to send to ' . $contact . ' with status: ' . $message->status], 404);
                    }
                }

                NotificationGroupModel::create([
                    'service_id' => $serviceCredentials->id,
                    'user_id' => $this->user->id,
                    'to' => json_encode($contacts),
                    'from' => $serviceCredentials->phone_number,
                    'body' => $validatedData['body'],
                    'status' => 'sent'
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'SMS sent to all contacts successfully',
                ]);
            } catch (\Exception $e) {
                // Handle exceptions

                return response()->json([
                    'status' => 'error',
                    'message' => 'Error sending SMS to all contacts: ' . $e->getMessage() . ', Pls check Your Service Credentials',
                ], 404);
            }
        } else {
            // If Twilio service credentials not found, return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Service credentials not found for Twilio',
            ], 404);
        }
    }

    public function getFromNumber()
    {
        $phone_number = ServiceCredentialsModel::where('service_name', 'Twilio')->first()->phone_number;
        return response()->json($phone_number);
    }
}
