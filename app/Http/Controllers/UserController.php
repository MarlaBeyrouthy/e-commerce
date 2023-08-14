<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountConfirmationMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;



class UserController extends Controller
{

    public function sendVerificationCode(Request $request)
    {
        // Validate the input data
        $this->validate($request, [
            'email' => 'required|email|unique:users,email'
        ]);


            $code=\random_int(100000,999999);



        // Store the email and verification code in the session
        $request->session()->put('email', $request->input('email'));
        $request->session()->put('verification_code',$code );



        Mail::to($request->input('email'))->send(new AccountConfirmationMail);

        return response()->json([
           "message"=>"A verification code has been sent to your email."], 200);

    }

    public function verifyCode(Request $request)
    {
        // Validate the input data
        $this->validate($request, [
            'verification_code' => 'required|digits:6'
        ]);

        // Retrieve the email and verification code from the session
        $email = $request->session()->get('email');
        $code = $request->session()->get('verification_code');

        // Check if the verification code submitted by the user is valid
        if ($request->input('verification_code') == $code) {
            // Store the email in the session again
            $request->session()->put('email', $email);

            return response()->json(['message' => 'Email address verified successfully.'], 200);
        } else {
            return response()->json(['error' => 'Invalid verification code.'], 400);
        }
    }

    public function registerUser(Request $request) {
        // Validate the input data
        $this->validate( $request, [
            'name'     => 'required',
            'phone'    => 'required',
            'permission_id'=> 'required|in:1,3', // Only allow permission IDs 1 or 3
            'password' => 'required|string|min:8|confirmed',
            "address"=>"required",
            'city_id'  => 'required|exists:cities,id',
            'place_id' => 'required|exists:places,id',

        ] );

        // Retrieve the email from the session
        $email = $request->session()->get('email');

        // Create a new user record in the database
   /*     $user = new User;
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->email = $email;
        $user->password = bcrypt($request->input('password'));*/
        $user = new User();
        $user->name=$request->name;
        $user->address=$request->address;
        $user->photo=$request->photo;
        $user->photo_profile=$request->photo_profile;
        $user->email = $email;
        $user->password=bcrypt($request->password);
        $user->phone=$request->phone;
        $user->bio=$request->bio;
        $user->contact=$request->contact;
        $user->city_id = $request->city_id;
        $user->place_id =$request->place_id;
        $user->permission_id =$request->permission_id;



        $photo = $request->file('photo');
        if ($request->hasFile('photo')) {
            $photoExtension = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/users_photo'), $photoExtension);
            $photoPath = 'uploads/users_photo/' . $photoExtension;
            $user->photo = $photoPath;
        }

        $photoProfile = $request->file('photo_profile');
        if ($request->hasFile('photo_profile')) {
            $photoProfileExtension = time() . '.' . $photoProfile->getClientOriginalExtension();
            $photoProfile->move(public_path('uploads/users_profile'), $photoProfileExtension);
            $photoProfilePath = 'uploads/users_profile/' . $photoProfileExtension;
            $user->photo_profile = $photoProfilePath;
        }


        $user->save();
        $token=$user->createToken("auth_token")->accessToken;


        // Clear the session data
        $request->session()->forget('email');

        return response()->json([
            'message' => 'User registered successfully.',
            "access_token"=>$token,
        ], 200);
    }

    public function sendConfirmationCode(Request $request)
    {
        //validation
        $request->validate([
            "name"=>"required",
            "phone"=>"required",
            "address"=>"required",
            //"email"=>"required | email | unique :users",
            'email' => 'required|email|unique:users,email,NULL,id,verified,1',
            "password"=>"required|string|min:8|confirmed",

        ]);

        //check if email already exists
        $user = User::where('email', $request->email)->first();
        if($user && !$user->verified){
            //update user record with new registration details
            $user->name=$request->name;
            $user->address=$request->address;
            $user->photo=$request->photo;
            $user->photo_profile=$request->photo_profile;
            $user->password=bcrypt($request->password);
            $user->phone=$request->phone;
            $user->bio=$request->bio;

            //generate new verification code and send email
            do{
                $code=\random_int(10000,99999);
            }while( User::where('verification_code',$code)->exists());
            $user->verification_code=$code;
            $user->save();
           // Mail::to($request->email)->send(new AccountConfirmationMail($code));
          //  Mail::to($user->email)->send(new AccountConfirmationMail($code,$user));


            return response()->json([
                "status"=>0,
                "message"=>"Email already exists. A new verification code has been sent to your email address."
            ]);
        }



        if ($user && $user->verified) {
            return response()->json([
                'message' => 'The email has already been taken and verified.',
            ], 409); // 409 status code indicates conflict/error
        }


        //generate unique verification code and send email
        do{
            $code=\random_int(10000,99999);
        }while( User::where('verification_code',$code)->exists());

        //create user
        $user = new User();
        $user->name=$request->name;
        $user->address=$request->address;
        $user->photo=$request->photo;
        $user->photo_profile=$request->photo_profile;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->phone=$request->phone;
        $user->verification_code=$code;
        $user->bio=$request->bio;



        /* $photo=$request->file('photo');
         if ($request->hasFile('photo'))
         {
             $extension=time(). '.' .$photo->getClientOriginalExtension();
             $photo->move(public_path('uploads/users_photo'),$extension);
             $extension='uploads/users_photo/'.$extension;

         }

         $photo=$request->file('photo_profile');
         if ($request->hasFile('photo_profile'))
         {
             $extension=time(). '.' .$photo->getClientOriginalExtension();
             $photo->move(public_path('uploads/users_profile'),$extension);
             $extension='uploads/users_profile/'.$extension;

         }*/



        $photo = $request->file('photo');
        if ($request->hasFile('photo')) {
            $photoExtension = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/users_photo'), $photoExtension);
            $photoPath = 'uploads/users_photo/' . $photoExtension;
            $user->photo = $photoPath;
        }

        $photoProfile = $request->file('photo_profile');
        if ($request->hasFile('photo_profile')) {
            $photoProfileExtension = time() . '.' . $photoProfile->getClientOriginalExtension();
            $photoProfile->move(public_path('uploads/users_profile'), $photoProfileExtension);
            $photoProfilePath = 'uploads/users_profile/' . $photoProfileExtension;
            $user->photo_profile = $photoProfilePath;
        }


       // $photoProfile->store();
       //  $photo->getError();


        $user->save();
       // Mail::to($user->email)->send(new AccountConfirmationMail($code,$user));


        //send response
        return response()->json([
            "status"=>1,
            "message"=>"User Registered Successfully",
           // "user"=>$user // Add this line to return the user object

        ]);
    }

