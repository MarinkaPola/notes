<?php

namespace App\Http\Controllers;


use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider): RedirectResponse
    {
        $providerModel = Socialite::driver($provider);

        if ($providerModel && method_exists($providerModel, 'stateless')) {
            return $providerModel->stateless()->redirect();
        }
        return redirect('/');
    }

    /**
     * Obtain the user information from Google.
     *
     * @param $provider
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback($provider)
    {
        $providerModel = Socialite::driver($provider);

        if ($providerModel && method_exists($providerModel, 'stateless')) {
            try {
                $user = $providerModel->stateless()->user();
            } catch (\Exception $e) {
                return redirect('/login');
            }

            // check if they're an existing user
            $existingUser = User::whereEmail($user->email)->first();
            if ($existingUser) {
                // log them in
                auth()->login($existingUser, true);
            } else {
                // create a new user
                $newUser = new User;
                $newUser->name = $user->name;
                $newUser->email = $user->email;
                $newUser->google_id = $user->id;
                $newUser->avatar = $user->avatar;
                $newUser->avatar_original = $user->avatar_original;
                $newUser->save();
                auth()->login($newUser, true);
            }

            return redirect()->to('/dashboard');
        }
        return redirect('/');
    }
}
