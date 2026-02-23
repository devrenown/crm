<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemoRequest;
use App\Models\Contact;

class FrontController extends Controller
{
   public $view = 'pages.front.blocks.';

   public function saveRequest (Request $request)
   {

    $validate = $request->validate([
        'name'                  => 'required|string|min:3',
        'organization'          => 'required|string',
        'company_size'          => 'required',
        'email'                 => 'required|email|string',
        'contact'               => 'required|numeric',
        'g-recaptcha-response'  => 'required',
    ]);

    if (!$validate) {
        return response()->json([
            'success' => false,
            'msg'     => 'Please Fill Required Fields !',
        ]);
    }

    $create = DemoRequest::create([
        'name'          => $validate['name'],
        'organization'  => $validate['organization'],
        'size'          => $validate['company_size'],
        'email'         => $validate['email'],
        'contact'       => $validate['contact'],
        'additional'    => $request->additional
    ]);

    if (!$create) {
        return response()->json([
            'success' => false,
            'msg'     => 'Something Went Wrong !',
        ]);
    }

    return response()->json([
        'success' => true,
        'msg'     => 'Request Sent Successfully',
    ]);

   }

   public function saveContact (Request $request)
   {

    $validate = $request->validate([
        'name'                  => 'required|string|min:3',
        'email'                 => 'required|email|string',
        'phone'                 => 'required|numeric',
        'message'               => 'nullable|string',
        // 'g-recaptcha-response'  => 'required',
    ]);

    if (!$validate) {
        return response()->json([
            'success' => false,
            'msg'     => 'Please Fill Required Fields !',
        ]);
    }

    $create = Contact::create([
        'name'          => $validate['name'],
        'email'         => $validate['email'],
        'phone'         => $validate['phone'],
        'message'       => $validate['message']
    ]);

    if (!$create) {
        return response()->json([
            'success' => false,
            'msg'     => 'Something Went Wrong !',
        ]);
    }

    return response()->json([
        'success' => true,
        'msg'     => 'Contact Sent Successfully',
    ]);

   }

   public function privacyPolicyView () 
   {
    return view($this->view . 'privacy-policy');
   } 

   public function termsConditionsView () 
   {
    return view($this->view . 'term_&_condition');
   } 

   public function switchCurrency (Request $request)
   {
    $currency = $request->currency;
   }

}