    public function confirmRegistration(Request $request)
    {
        $request->validate([
            'verification_code' => 'required'
        ]);

        $user = User::where('verification_code', $request->verification_code)->first();

        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'Verification failed'
            ]);
        }

        if ($user->verification_code !== $request->verification_code) {
            return response()->json([
                'status' => 0,
                'message' => 'Verification failed'
            ]);
        }

        $user->verified = true;
        $user->save();

        $token=$user->createToken("auth_token")->accessToken;


        return response()->json([
            'status' => 1,
            'message' => 'Verification successful',
            "access_token"=>$token

        ]);
    }

    //login-post
    public function login(Request $request)
    {
        //validation
        $login_data=$request->validate([
            "email"=>"required",
            "password"=>"required",
        ]);

        //validate author data
        if (!auth()->attempt($login_data)  ){
            return response()->json([
                "status"=>false,
                "message"=>"invalid contents "

            ]);
        }

       /* $user = auth()->user();
        if (!$user->verified) {
            return response()->json([
                "status" => false,
                "message" => "User not verified"
            ]);
        }*/

        //token
        $token=auth()->user()->createToken("auth_token")->accessToken;
        //send response
        return \response()->json([
            "status"=>true,
            "message"=>"user logged in successfully",
            "access_token"=>$token
        ]);

    }

    public function myProfile()
    {
        $user_data = auth()->user()->makeHidden(['verification_code', 'verified']);
        return response()->json([
            "status" => true,
            "message" => "user data",
            "data" => $user_data
        ]);
    }

    public function getProfile($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }
        else {
            $user->makeHidden(['verification_code', 'verified','email']);
        }

        return response()->json([
            'status' => true,
            'message' => 'User profile data',
            'data' => $user
        ]);
    }

    //logout-get
    public function logout()
    {

        auth()->user()->token()->delete();

        return \response()->json([
            "status"=>true,
            "message"=>"user log out successfully"
        ]);

    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Validate the input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'contact' => 'nullable|string|max:500',
            //  'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable',
            'photo' => 'nullable|image|max:2048',
            'photo_profile' => 'nullable|image|max:2048',
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update the user's profile information
        $user->name = $validatedData['name'];
        $user->phone = $validatedData['phone'];
        $user->bio = $validatedData['bio']?? $user->bio;
        $user->contact = $validatedData['contact']?? $user->contact;
        // $user->email = $validatedData['email'];
        $user->address = $validatedData['address']?? $user->address;

     /*   if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('public/profiles');
        }

        if ($request->hasFile('photo_profile')) {
            $user->photo = $request->file('photo')->store('public/profiles');
        }*/

        $photo = $request->file('photo');
        if ($request->hasFile('photo')) {
            $photoExtension = time() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/users_photo'), $photoExtension);
            $photoPath = 'uploads/users_photo/' . $photoExtension;
            $user->photo = $photoPath;
        }

        $photoProfile = $request->file('photo_profile');
        if ($request->hasFile('photo_profile')) {
            $photoProfileExtension = time() . '.' . $photoProfile->getClientOriginalExtension();
            $photoProfile->move(public_path('uploads/users_profile'), $photoProfileExtension);
            $photoProfilePath = 'uploads/users_profile/' . $photoProfileExtension;
            $user->photo_profile = $photoProfilePath;
        }

        // Update the user's password if a new password has been provided
        if (isset($validatedData['new_password'])) {
            $currentPassword = $validatedData['current_password'];
            $newPassword = $validatedData['new_password'];

            // Check if the current password is correct
            if (Hash::check($currentPassword, $user->password)) {
                // Hash and save the new password
                $user->password = Hash::make($newPassword);
            } else {
                // If the current password is incorrect, redirect with an error message
                return response()->json([
                   "message"=>'The current password is incorrect.'
                ],400);
            }
        }

        // Save the updated profile information and password
        $user->save();

        // Redirect the user to the profile page with a success message
        return response()->json([
           "message"=>'Your profile has been updated.'
        ],200);

    }

    public function checkPassword(Request $request)
    {
        $inputPassword  = $request->input( 'password' );
        $hashedPassword = auth()->user()->password;

        if ( Hash::check( $inputPassword, $hashedPassword ) ) {
            return response()->json( [
                'message' => 'Valid password'
            ] );
        } else {
            return response()->json( [
                'message' => 'Invalid password'
            ] );
        }
    }
}
