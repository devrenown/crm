<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function login()
    {
        // If already logged in
        if (Auth::check() && Auth::user()->hasRole('Blog Writer')) {
            return redirect()->route('blog.index');
        }

        return view('blog::auth.login');
    }

    /**
     * Handle login request
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        // Attempt login
        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            
            // if (!$user->hasRole('Blog Writer')) {

            //     Auth::logout();

            //     return redirect()
            //         ->route('blog.login')
            //         ->with('error', 'Unauthorized access.');
            // }

            $request->session()->regenerate();

            return redirect()
                ->route('blog.index')
                ->with('success', 'Login successful.');
        }

        return redirect()
            ->back()
            ->with('error', 'Invalid credentials.')
            ->withInput();
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('blog.login')
            ->with('success', 'Logged out successfully.');
    }
}