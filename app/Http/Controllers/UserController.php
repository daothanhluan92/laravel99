<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function login()
    {
        return view('layouts.login');
    }

    public function auth(Request $request)
    {
        $credential = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','min:6']
        ]);
        if (Auth::attempt($credential)){
            $request->session()->regenerate();
            return redirect()->intended();

        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register()
    {
        return view('layouts.register');
    }
    public function store(StorePostRequest $request)
    {

        $users = [
            $request->input('name'),
            $request->input('email'),
            Hash::make($request->input('password'))
        ];

        $user = User::create($request->validated());
//        DB::insert('insert into users(name,email,password) values (?,?,?)',$users);
        Auth::login($user);
        return redirect('/');

    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }


}
