<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationFormRequest;
use App\Repositories\UserRepository;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class APIController extends Controller
{
    /**
     * @var bool
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function showLoginForm() {
        return view('auth/login');
    }
    public function showRegisterForm() {
        return view('auth/register');
    }

    public function register(RegistrationFormRequest $request) {
        $user = $this->userRepository->create([$request]);
        $token = JWTAuth::fromUser($user);

        return $this->sendResponse(compact('token'), "Registered success");
    }

    public function login(Request $request) {
        $credentials = $request->only('username', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->sendError('invalid username and password', null, 401);
            }
        } catch (Exception $e) {
            return $this->sendError('could not create token', null, 500);
        }
        $user = JWTAuth::user();
        return $this->sendResponse(compact('token', 'user'), "Login with JWT succeeded");
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            return $this->sendResponse(null, 'User logged out successfully');
        } catch (JWTException $exception) {
            return $this->sendError('User logged out successfully', null, 500);
        }
    }

     /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->sendResponse(auth()->refresh(), "Token refreshed");
    }
}
