<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists with this google_id
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                // If no user found with this google_id, check by email
                $user = User::where('email', $googleUser->email)->first();
                
                if ($user) {
                    // If user exists with this email, update their google_id
                    $user->update([
                        'google_id' => $googleUser->id
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => Hash::make(rand(100000, 999999)), // Random password
                        'activate' => 1,
                    ]);
                }
            }
            
            // Login user
            Auth::login($user);
            
            return redirect()->route('home');
            
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        // Find the user
        $user = User::where('phone', $request->phone)
                    ->first();
        
        if (!$user) {
            return redirect()->route('user.login')->with('error','User Not found with these phone number');
        }
        
        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('user.login')->with('success','Password Change Successfully');

    }
    
    public function showLogin()
    {
    
        return view('user.login');
    }
    
    
    public function showRegister()
    {
    
        return view('user.register');
    }

    public function login(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'phone' => 'required|string',
        //     'password' => 'required|string',
        // ]);
        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput($request->except('password'));
        // }

        $request->validate([
            'phone'     => 'required|string|exists:users,phone',
            'password'  => 'required|string',
        ]);
        $credentials = $request->only('phone', 'password');

        if (Auth::attempt([
            'phone' => $request->input('phone'), 
            'password' => $request->input('password'), 
            'activate' => 1
        ])) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return redirect()->back()
            ->withErrors(['phone' => __('messages.auth_failed')])
            ->withInput($request->except('password'));
    }

    public function register(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'name'      => 'required|string|max:255',
        //     'phone'     => 'required|string|unique:users,phone|string|max:20',
        //     'email'     => 'required|string|unique:users,email|string|max:20',
        //     'password'  => 'required|string',
        //     'grade'     => 'nullable|required_if:role_name,student|in:1,2,3,4,5,6,7,8,9',
        //     'role_name' => 'nullable|in:student,parent,teacher',
        // ]);
        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput($request->except('password'));
        // }
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|unique:users,phone|string|max:20',
            'email'     => 'required|string|unique:users,email|string|max:20',
            'password'  => 'required|string',
            'grade'     => 'nullable|required_if:role_name,student|in:1,2,3,4,5,6,7,8,9',
            'role_name' => 'nullable|in:student,parent,teacher',
        ]);
        $user = User::create([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_name' => $request->role_name,
            'clas_id'   => $request->grade,
            'activate'  => 1,
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
