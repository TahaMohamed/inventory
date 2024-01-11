<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\HandleAuth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use HandleAuth;
    public function login(LoginRequest $request)
    {
        $user = $this->makeLogin($request, [User::SUPPLIER, User::CUSTOMER]);
        return ApiResponse::successResponse(
                data: UserResource::make($user),
                message: __('Welcome :User', ['user' => $user->name])
            );
    }

    public function logout(Request $request)
    {
        $user = \Auth::user();
//        $user->currentAccessToken()->delete();
        $user->tokens()->delete();
        \Auth::guard('sanctum')->forgetUser();
        return ApiResponse::successResponse(message: __('GoodBye!'));
    }
}
