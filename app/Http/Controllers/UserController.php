<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountConfirmationMail;
use Illuminate\Support\Str;


class UserController extends Controller
{
    //register-post
   /* public function register(Request $request)
    {

        //validation
        $request->validate([
            "name"=>"required",
            "phone"=>"required",
            "address"=>"required",
            "email"=>"required | email | unique :users",
            "password"=>"required | confirmed"
        ]);


        $code=\random_int(10000,99999);
        Mail::to($request->email)->send(new AccountConfirmationMail($code));



        //create

        $user = new User();

        $user->name=$request->name;
        $user->address=$request->address;
        $user->photo=$request->photo;
        $user->photo_profile=$request->photo_profile;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->phone=$request->phone;


        $photo=$request->file('photo');
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

        }
        $user->save();



        //send response

        return response()->json([
            "status"=>1,
            "message"=>"User Registered Successfully",
            "code" => $code
        ]);

    }

    public function verify(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|digits:5',
        ]);

        // Find the user with the given email
        $user = User::where('email', $request->email)->first();

        // If the user doesn't exist, return an error response
        if (!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'User not found',
            ]);
        }

        // If the user is already verified, return an error response
        if ($user->verified) {
            return response()->json([
                'status' => 0,
                'message' => 'User already verified',
            ]);
        }

        // If the verification code doesn't match, return an error response
        if ($user->verification_code != $request->verification_code) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid verification code',
            ]);
        }

        // Update the user's verified field to true
        $user->verified = true;
        $user->save();

        // Return a success response
        return response()->json([
            'status' => 1,
            'message' => 'User verified successfully',
        ]);
    }*/

    public function sendConfirmationCode(Request $request)
    {
        //validation
        $request->validate([
            "name"=>"required",
            "phone"=>"required",
            "address"=>"required",
            //"email"=>"required | email | unique :users",
            'email' => 'required|email|unique:users,email,NULL,id,verified,1',

            "password"=>"required | confirmed"
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
            //generate new verification code and send email
            do{
                $code=\random_int(10000,99999);
            }while( User::where('verification_code',$code)->exists());
            $user->verification_code=$code;
            $user->save();
            Mail::to($request->email)->send(new AccountConfirmationMail($code));

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
        Mail::to($request->email)->send(new AccountConfirmationMail($code));


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

        $user = auth()->user();
        if (!$user->verified) {
            return response()->json([
                "status" => false,
                "message" => "User not verified"
            ]);
        }

        //token
        $token=auth()->user()->createToken("auth_token")->accessToken;
        //send response
        return \response()->json([
            "status"=>true,
            "message"=>"user logged in successfully",
            "access_token"=>$token
        ]);

    }

    //profile-get
    public function profile()
    {
        $user_data=auth()->user();
        return \response()->json([
            "status"=>true,
            "message"=>"user data",
            "data"=>$user_data
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





}
