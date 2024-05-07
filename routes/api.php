<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VonageController;
use App\Http\Controllers\TwilioController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DraftsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\NotifiableController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);


    Route::delete('/deleteUser/{user}', [AuthController::class, 'destroy']);
    Route::get('/getUser', [AuthController::class, 'getUser']);

    Route::get('/allNotifications', [DashboardController::class, 'allNotifications']);
    Route::get('/allServices', [DashboardController::class, 'allServices']);
    Route::delete('/notification/{notification}', [DashboardController::class, 'destroyNotif']);
    Route::delete('/service/{service}', [DashboardController::class, 'destroyService']);
    Route::get('/getService/{service}', [DashboardController::class, 'getSingleService']);
    Route::put('/updateVonage/{service}', [VonageController::class, 'updateVonageService']);
    Route::put('/updateTwilio/{service}', [TwilioController::class, 'updateTwilioService']);
    Route::put('/updateUser/{user}', [AuthController::class, 'updateUserInfo']);
    Route::put('/updatePass', [AuthController::class, 'updatePass']);
    Route::post('/deleteEvent', [EventsController::class, 'destroyEventById']);

    Route::post('/sendSmsWithVonage', [VonageController::class, 'sendSmsWithVonage']);
    Route::post('/checkVonage', [VonageController::class, 'checkVonage']);
    Route::get('/allNotificationss', [DashboardController::class, 'getAllNotifications']);

    Route::get('/getDashboard', [DashboardController::class, 'getDashboard']);
    Route::get('/allContacts', [ContactController::class, 'getAllContacts']);
    Route::post('/saveContact', [ContactController::class, 'store']);
    Route::delete('/destroyContact/{contact}', [ContactController::class, 'destroy']);
    Route::post('/sendToVonageGroup', [VonageController::class, 'sendSmsToAllContactsWithVonage']);
    Route::post('/sendToTwilioGroup', [TwilioController::class, 'sendSmsToAllContactsWithTwilio']);
    Route::get('/getContact/{contact}', [ContactController::class, 'getContact']);
    Route::put('/updateContact/{contact}', [ContactController::class, 'update']);
    Route::get('/getNotifGroup', [DashboardController::class, 'getNotifGroup']);
    Route::post('/getEvent', [EventsController::class, 'getEventById']);

    Route::get('/getTwilioNumber', [TwilioController::class, 'getFromNumber']);
    Route::post('/sendSmsWithTwilio', [TwilioController::class, 'sendSmsWithTwilio']);
    Route::post('/checkTwilio', [TwilioController::class, 'checkTwilio']);
    Route::post('/contacts/csv', [ContactController::class, 'uploadCsv']);
    Route::post('/sendToCategory', [ContactController::class, 'sendSmsByCategory']);
    Route::get('/exportNotification', [NotificationController::class, 'exportNotifications']);
    Route::get('/smStatusNotifications', [NotifiableController::class, 'getSmStatusNotifications']);
    Route::put('/updateSmsEvent', [EventsController::class, 'updateSmsEvent']);
    Route::post('/setReadAt', [NotifiableController::class, 'setRead_at']);

    Route::get('/getSmsEvents', [EventsController::class, 'getSmsEvents']);
    // Route::post('/store_draft', [DraftsController::class, 'storeDraft']);
    // Route::put('/update_draft', [DraftsController::class, 'updateCurrentDraft']);
    Route::resource('/draft', DraftsController::class);
    Route::post('/scheduleNotif', [NotificationController::class, 'scheduledNotif']);
    Route::post('/scheduleNotifGroup', [NotificationController::class, 'scheduledNotifGroup']);
    Route::delete('/deleteAllNotifiable', [NotifiableController::class, 'deleteAllNotifiable']);
});
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    Route::post('/send-email', [AuthController::class, 'sendEmailVerification']);
    Route::post('/check-code', [AuthController::class, 'checkVerificationCode']);
    Route::post('/resend-code', [AuthController::class, 'resendEmailVerification']);

Route::post('/reset-pass', [AuthController::class, 'resetPassword']);
