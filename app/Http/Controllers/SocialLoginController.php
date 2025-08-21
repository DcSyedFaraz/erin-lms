<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]
        );

        // Assign default role if not assigned
        if (!$user->roles->count()) {
            $user->assignRole('Learner');
        }

        Auth::login($user, true);

        return redirect()->intended('/');
    }
}
