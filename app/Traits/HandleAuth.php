<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

trait HandleAuth
{
    private array $credentials = [];


    public function makeLogin(Request $request, array $userTypes): User
    {
        $user = $this->getUser($request->login_key, $request->username, $userTypes);
        $this->throwIfUserDoesntExists($user);
//        $this->throwIfNotVerified($user, $request->login_key);
//        $this->throwIfBanned($user, false);
        $this->setCredentials($request);
        return $this->attempt($user);
    }

    protected function setCredentials($request):void
    {
        $this->credentials = [
            $request->login_key => $request->username,
            'password' => $request->password
        ];
    }

    protected function getCredentials(?string $key): string|array
    {
        return $key ? $this->credentials[$key] : $this->credentials;
    }

    protected function throwIfNotVerified(User $user, string $verifiedKey = 'phone'): void
    {
        if (!$user->isVerified($verifiedKey)) {
            ApiResponse::throwException(HttpResponseException::class, __("This :key unverified", ['key' => $verifiedKey]), Response::HTTP_FORBIDDEN);
        }
    }

    protected function throwIfBanned($user): void
    {
        if ($user->isBanned()) {
            ApiResponse::throwException(HttpResponseException::class, __('Banned account ":reason"', ['reason' => $user?->reason]), Response::HTTP_FORBIDDEN);
        }
    }

    protected function throwIfUserDoesntExists($user):void
    {
        if (! $user) {
            ApiResponse::throwException(HttpResponseException::class, __('auth.failed'));
        }
    }

    private function getUser($key, $value, $userTypes): ?User
    {
        return User::query()->whereIn('user_type', $userTypes)->firstWhere($key, $value);
    }

    protected function attempt(User $user): User
    {
        if (!Hash::check($this->getCredentials('password'), $user->password)){
           ApiResponse::throwException(HttpResponseException::class, __('Username or Password is incorrect'));
        }
        Auth::login($user);
        $user->_token = $user->createToken('Inventory')->plainTextToken;
        return $user;
    }
}
