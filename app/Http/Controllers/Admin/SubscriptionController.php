<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public $view = 'pages.subscription.';

    public function index () 
    {
        $this->data['pageTitle']        = 'Subscriptions';
        $this->data['tenant']           = app('tenant');
        $this->data['subscription']     = $this->data['tenant']->currentSubscription;
        $this->data['subscriptions']    = $this->data['tenant']->subscriptions;
        $this->data['plan']             = $this->data['tenant']->currentSubscription->plan;

        return view($this->view . 'index', $this->data);
    }

    public function upgrade ()
    {
        $currencyCode = session('currency', 'INR');
        $rate = CurrencyService::getRate('INR', $currencyCode);

        $currency = DB::table('currencies')->where('code', $currencyCode)->first();

        $plans = Plan::where('status', 1)->get()->map(function ($plan) use ($rate) {
            $plan->display_price = round($plan->price * $rate, 2);
            return $plan;
        });

        $this->data['pageTitle']    = 'Upgrade Subscription';
        $this->data['plans']        = $plans;
        $this->data['currency']     = $currency;
        $this->data['current_plan'] = app('tenant')->currentSubscription->plan;
        $this->data['subscription'] = app('tenant')->currentSubscription;
        return view($this->view . 'upgrade', $this->data);
    }
}
