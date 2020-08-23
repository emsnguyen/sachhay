<?php namespace App\Services;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $user = $this->userRepository->create([$request]);
        return JWTAuth::fromUser($user);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                throw new \Exception('invalid username or password', 401);
            }
        } catch (Exception $e) {
            throw new \Exception('could not create token', 500);
        }
        return $token;
    }

    public function getCurrentUser() {
        return JWTAuth::user();
    }

    public function all(Request $request)
    {
        return $this->userRepository->all();
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        return $this->userRepository->update([$request], $id);
    }

    public function delete(int $id)
    {
        $this->userRepository->delete($id);
    }
}
