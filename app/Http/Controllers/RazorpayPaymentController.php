<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
use Exception;
use App\Models\Plan;
use App\Models\Subscription;
use App\Mail\SubscriptionInvoiceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class RazorpayPaymentController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */

    public function index()
    {        
        return view('razorpayView');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function createOrder (Request $request) 
    {
       $api         = new Api(env('RAZORPAY_API_KEY'), env('RAZORPAY_API_SECRET'));
       $currency    = session('currency', 'INR');

        $order = $api->order->create([
            'receipt'   => 'order_rcpt_'.time(),
            'amount'    => (int)$request->amount,
            'currency'  => $currency,
        ]);

        return response()->json([
            'id'       => $order['id'],
            'amount'   => $order['amount'],
            'currency' => $order['currency'],
        ]); 
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'plan_id'             => 'required|exists:plans,id',
        ]);

        $api = new Api(env('RAZORPAY_API_KEY'), env('RAZORPAY_API_SECRET'));

        if (isset($input['email']) && isset($input['organization'])) {
            $tenant = (object)[
                'name' => $input['organization'],
                'adminUser' => (object)[
                    'email' => $input['email']
                ]
            ];
        }else {
            $tenant  = app('tenant');
        }

        try {
            $payment        = $api->payment->fetch($input['razorpay_payment_id']);
            $paymentData    = $payment->toArray();

            if ($paymentData['status'] !== 'captured') {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Payment not captured'
                ]);
            }

            if ( Subscription::where('razorpay_payment_id', $paymentData['id'])->exists() ) {
                DB::rollBack();

                return response()->json([
                    'success' => true,
                    'message' => 'Payment already processed'
                ]);
            }

            // Activate plan for tenant
            $requestedPlan  = Plan::findOrFail($input['plan_id']);

            if (!$requestedPlan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested plan is not found!'
                ]);
            }

            $prevSubscription   = Subscription::find($input['subscription_id']);

            if ($prevSubscription) {
                $subscription->update(['status' => 2]);
            }

            $subscription = Subscription::create([
                'plan_id'                   => $input['plan_id'],
                'start_date'                => now(),
                'end_date'                  => now()->addDays((int)$requestedPlan->duration),
                'razorpay_payment_id'       => $paymentData['id'],
                'razorpay_payment_payload'  => $paymentData,
                'status'                    => 1
            ]);

            if ($subscription) {
                Mail::to($tenant->adminUser->email)->send(
                    new SubscriptionInvoiceMail(
                        $subscription,
                        $requestedPlan,
                        $tenant,
                        $input['razorpay_payment_id'],
                        $input['razorpay_payment_amount']
                    )
                );
            }
            

            return response()->json([
                'success' => true,
                'message' => 'Payment successful & plan upgraded!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}