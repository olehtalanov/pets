<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\InvalidProviderException;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Response;

class SocialiteController extends Controller
{
    public function link(string $provider): RedirectResponse
    {
        if (!in_array($provider, config('services.auth_providers'), true)) {
            throw new InvalidProviderException();
        }

        return Socialite::driver($provider)->redirect();
    }

    public function store(Request $request, string $provider): JsonResponse
    {
        if (!in_array($provider, config('services.auth_providers'), true)) {
            throw new InvalidProviderException();
        }

        $providerUser = Socialite::driver($provider)->user();

        /** @var User $user */
        $user = User::updateOrCreate([
            'email' => $providerUser->email,
        ], [
            'name' => $providerUser->name,
            'provider' => $provider,
            'provider_id' => $providerUser->id,
            'provider_token' => $providerUser->token,
            'provider_refresh_token' => $providerUser->refreshToken,
        ]);

        Auth::login($user);

        return Response::json([
            'user' => new UserResource($user),
            'token' => $user->createToken(
                $request->input('token_name', 'app')
            )->plainTextToken
        ]);
    }
}
