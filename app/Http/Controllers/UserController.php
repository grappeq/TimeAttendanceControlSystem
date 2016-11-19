<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use \App\User;
use Validator;

/**
 * User-related views controllers
 * @package App\Http\Controllers
 */
class UserController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show users list.
     *
     * @return Response
     */
    public function showNewUserForm() {
        return view('users/new');
    }

    /**
     * Handles user create POST request.
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'username' => 'required|max:255|unique:users',
                    'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return redirect('/users/new')
                            ->withInput()
                            ->withErrors($validator);
        }

        $request->session()->flash('message', "Użytkownik został utworzony!");
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);
        return redirect('/users/list');
    }

    /**
     * Show users list.
     *
     * @return Response
     */
    public function showUserList() {
        $users = User::orderBy('created_at', 'asc')->get();
        return view('users/userslist', [
            'users' => $users
        ]);
    }

}
