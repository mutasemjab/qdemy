<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Notification;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WholeSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class AuthController extends Controller
{

     public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
            'password' => 'required',
            // Add other necessary validation rules for wholesale fields
        ], [
            'name.required' => 'The name field is required.',
            'phone.unique' => 'The phone has already been taken for the selected user type.',
            'email.unique' => 'The email has already been taken for the selected user type.',
        ]);


            $user = new User();
            $user->name = $request->get('name');
            $user->phone = $request->get('phone');
            $user->email = $request->get('email');
            $user->user_type = $request->get('user_type');
            $user->section_user_id = $request->get('section_user_id');
            $user->password = Hash::make($request->get('password'));
            $user->ip_address = $request->get('ip_address');
            if ($request->has('fcm_token')) {
                $user->fcm_token = $request->get('fcm_token');
            }
            if($user->save()){
            // Create wallet for the customer
                $wallet = new Wallet();
                $wallet->total = 0; // Set initial total to 0
                $wallet->user_id = $user->id;
                $wallet->save();
            }


            $accessToken = $user->createToken('authToken')->accessToken;
            return response(['user' => $user, 'token' => $accessToken], 201);


    }



    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email',
            'password' => 'required ',
            'ip_address' => 'nullable', // Ensure IP address is provided

        ], [
            'email.required_without' => 'The email field is required when phone is not present.',
            'email.email' => 'The email must be a valid email address.',
            'phone.required_without' => 'The phone field is required when email is not present.',
            'password.required' => 'The password field is required.',
        ]);

        $user = null;
        $isAdmin = false;

        if ($request->email) {
            $user = User::where('email', $request->email)->first();

            // Check in the admins table if the user is not found in users
            if (!$user) {
                $user = Admin::where('email', $request->email)->first();
                if ($user) {
                    $isAdmin = true; // Mark as admin if found in the admins table
                    $user->update([
                        'fcm_token' => $request->fcm_token,
                    ]);
                }
            }
        } else {
            $user = User::where('phone', $request->phone)->first();
        }

        if (!$user) {
            return response(["message" => "User not found."], 404);
        }

        if ($user->active == 2) {
            return response(['errors' => ['Your account has been deleted']], 404);
        }

        // Check the password based on whether the user is a regular user or an admin
        if (!Hash::check($request->password, $user->password)) {
            return response(['message' => 'Invalid credentials'], 401);
        }

        if (!$isAdmin && isset($request->ip_address) && $user->ip_address !== $request->ip_address) {
            return response(['message' => 'IP address mismatch. Login not allowed.'], 401);
        }

        $accessToken = $user->createToken('authToken')->accessToken;

        if (isset($request->fcm_token)) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

        return response(['user' => $user, 'is_admin' => $isAdmin, 'token' => $accessToken], 200);
    }



   public function updateProfile(Request $request){


       $user =  auth()->user();

       if(isset($request->password)){
           $user->password = Hash::make($request->password);
       }

       if ($request->has('photo')) {
        $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
        $user->photo = $the_file_path;
     }

       if($user->save()){
           return response(['message'=>['Your setting has been changed'],'user'=>$user]);
       }else{
           return response(['errors'=>['There is something wrong']],402);
       }
   }

   public function mobileVerified(Request $request)
   {

       $user = auth()->user();
       if(!$user){
       return response(['errors' => ['Unauthenticated']], 402);

       }

       $user->is_verified = 1;

       if ($user->save()) {
           return response(['message' => ['Your setting has been changed'], 'user' => $user], 200);
       } else {
           return response(['errors' => ['There is something wrong']], 402);
       }
   }




}

