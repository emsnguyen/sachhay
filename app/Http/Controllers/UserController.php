<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    //$sortType, $sortDirection, $pageIndex, $pageSize
    public function index(Request $request)
    {
        $users = $this->userService->all($request);
        return $this->sendResponse($users, "All users");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param int $id
     * @return void
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->update($request, $id);
        return $this->sendResponse($user, "User updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
         $this->userService->delete($id);
         return $this->sendResponse($id, "User deleted successfully");
    }
}
