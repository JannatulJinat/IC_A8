<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginPage()
    {
        return view('webpage.login');
    }

    public function showSignupPage()
    {
        return view('webpage.register');
    }

    public function showNewsFeedPage()
    {
        return view('webpage.index');
    }

    public function showEditProfilePage()
    {
        return view('webpage.edit-profile');
    }

    public function authenticate(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ])->validate();

        if (Auth::attempt($credentials)) {
            return redirect('/newsfeed');
        }

        return back()->withErrors([
            'LoginError' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function saveUser(Request $request)
    {
        $userInfo = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],

        ])->validate();

        DB::table('users')->insert([
            'full_name' => $request->name,
            'user_name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/');
    }

    public function editUser(Request $request)
    {
        $userInfo = Validator::make($request->all(), [
            'first-name' => ['required', 'string', 'max:255'],
            'last-name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ])->validate();
        $user = DB::table('users')->where('id', Auth::id())->first();
        if ($user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'full_name' => $request->input('first-name').' '.$request->input('last-name'),
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'bio' => $request->bio,
                ]);

            return redirect('/newsfeed');
        }

        return redirect('/profile/edit');
    }

    public function profileView(Request $request)
    {
        $user = DB::table('users')->where('id', Auth::id())->first();
        $posts = DB::table('posts')
            ->where('user_id', Auth::id())
            ->get();
        return view('webpage.profile',  compact('user','posts'));
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect('/');
    }
}
