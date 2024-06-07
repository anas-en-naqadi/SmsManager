<?php

namespace App\Http\Controllers;

use App\Helpers\CleanInputs;
use App\Http\Requests\ContactRequest;

use App\Models\ContactModel;
use App\Models\NotificationGroupModel;
use App\Models\ServiceCredentialsModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Csv\Reader;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client as TwilioClient;
use Vonage\Client\Credentials\Basic;
use Vonage\Client;


use Vonage\SMS\Message\SMS as VonageMessage;

class ContactController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }
    public function uploadCsv(Request $request)
    {

        // Check if the file exists and is a valid CSV file
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);

            // Check if the CSV file has the correct number of columns
            if (!empty(array_diff(['name', 'phone number', 'service name', 'address', 'category'], $csv->getHeader()))) {
                return response()->json(['error' => 'Invalid Csv headers.'], 400);
            }

            $records = [];
            foreach ($csv as $record) {
                // Check if the CSV file has the correct number of columns
                if (count($record) !== count($csv->getHeader())) {
                    return response()->json(['error' => 'Invalid number of columns in CSV file.'], 400);
                }

                // Map the CSV columns to the database columns
                $records[] = [
                    'name' => $record['name'],
                    'phone_number' => $record['phone number'],
                    'user_id' => Auth::id(),
                    'service_name' => $record['service name'],
                    'category' => $record['category'],
                    'address' => $record['address'],
                ];
            }

            // Validate the mapped records
            foreach ($records as $record) {
                $validator = Validator::make($record, [
                    'name' => 'required|string|max:255',
                    'phone_number' => 'required|string|max:255',
                    'user_id' => 'required',
                    'service_name' => 'required|string|max:255',
                    'category' => 'string|max:255',
                    'address' => 'required|string|max:255',
                ]);

                // Check if validation failed
                if ($validator->fails()) {
                    return response()->json(['error' => 'Invalid data in CSV file.'], 400);
                }
            }

            // Store the records in the database
            foreach ($records as $record) {
                ContactModel::create($record);
            }

            return response()->json(['message' => 'File uploaded and records stored successfully.'], 200);
        }

        return response()->json(['error' => 'Invalid file Type .'], 400);
    }
    public function getAllContacts()
    {
        $contacts =  ContactModel::where('user_id', Auth::id())->paginate(10);
        return response($contacts);
    }
    public function store(ContactRequest $request)
    {
        $formFields = $request->validated();

        cleanInputs($formFields);


        if (!empty($formFields['category'])) {
            $existingContact = ContactModel::where('category', $formFields['category'])
                ->where('service_name', '!=', $formFields['service_name'])
                ->where('user_id', Auth::id())
                ->first();

            if ($existingContact) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You can only add new contact with same category and the same service !!.',
                ], 400);
            }
        }
        $formFields['user_id'] = Auth::id();




        ContactModel::create($formFields);
        return response()->json(['status' => 'success', 'message' => 'Contact Saved Successfully !!']);
    }
    public function destroy(ContactModel $contact)
    {
        $contact->delete();
        return response()->json(['status' => 'success', 'message' => 'Contact Deleted Successfully !!']);
    }
    public function getContact(ContactModel $contact)
    {
        return response($contact);
    }
    public function update(ContactModel $contact, Request $request)
    {


        $contact->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Contact Updated Successfully !!']);
    }
    public function sendSmsByCategory(Request $request)
    {

        $notification = $request->validate([
            'to' => 'required|array',
            'from' => 'required|string',
            'body' => 'required|string',
            'category' => 'required|string',

        ]);
        cleanInputs($notification);





        $service =  ContactModel::where('user_id', Auth::id())->where('category', $notification['category'])->first()->service_name;

        if ($service == 'Twilio') {
            $twilioCredentials = ServiceCredentialsModel::where('service_name', $service)
                ->where('user_id', Auth::id())
                ->first();







            if ($twilioCredentials) {

                try {
                    // Create a Twilio client instance using the stored credentials
                    $twilio = new TwilioClient($twilioCredentials->service_key, $twilioCredentials->service_token);

                    // Retrieve all contacts for the user
                    $contacts = $notification['to'];

                    // Loop through the contacts and send SMS to each contact
                    foreach ($contacts as $contact) {
                        // Send the SMS message
                        $message = $twilio->messages->create(
                            $contact,
                            [
                                'from' => $twilioCredentials->phone_number,
                                'body' => $notification['body'],
                            ]
                        );

                        if ($message->status !== 'sent') {
                            // SMS failed to send
                            return response()->json(['status' => 'error', 'message' => 'SMS failed to send to ' . $contact . ' with status: ' . $message->status], 404);
                        }
                    }

                    NotificationGroupModel::create([
                        'service_id' => $twilioCredentials->id,
                        'user_id' => $this->user->id,
                        'to' => json_encode($contacts),
                        'from' => $twilioCredentials->phone_number,
                        'body' => $message['body'],
                        'status' => 'sent'
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'SMS sent to all Category Twilio contacts successfully',
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
        } else if ($service === 'vonage') {
            $vonageCredentials = ServiceCredentialsModel::where('service_name', $service)
                ->where('user_id', Auth::id())
                ->first();

            if ($vonageCredentials) {
                try {
                    // Create a Vonage client instance using the stored credentials
                    $vonage = new Client(new Basic($vonageCredentials->service_key, $vonageCredentials->service_token));

                    // Retrieve all contacts for the user
                    $contacts = $notification['to'];

                    // Loop through the contacts and send SMS to each contact
                    foreach ($contacts as $contact) {
                        // Send the SMS message
                        $response = $vonage->sms()->send(
                            new VonageMessage($contact, $notification['from'], $notification['body'])
                        );

                        $message = $response->current();

                        if ($message->getStatus() !== 0) {

                            return response()->json(['status' => 'error', 'message' => 'SMS failed to send to ' . $contact . ' with status: ' . $message->getStatus()], 404);
                        }
                    }

                    NotificationGroupModel::create([
                        'service_id' => $vonageCredentials->id,
                        'user_id' => Auth::id(),
                        'to' => json_encode($contacts),
                        'from' => $notification['from'],
                        'body' => $notification['body'],
                        'status' => 'sent'
                    ]);
                    return response()->json([
                        'status' => 'success',
                        'message' => 'SMS sent to all Category Vonage contacts successfully',
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
}
