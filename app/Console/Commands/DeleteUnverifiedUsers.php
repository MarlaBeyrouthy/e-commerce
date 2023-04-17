<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unverified-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        // Get the current time
        $currentTime = now();

        // Specify the time limit for verification in minutes (e.g. 60 minutes)
        $verificationTimeLimit = 60;

        // Calculate the cutoff time for unverified users
        $cutoffTime = $currentTime->subMinutes($verificationTimeLimit);

        // Retrieve unverified users who have not provided verification code within the specified time limit
        $unverifiedUsers = User::where('verified', false)
                               ->where('created_at', '<', $cutoffTime)
                               ->get();

        // Loop through each unverified user and delete their information
        foreach ($unverifiedUsers as $user) {
            // Delete the user's profile photo and profile photo profile from storage (if applicable)
            if ($user->photo) {
                Storage::delete($user->photo);
            }
            if ($user->photo_profile) {
                Storage::delete($user->photo_profile);
            }

            // Delete the user's record from the users table
            $user->delete();
        }

        // Output a message indicating the number of unverified users deleted
        $count = count($unverifiedUsers);
        $this->info("Deleted $count unverified users.");
    }


}
