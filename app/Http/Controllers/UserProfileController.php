<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmployeeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use App\Traits\uploadFile;
//use App\Traits\SecureFileUpload;

class UserProfileController extends Controller
{
    use uploadFile;

    public $tenant;

    public function __construct()
    {
        $this->tenant = app('tenant');
    }

    public function index()
    {
        $user       = auth()->user();
        $employee   = EmployeeDetail::where('user_id', $user->id)->first();
        $pageTitle  = __('Profile');
        return view('pages.profile', compact(
            'user',
            'employee',
            'pageTitle'
        ));
    }

    public function edit(Request $request)
    {
        $user = auth()->user();
        return view('pages.profile.edit', compact(
            'user'
        ));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => [
            'required',
            'email',
            Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);
        
        $user = User::findOrFail(auth()->user()->id);
        
        $fileName = $user->avatar;
        if ($request->hasFile('avatar')) {

            $path =  $this->tenant->id . '/'. $user->id . '/';
            $fileName   = self::upload($request->file('avatar'), $path, $user->avatar ?? '');
        }
        $user->update([
            'firstname' => $request->firstname ?? $user->firstname,
            'middlename' => $request->middlename ?? $user->middlename,
            'lastname' => $request->lastname ?? $user->lastname,
            'email' => $request->email ?? $user->email,
            'username' => $request->username ?? $user->username,
            'address' => $request->address ?? $user->address,
            'country' => $request->country_name ?? $user->country,
            'country_code' => $request->country_code ?? $user->country_code,
            'dial_code' => $request->dial_code ?? $user->dial_code,
            'phone' => $request->phone ?? $user->phone,
            'avatar' => $fileName,
        ]);
        $notification = notify(__('Profile has been updated'));
        return redirect()->route('profile')->with($notification);
    }

    public function updatePassword (Request $request)
    {
        // dd($request->all());
        
        $validate = $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|confirmed',
        ]);

        if (!$validate) {
           $notification = notify(__('Please fill required fields !'));
           return back()->with($notification);
        }

        $userId = $request->user_id;
        $user   = User::find($userId);

        if (!Hash::check($validate['current_password'], $user->password)) {
            $notification = notify(__('Password does not mached !'));
           return back()->with($notification);
        }

        $passwordHash = Hash::make($validate['new_password']);

        $user->update([
            'password' => $passwordHash
        ]);

        $notification = notify(__('Password Updated Successfully'));
        return back()->with($notification);
    }
}
