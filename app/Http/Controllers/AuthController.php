<?php

namespace App\Http\Controllers;


use App\Http\Requests\RegisterRequest;
use App\Models\User;


use App\Http\Requests\LoginRequest;
use App\Mail\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{
    public function resendEmailVerification(Request $request)
    {

        // Validate the code received in the request
        $validatedData = $request->validate([
            'code' => 'required|numeric',
        ]);

        // Find the user by email
        $user = User::where('reset_token', $validatedData['code'])->first();

        // If the user exists, send the email
        if ($user) {
            // Send the verification email
            $random = $this->sendEmail($user);

            // Return success response
            return response()->json(['status' => 'success', "token" => $random], 200);
        } else {
            // Return error response if user not found
            return response()->json(['status' => 'error', 'message' => 'User with this email does not exist'], 404);
        }
    }
    public function sendEmailVerification(Request $request)
    {
        // Validate and retrieve the email from the request
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        // Find the user by email
        $user = User::where('email', $validatedData['email'])->first();

        // If the user exists, send the email
        if ($user) {
            // Send the verification email
            $random = $this->sendEmail($user);

            // Return success response
            return response()->json(['status' => 'success', "token" => $random], 200);
        } else {
            // Return error response if user not found
            return response()->json(['status' => 'error', 'message' => 'User with this email does not exist'], 404);
        }
    }

    private function sendEmail(User $user): string
    {
        $random = (string) rand(100000, 999999);
        $user->reset_token = $random;
        $user->reset_token_expires_at = now()->addMinutes(30); // Set expiration to 30 minutes from now
        $user->save();

        // Send email with verification code and token
        Mail::to($user->email)->send(new ForgotPassword($random));
        return $random;
    }

    public function checkVerificationCode(Request $request)
    {
        // Validate the code received in the request
        $validatedData = $request->validate([
            'code' => 'required|numeric',
        ]);

        // Find the user by the provided code and where reset_token is equal to the code and not expired
        $user = User::where('reset_token', $validatedData['code'])
            ->where('reset_token_expires_at', '>=', now()) // Check if the token is not expired
            ->first();

        if (!$user) {
            // Token not found or expired
            return response()->json(['status' => 'error', 'message' => 'Invalid token'], 402);
        }




        return response()->json(['status' => 'success'], 200);
    }

    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'numeric|required',
            'password' => 'confirmed|required|min:8',
        ]);

        $user = User::where('reset_token', $validatedData['code'])->first();

        if (!$user) {
            // Token not found or user does not exist
            return response()->json(['status' => 'error', 'message' => 'Invalid token'], 404);
        }

        if ($user->reset_token_expires_at && Carbon::parse($user->reset_token_expires_at)->isPast()) {
            // Token has expired
            return response()->json(['status' => 'error', 'message' => 'Token has expired'], 422);
        }

        // Token is valid and not expired, allow password reset
        // Reset the user's password and clear the reset token
        $user->password = Hash::make($validatedData['password']);
        $user->reset_token = null;
        $user->reset_token_expires_at = null;

        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Password reset successfully']);
    }


    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();




        if (Auth::attempt($credentials, $credentials->remember ?? false)) {
            $user = Auth::user();
            $token = $user->createToken('main')->plainTextToken;
            return response()->json(['status' => 'success', 'user ' => $user, 'token' => $token]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Wrong Credentials. Pls verify !!']);
        }
    }




    public function register(RegisterRequest $request)
    {

        $formFields = $request->validated();
        cleanInputs($formFields);

        $formFields['password'] = Hash::make($formFields['password']);
        User::create($formFields);

        return response()->json([
            'status' => 'success',

        ]);
    }


    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json(['status' => 'success', 'message' => 'Logged out successfully']);
    }


    public function getUser()
    {

        $user = Auth::user();
        $user['image_url'] = $user['image'] ? URL::to($user['image']) : null;
        return response()->json($user);
    }
    public function updatePass(Request $request)
    {
        if (Hash::check($request->currentPass, Auth::user()->password)) {

            if (strlen($request->newPass) >= 8) {


                if ($request->newPass === $request->confirmed_password) {
                    $user = Auth::user();
                    $user['password'] = Hash::make($request->newPass);
                    $user->save();
                } else {

                    return response(['error' => 'The password doesn\'t match'], 420);
                }
            } else {
                return response(['error' => 'The password must be at least 8 characters long'], 420);
            }
        } else {
            return response(['error' => 'The password is Wrong'], 420);
        }
        return response()->json(['status' => 'success']);
    }
    public function updateUserInfo(Request $request, User $user)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'image' => 'nullable', // Adjust the max file size as needed
        ]);
        if (isset($validated['image'])) {
            $validated['image'] = $this->storeImage($validated['image']);
        }

        $user->update($validated);
        $user['image_url'] = URL::to($validated['image']);
        return response($user);
    }

    private function storeImage($image)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            $image = substr($image, strpos($image, ','));
            $type = strtolower($type[1]);
            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new Exception('invalid image type');
            }
            $image = str_replace('', '+', $image);
            $image = base64_decode($image);
        } else {
            throw new Exception('did not match data urL with image');
        }
        $dir = 'storage/profiles/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);
        return $relativePath;
    }
    public function destroy(User $user)
    {
        // Delete user's surveys
        foreach ($user->serviceCredentials as $service) {
            // Delete answers associated with the user's survey
            $service->sms_notifications()->delete();



            // Delete the user's survey
            $service->delete();
        }

        // Finally, delete the user
        $user->delete();
        return response('successfful');
    }
}
