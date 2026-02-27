<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserFamilyInfo;
use Illuminate\Http\Request;
use App\Traits\uploadFile;

class FamilyInfoController extends Controller
{
    use uploadFile;

    public $tenant;

    public function __construct ()
    {
        $this->tenant = app('tenant');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user_id = $request->user;
        return view('pages.family-information.create',compact(
            'user_id'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'relationship' => 'required',
            'user' => 'required',
            'image' => 'nullable|file|image'
        ]);
        $fileName = null;
        if($request->hasFile('image')){
            $path = $this->tenant->id . '/' . $request->user . '/family-members/'; 
            $upload = self::upload(
                $request->file('image'),
                $path,
            );

            $fileName = $upload;
        }
        UserFamilyInfo::create([
            'user_id'       => $request->user,
            'name'          => $request->name,
            'relationship'  => $request->relationship,
            'dob'           => $request->dob,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'picture'       => $fileName,
        ]);
        $notification = notify(__('Family information has been added'));
        return back()->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserFamilyInfo $family_information)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserFamilyInfo $family_information)
    {
        $member = $family_information;
        return view('pages.family-information.edit',compact(
            'member'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserFamilyInfo $family_information)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'relationship' => 'required',
            'user' => 'required',
            'image' => 'nullable|file|image'
        ]);
        $fileName = $family_information->picture;
        if($request->hasFile('image')){
            $path       = $this->tenant->id . '/' . $request->user . '/family-members/'; 
            $fileName   = self::upload($request->file('image'), $path, $family_information->picture ?? '');
        }
        $family_information->update([
            'user_id' => $request->user,
            'name' => $request->name,
            'relationship' => $request->relationship,
            'dob' => $request->dob,
            'phone' => $request->phone,
            'address' => $request->address,
            'picture' => $fileName,
        ]);
        $notification = notify(__('Family information has been updated'));
        return back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserFamilyInfo $family_information)
    {
        if ($family_information->picture) {
            $path = $this->tenant->id . '/' . $family_information->user_id . '/family-members/'; 
            self::delete($family_information->picture, $path);
        }

        $family_information->delete();
        $notification = notify(__("Family information has been deleted"));
        return back()->with($notification);
    }
}
