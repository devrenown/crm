<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $plan;
    public $tenant;
    public $paymentId;
    public $amount;

    public function __construct($subscription, $plan, $tenant, $paymentId, $amount)
    {
        $this->subscription = $subscription;
        $this->plan         = $plan;
        $this->tenant       = $tenant;
        $this->paymentId    = $paymentId;
        $this->amount       = $amount;
    }

    public function build()
    {
        return $this->subject('Your Subscription Invoice - ' . $this->plan->name)
            ->markdown('mail.subscriptionInvoice');
    }
}
