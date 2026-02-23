<x-mail::message>
# Invoice for Subscription Purchase

Hello **{{ $tenant->name }}**,  
Thank you for choosing your plan on **Renown CRM**!

---

## **🧾 Invoice Details**

**Plan:** {{ $plan->name }}  
**Subscription Duration:** {{ $plan->duration }} days  
**Amount Paid:** ₹{{ number_format($amount / 100, 2) }}  
**Payment ID:** {{ $paymentId }}  
**Invoice Date:** {{ now()->format('d M, Y') }}

---

## **📅 Subscription Period**

| Starts On | Ends On |
|-----------|----------|
| {{ date('d M Y', strtotime($subscription->start_date)) }} | {{ date('d M Y', strtotime($subscription->end_date)) }} |

---

## **🔒 Secure Purchase**

Your payment has been securely processed via Razorpay.  
If you have any questions, reply to this email anytime.

@if(isset($tenant->domain))
	<x-mail::button :url="'https://$tenant->domain'">
	Go To Dashboard
	</x-mail::button>
@endif

Thanks,<br>
**Renown CRM**
</x-mail::message>
