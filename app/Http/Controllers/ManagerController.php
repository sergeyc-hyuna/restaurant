<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class ManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsersList()
    {
        $users = User::where('role', '<>', 'manager')->get();
        return view('manager.index',['users' => $users]);
    }

    public function fireEmployee(Request $request)
    {
        $user = User::find($request->user_id);
        $user->delete();

        return $user;
    }

    public function changeEmployeePosition(Request $request)
    {
        $data = [
            'role' => $request->position
        ];
        $user = User::find($request->user_id);
        $user->fill($data);
        return ($user->update()) ? $user : false;
    }
}
