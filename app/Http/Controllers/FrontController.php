<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemoRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class FrontController extends Controller
{
   public $view = 'pages.front.blocks.';
   public $featureView = 'pages.front.features.';

   // public function demoRequest ()
   // {
   //   return view($this->view . 'demo-modal');
   // }

   public function saveRequest (Request $request)
   {

    $validate = $request->validate([
        'r_name'                  => 'required|string|min:3',
        'r_organization'          => 'required|string',
        'r_company_size'          => 'required',
        'r_email'                 => 'required|email|string',
        'r_contact'               => 'required|numeric',
        'g-recaptcha-response'    => 'required',
    ]);

    if (!$validate) {
        return response()->json([
            'success' => false,
            'msg'     => 'Please Fill Required Fields !',
        ]);
    }

    $create = DemoRequest::create([
        'name'          => $validate['r_name'],
        'organization'  => $validate['r_organization'],
        'size'          => $validate['r_company_size'],
        'email'         => $validate['r_email'],
        'contact'       => $validate['r_contact'],
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
        'message'               => 'nullable|string|max:500',
        'g-recaptcha-response'  => 'required',
    ]);

    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret'   => '6LdkPu0rAAAAALtp1F1GL324r-dz2ry-v54JDlRm',
        'response' => $request->input('g-recaptcha-response'),
        'remoteip' => $request->ip(),
    ]);

    $result = $response->json();

    if (!isset($result['success']) || $result['success'] !== true) {
        return response()->json([
            'success' => false,
            'msg'     => 'reCAPTCHA verification failed. Please try again.',
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

    Mail::send([], [], function ($message) use ($request) {
        $message->to('support@renownsystem.com')
                ->subject('New Contact Form Submission')
                ->html("
                    <h2>New Contact Message</h2>
                    <p><strong>Name:</strong> {$request->name}</p>
                    <p><strong>Email:</strong> {$request->email}</p>
                    <p><strong>Phone:</strong> {$request->phone}</p>
                    <p><strong>Message:</strong> " . ($request->message ?? 'N/A') . "</p>
                ");
    });

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
   
   public function featureDetail (Request $request, $slug)
   {
     if (!$slug) {
        abort(404);
     }

     $view = $this->featureView . $slug;

     if (view()->exists($view)) {
        return view($view);
     }

     abort(404);
   }

}
