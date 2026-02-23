<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as ValidatePassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeDetail;
use App\Enums\UserType;
use App\Traits\GenerateEmployeeCode;

class AuthController extends BaseController
{
    use GenerateEmployeeCode;

    public function __construct() 
    {
        $this->tenant = app('tenant');
    }

    public function signup (Request $request) 
    {
        $this->data['pageTitle'] = __('Register');
        return view("auth.signup", $this->data);
    }

    public function register (Request $request)
    {
        $validate = $request->validate([
            'full_name' => 'required|string|min:3',
            'email'     => 'required|email|unique:users',
            'phone'     => 'required|numeric',
            'password'  => ['required','confirmed', ValidatePassword::min(6)]
        ]);

        if (!$validate) {
            return redirect()->back();
        }

        $user = User::create([
            'firstname' => $validate['full_name'],
            'email'     => $validate['email'],
            'phone'     => $validate['phone'],
            'type'      => UserType::EMPLOYEE,
            'is_active' => 1,
            'password'  => Hash::make($validate['password']),
        ]);

        if (!$user) {
            return redirect()->back()->with('error', __('Something went wrong !'));
        }
        
        $user->assignRole(UserType::EMPLOYEE);
        // $totalEmployees = User::where('type', UserType::EMPLOYEE)->where('is_active', true)->count();
        // $empId = "EMP-" . pad_zeros(($totalEmployees + 1));

        $empId = $this->generateEmployeeCode($this->tenant);
        
        EmployeeDetail::create([
            'emp_id' => $empId,
            'user_id' => $user->id,
        ]);

        // Auth::login($user);

        return redirect()->route('login')->with('success', __('Registration successful!'));

    }

    public function login(Request $request)
    {
        $this->data['pageTitle'] = __('Login');

        if ($request->getHost() === env('PRIMARY_HOST')) {
            return view('auth.login', $this->data);
        }
        
        return redirect()->route('tenant.login');
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {
            if ($user->is_active === 1) {
                $credentials = $request->only('email', 'password');
                if (Auth::attempt($credentials)) {
                    
                    $user->update(['is_online' => true,]);

                    $activeRole = $user->active_role ?? $user->getRoleNames()->first();
                    session(['active_role' => $activeRole]);

                    if (!$user->active_role) {
                        $user->update(['active_role' => $activeRole]);
                    }

                    return redirect()->route('dashboard');
                }
                return back()->withErrors(['password' => 'Invalid Email or Password']);
            }
            return back()->withErrors(['email' => 'Your account is disabled please contact to admin.']);
        }
        return back()->withErrors(['email' => 'Account could not be found.']);
    }

    public function forgotPassword()
    {
        $this->data['pageTitle'] = __('Forgot Password');
        return view('auth.forgot-password', $this->data);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(notify(__($status)))
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(string $token)
    {
        $this->data['pageTitle'] = __('Reset Password');
        $this->data['token'] = $token;
        return view('auth.password-reset', $this->data);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with(notify(__($status)))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request)
    {
        auth()->user()->update([
            'is_online' => true,
        ]);
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
