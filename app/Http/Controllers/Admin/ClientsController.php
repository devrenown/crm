<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserType;
use App\Models\ClientDetail;
use Illuminate\Http\Request;
use App\DataTables\ClientDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\Traits\uploadFile;

class ClientsController extends Controller
{
    use uploadFile;

    public $tenant;

    public function __construct ()
    {
        $this->tenant = app()->bound('tenant') ? app('tenant') : null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $pageTitle = __("Clients");
        $clients = User::where('type', UserType::CLIENT)->paginate(12);
        return view('pages.clients.index', compact(
            'pageTitle',
            'clients'
        ));

    }

    public function list(ClientDataTable $dataTable)
    {
        $pageTitle = __("Clients");
        return $dataTable->render('pages.clients.list', compact(
            'pageTitle',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'middlename' => 'nullable|string',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,except,id',
            'password' => 'required|string|confirmed',
        ]);

        $fileName = null;
        if ($request->hasFile('avatar')) {

            $path       = $this->tenant->id . '/clients/';
            $imageName  = time() . '.' . $request->avatar->extension();

            $fileName   = self::upload($request->avatar, $path);
            // $request->avatar->move(public_path('storage/users'), $imageName);
        }
        $status = $request->status == 'on' ? true : false;

        $user = User::create([
            'type'          => UserType::CLIENT,
            'firstname'     => $request->firstname,
            'middlename'    => $request->middlename,
            'lastname'      => $request->lastname,
            'email'         => $request->email,
            // 'username'      => $request->username,
            'address'       => $request->address,
            'country'       => $request->country_name,
            'country_code'  => $request->country_code,
            'dial_code'     => $request->dial_code,
            'phone'         => $request->phone,
            'avatar'        => $fileName,
            'created_by'    => auth()->user()->id,
            'is_active'     => $status,
            'password'      => Hash::make($request->password)
        ]);
        if(!empty($user)){
            $user->assignRole(UserType::CLIENT);
            $totalEmployees = User::where('type', UserType::CLIENT)->where('is_active', true)->count();
            $cltId = "CLT-" . pad_zeros(($totalEmployees + 1));
            ClientDetail::create([
                'clt_id' => $cltId,
                'user_id' => $user->id,
            ]);
        }
        $notification = notify(__('Client has been created'));
        return back()->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $client)
    {
        try{
            $user = User::findOrFail(Crypt::decrypt($client));
            $pageTitle = __("Client Profile");
            return view('pages.clients.show',compact(
                'user','pageTitle'
            ));
        }
        catch(\Exception $e){
            $notification = notify(__($e->getMessage()),'error');
            return back()->with($notification);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $client = User::findOrFail(Crypt::decrypt($id));
            return view('pages.clients.edit',compact(
                'client'
            ));
        }catch(\Exception $e){
            $notification = notify(__($e->getMessage()),'error');
            return back()->with($notification);
        }
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $client)
    {
        $user = $client;
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email'    => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|confirmed',
        ]);

        $status = $request->status == 'on' ? true : false;

        $fileName = $user->avatar;

        if ($request->hasFile('avatar')) {
            $path       = $this->tenant->id . '/clients/';
            $imageName  = time() . '.' . $request->avatar->extension();

            $fileName   = self::upload($request->avatar, $path, !empty($user->avatar) ?? null);
            // $request->avatar->move(public_path('storage/users'), $imageName);
        }
        $user->update([
            'firstname' => $request->firstname ?? $user->firstname,
            'middlename' => $request->middlename ?? $user->middlename,
            'lastname' => $request->lastname ?? $user->lastname,
            'email' => $request->email ?? $user->email,
            // 'username' => $request->username ?? $user->username,
            'address' => $request->address ?? $user->address,
            'country' => $request->country_name ?? $user->country,
            'country_code' => $request->country_code ?? $user->country_code,
            'dial_code' => $request->dial_code ?? $user->dial_code,
            'phone' => $request->phone ?? $user->phone,
            'avatar' => $fileName,
            'is_active' => $status,
            'password' => !empty($request->password) ? Hash::make($request->password) : $user->password
        ]);
        if(!$user->hasRole(UserType::CLIENT)){
            $user->assignRole(UserType::CLIENT);
        }
        $notification = notify(__('Client has been updated'));
        return back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $client)
    {
        $path = $this->tenant->id . '/clients/';

        if ($client->avatar) {
            self::delete($client->avatar, $path);
        }
        
        $client->delete();
        $notification = notify(__('Client has been deleted'));
        return redirect()->route('clients.index')->with($notification);
    }
}
