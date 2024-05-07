<?php

namespace App\Http\Controllers;

use App\Helpers\CleanInputs;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\NotificationGroupRequest;
use App\Http\Requests\VonageRequest;
use App\Http\Requests\NotificationRequest;
use App\Models\NotificationGroupModel;
use App\Models\Notifications;
use App\Models\ServiceCredentialsModel;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Vonage\SMS\Message\SMS as VonageMessage;



class VonageController extends Controller
{

    private User $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function checkVonage(VonageRequest $request)
    {

        // Validate the request data
        $credentials = $request->validated();
        cleanInputs($credentials);


        unset($credentials['phone_number']);
        $credentials['user_id'] = $this->user->id;
        $credentials['service_name'] = $credentials['service'];
        ServiceCredentialsModel::create($credentials);
        // Return success response
        return response(['success']);
    }

    public function sendSmsWithVonage(NotificationRequest $request)
    {
        // Validate the request data
        $validatedData = $request->validated();
        cleanInputs($validatedData);


        // Retrieve Vonage service credentials for the user
        $serviceCredentials = ServiceCredentialsModel::where('service_name', 'Vonage')->where('user_id', $this->user->id)->first();
        $validatedData['service_id'] = ServiceCredentialsModel::where('user_id', $this->user->id)->where('service_name', $validatedData['service'])->select('id')->first()->id ?? null;
        $validatedData['user_id'] = $this->user->id;

        // Check if Vonage service credentials exist for the user
        if ($serviceCredentials) {
            try {
                // Create a Vonage client instance using the stored credentials
                $vonage = new Client(new Basic($serviceCredentials->service_key, $serviceCredentials->service_token));

                // Send the SMS message
                $response = $vonage->sms()->send(
                    new VonageMessage($validatedData['to'], $validatedData['from'], $validatedData['body'])
                );

                $message = $response->current();

                if ($message->getStatus() == 0) {
                    // SMS sent successfully
                    $validatedData['status'] = 'sent';

                    Notifications::create($validatedData);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'The message was sent successfully',
                        'message_sid' => $message->getMessageId(),
                    ]);
                } else {
                    // SMS failed to send
                    return response()->json([
                        'status' => 'error',
                        'message' => 'The message failed with status: ' . $message->getStatus(),
                    ], 404);
                }
            } catch (\Exception $e) {
                // Handle exceptions
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error sending SMS : ' . $e->getMessage() . ', Pls check Your Service Credentials',
                ], 404);
            }
        } else {
            // If Vonage service credentials not found, return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Service credentials not found for Vonage',
            ], 404);
        }
    }

    public function updateVonageService(ServiceCredentialsModel $service, Request $request)
    {
        unset($request['phone_number']);
        $formFields = $request->all();
        if ($this->user->id !== $service->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Oops something went wrong']);
        }
        $service->update($formFields);
        return response()->json(['status' => 'success', 'message' => 'Updated Successeffully']);
    }

    public function sendSmsToAllContactsWithVonage(NotificationGroupRequest $request)
    {
        // Retrieve Vonage service credentials for the user
        $serviceCredentials = ServiceCredentialsModel::where('service_name', 'Vonage')
            ->where('user_id', Auth::id())
            ->first();

        // Validate the request data
        $validatedData = $request->validated();
        cleanInputs($validatedData);
        // Check if Vonage service credentials exist for the user
        if ($serviceCredentials) {
            try {
                // Create a Vonage client instance using the stored credentials
                $vonage = new Client(new Basic($serviceCredentials->service_key, $serviceCredentials->service_token));

                // Retrieve all contacts for the user
                $contacts = $validatedData['to'];

                // Loop through the contacts and send SMS to each contact
                foreach ($contacts as $contact) {
                    // Send the SMS message
                    $response = $vonage->sms()->send(
                        new VonageMessage($contact, $validatedData['from'], $validatedData['body'])
                    );

                    $message = $response->current();

                    if ($message->getStatus() !== 0) {
                        // SMS sent successfully
                        // SMS failed to send
                        return response()->json(['status' => 'error', 'message' => 'SMS failed to send to ' . $contact . ' with status: ' . $message->getStatus()], 404);
                    }
                }

                NotificationGroupModel::create([
                    'service_id' => $serviceCredentials->id,
                    'user_id' => Auth::id(),
                    'to' => json_encode($contacts),
                    'from' => $validatedData['from'],
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
            // If Vonage service credentials not found, return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Service credentials not found for Vonage',
            ], 404);
        }
    }
}
