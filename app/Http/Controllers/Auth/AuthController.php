<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function handleGoogleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->user();
            

            $userExist = User::where('email', $user->id)->first();

            if ($userExist){
                // Auth::login($userExist);
                auth()->login($userExist, true);


                return redirect()->route('dashboard');
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'oauth_id' => $user->id,
                    'oauth_type' => 'google',
                    'password' => Hash::make($user->id)
                ]);

                Auth::login($newUser);

        

                return redirect()->route('dashboard');
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    // public function intro()
    // {
    //     return "All is working";
    // }
}
