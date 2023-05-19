<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class PasswordResetController extends Controller
{
    public function sendResetCode(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $code = Str::random(6); // Generate a 6-digit verification code
        $expiresAt = Carbon::now()->addMinutes(30); // Set expiration time to 30 minutes from now


        DB::table('password_resets')->insert([
            'email' => $request->email,
            'code' => $code,
            'created_at' => now(),
            'expires_at' => $expiresAt, // Insert the expiration time

        ]);

        // Send the verification code to the user via email or any other preferred method

        Mail::to($request->email)->send(new ResetPasswordMail($code,$user));
        return response()->json(['message' => 'Verification code sent to your email']);
    }

    public function resetPasswordWithCode(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'code' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $resetRecord = DB::table('password_resets')
                         ->where('email', $request->email)
                         ->where('code', $request->code)
                         ->first();

        if (!$resetRecord) {
            return response()->json(['message' => 'Invalid verification code'], 404);
        }

        // Check if the verification code is still valid by comparing with the current timestamp
        $expiresAt = Carbon::parse($resetRecord->expires_at);
        if ($expiresAt->isPast()) {
            // Verification code has expired
            return response()->json(['message' => 'Verification code has expired'], 404);
        }

        // Update the password for the corresponding user in the users table

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Delete the verification code from the password_resets table

        DB::table('password_resets')
          ->where('email', $request->email)
          ->where('code', $request->code)
          ->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }

}
