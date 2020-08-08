<?php

namespace App\Http\Controllers;

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
    public function showLoginForm() {
        return view('auth/login');
    }
    public function showRegisterForm() {
        return view('auth/register');
    }

    public function register(Request $request) {
        $validator = Validator::make($request -> all(),[
            'email' => 'required|string|email|max:255|unique:users',
            'username'=> 'required|unique:users',
            'name'=> 'required',
            'password'=> 'required'
           ]);

           if ($validator -> fails()) {
               return $this->sendError("Validation fails", $validator->errors(), 401);
           }

           User::create([
               'name' => $request->get('name'),
               'username'=> $request->get('username'),
               'email' => $request->get('email'),
               'password'=> bcrypt($request->get('password')),
           ]);
           $user = User::first();
           $token = JWTAuth::fromUser($user);

           return $this->sendResponse(compact('token'), "Registered success");
    }

    public function login(Request $request) {
        $credentials = $request->only('username', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                // setcookie('token', $token, time() + (86400 * 30), "/");
                // return redirect("dashboard/books")->with('token', $token);
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
}
