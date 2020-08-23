<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $users = User::all();
        $this->sendResponse($users, null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        // get back the object to update
        $user = User::find($id);
        // validate form data
        $request->validate([
            'name' => 'bail|required|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email,' .$id
            ],
            'banned' => 'required|boolean',
            'role' => 'required|regex:/^[12]$/'
        ]);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->banned=$request->banned;
        $user->role=$request->role;
        // save update on users table
        $user->save();
        $this->sendResponse($user, "User updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
         // get back the object to delete
         $user = User::find($id);
         $user->delete();
         $this->sendResponse($user, "User deleted successfully");
    }
}
