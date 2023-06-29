<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\InvalidProviderException;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\FullResource;
use App\Models\User;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use OpenApi\Annotations as OA;
use Response;

class SocialiteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/auth/{provider}/redirect",
     *     tags={"Auth"},
     *     summary="Get OAuth redirect link.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\Schema(type="string")
     *     )
     * )
     */
    public function link(string $provider): string
    {
        if (!in_array($provider, config('services.auth_providers'), true)) {
            throw new InvalidProviderException();
        }

        return Socialite::driver($provider)->redirect()->getTargetUrl();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/{provider}/callback",
     *     tags={"Auth"},
     *     summary="Authenticate user.",
     *
     *     @OA\Response(response=200, description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="token", type="string"),
     *                 @OA\Property(property="profile", type="object", ref="#/components/schemas/UserFullResource"),
     *             ),
     *         )
     *     )
     * )
     */
    public function store(Request $request, string $provider): JsonResponse
    {
        if (!in_array($provider, config('services.auth_providers'), true)) {
            throw new InvalidProviderException();
        }

        $providerUser = Socialite::driver($provider)->user();

        $name = explode(' ', $providerUser->getName());

        $firstName = $name[0];
        $lastName = $name[0];

        if (count($name) > 1) {
            unset($name[0]);
            $lastName = implode(' ', $name);
        }

        /** @var User $user */
        $user = User::updateOrCreate([
            'email' => $providerUser->getEmail(),
        ], [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'provider' => $provider,
            'provider_id' => $providerUser->getId(),
            'provider_token' => $providerUser->token,
            'provider_refresh_token' => $providerUser->refreshToken,
        ]);

        Auth::login($user);

        return Response::json([
            'token' => $user->createToken($request->input('token_name', $provider))->plainTextToken,
            'profile' => new FullResource($user),
        ]);
    }
}
