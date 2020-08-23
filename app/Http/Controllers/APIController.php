<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegistrationFormRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class APIController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegistrationFormRequest $request) {
        $token = $this->userService->register($request);
        return $this->sendResponse(compact('token'), "Registered success");
    }

    public function login(Request $request) {
        try {
            $token = $this->userService->login($request);
        } catch (\Exception $e) {
            $this->sendError("Login failed", 401);
        }
        $user = $this->userService->getUser();
        return $this->sendResponse(compact('token', 'user'), "Login with JWT succeeded");
    }

    public function logout(LogoutRequest $request)
    {
        try {
            JWTAuth::invalidate($request->input('token'));
            return $this->sendResponse(null, 'User logged out successfully');
        } catch (JWTException $exception) {
            return $this->sendError('User logged out successfully', null, 500);
        }
    }

     /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->sendResponse(auth()->refresh(), "Token refreshed");
    }
}
