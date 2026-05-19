<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemoRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Models\Blog;
use App\Models\BlogCategory;

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

    Mail::send([], [], function ($message) use ($validate) {
        $message->to('support@renownsystem.com')
                ->cc('connect2rgteam@gmail.com')
            ->subject('New Demo Request Form Submission')
            ->html("
                <h2>New Demo Request Message (Renown System)</h2>
                <p><strong>Name:</strong> {$validate['r_name']}</p>
                <p><strong>Organization Name:</strong> {$validate['r_organization']}</p>
                <p><strong>Company Size:</strong> {$validate['r_company_size']}</p>
                <p><strong>Email:</strong> {$validate['r_email']}</p>
                <p><strong>Contact:</strong> {$validate['r_contact']}</p>
                <p><strong>Additional Note:</strong> " . ($request->additional ?? 'N/A') . "</p>
            ");
    });

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
                ->cc('connect2rgteam@gmail.com')
                ->subject('New Contact Form Submission')
                ->html("
                    <h2>New Contact Message (Renown System)</h2>
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

     $metaData = [
        'employee-management' => [
            'title'       => 'Employee Management System for Businesses | Renown System',
            'description' => 'Manage employee data, performance, and records efficiently with Renown System’s 
                              employee management software. Simplify HR tasks and improve productivity with smart 
                              automation.'
        ],

        'leave-management' => [
            'title'       => 'Leave Management System for Employees | Renown System',
            'description' => 'Streamline employee leave requests, approvals, and tracking with Renown System. Improve 
                              HR efficiency with an easy-to-use leave management solution.'
        ],

        'client-management' => [
            'title'       => 'Client Management Software for Businesses | Renown System',
            'description' => 'Organize customer data, track interactions, and improve relationships with Renown System 
                              client management software. Boost productivity and customer satisfaction.'
        ],

        'task-management' => [
            'title'       => 'Task Management Software for Teams | Renown System',
            'description' => 'Track tasks, assign work, and manage team productivity with Renown System task 
                              management software. Improve workflow and collaboration easily. '
        ],

        'onboarding-management' => [
            'title'       => 'Employee Onboarding Management System | Renown System',
            'description' => 'Simplify employee onboarding with Renown System. Automate documentation, training, and 
                              processes to enhance new hire experience and efficiency.'
        ],

        'invoice-management' => [
            'title'       => 'Invoice Management Software for Businesses | Renown System ',
            'description' => 'Create, track, and manage invoices efficiently with Renown System. Automate billing 
                              processes and improve financial management with ease.'
        ],

        'project-management' => [
            'title'       => 'Project Management Software for Teams | Renown System',
            'description' => 'Plan, track, and manage projects effectively with Renown System. Improve team 
                              collaboration and deliver projects on time with smart tools.'
        ],

        'payroll-management' => [
            'title'       => 'Payroll Management System for Businesses | Renown System',
            'description' => 'Automate salary processing, tax calculations, and payroll management with Renown 
                              System. Ensure accuracy and compliance with ease.'
        ],

        'ticket-management' => [
            'title'       => 'Ticket Management System for Support Teams | Renown System',
            'description' => 'Manage support tickets, track issues, and improve customer service with Renown System. 
                              Streamline workflows and resolve queries faster.'
        ],
     ];

     $title         = $metaData[$slug]['title']         ?? null;
     $description   = $metaData[$slug]['description']   ?? null;

     if ($slug == 'employee-management') {
        $title = 'Employee Management System for Businesses | Renown System';
        $description = 'Manage employee data, performance, and records efficiently with Renown System’s 
        employee management software. Simplify HR tasks and improve productivity with smart 
        automation.'; 
     }
     

     $view = $this->featureView . $slug;

     if (view()->exists($view)) {
        return view($view, compact('title', 'description'));
     }

     abort(404);
   }
   
   public function blogs (Request $request, $slug = '')
   {
       $query = Blog::where('status', 'published');
       
       if(!empty($slug)) {
           $query->whereHas('category', function($q) use ($slug) {
               $q->where('slug', $slug);
           });
       }
       
       $blogs = $query->paginate(10);
       
       $categories = BlogCategory::withCount([
           'blogs' => function ($q) {
               $q->where('status', 'published');
           }
        ])
        ->where('status', 1)
        ->latest()
        ->get();
        
        $recentBlogs = Blog::where('status', 'published')
                        ->latest()
                        ->take(5)
                        ->get();
       
       return view('pages.front.blogs', 
       [
           'blogs'          => $blogs,
           'categories'     => $categories,
           'recentBlogs'    => $recentBlogs
       ]);
   }
   
   public function blogDetails (Request $request, $slug)
   {
        if (!$slug) {
          abort(404);
        }
       
       $blog = Blog::where(['slug' => $slug, 'status' => 'published'])->first();
       
       $title         = $blog->meta_title         ?? null;
       $description   = $blog->meta_description   ?? null;
       $keywords      = $blog->kaywords           ?? null;
       
       $categories = BlogCategory::withCount([
           'blogs' => function ($q) {
               $q->where('status', 'published');
           }
        ])
        ->where('status', 1)
        ->latest()
        ->get();
        
        $recentBlogs = Blog::where('status', 'published')
                        ->latest()
                        ->take(5)
                        ->get();
       
       $relatedBlogs = Blog::where('status', 'published')
                        ->where('id', '!=', $blog->id)
                        ->latest()
                        ->take(3)
                        ->get();
       
       return view('pages.front.blog-details', 
        [
           'blog'           => $blog, 
           'relatedBlogs'   => $relatedBlogs, 
           'title'          => $title, 
           'description'    => $description, 
           'keywords'       => $keywords,
           'categories'     => $categories,
           'recentBlogs'    => $recentBlogs
        ]);
   }

}
