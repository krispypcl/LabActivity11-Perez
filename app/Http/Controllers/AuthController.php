<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Simple manual login from Lab 9 (Breeze is Week 12)
class AuthController extends Controller
{
    // GET /login - just shows the form
    public function showLogin()
    {
        return view('auth.login');
    }

    // POST /login
    public function login(Request $request)
    {
        // take only the two fields we need (never $request->all())
        $credentials = $request->only('email', 'password');

        // Auth::attempt finds the user by email and checks the password against the stored hash
        if (Auth::attempt($credentials)) {
            // new session id after login so an old session id can't be reused (session fixation)
            $request->session()->regenerate();

            return redirect('/posts');
        }

        // wrong login: go back to the form with an error, and keep the email typed (not the password)
        return back()
            ->withErrors(['email' => 'Wrong email or password.'])
            ->onlyInput('email');
    }

    // POST /logout (a form with @csrf, not a link, so other sites can't log you out)
    public function logout(Request $request)
    {
        Auth::logout();

        // clear the session data and make a new CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/posts');
    }
}
