@php
    $company = app(\App\Settings\CompanySettings::class);
    $client  = $invoice->client;

    $taxAmount        = $invoice->tax_amount ?? 0;
    $discountedAmount = ((int)$invoice->subtotal * (int)$invoice->discount) / 100;
    $grandTotal       = (int)$invoice->subtotal - (int)$discountedAmount + (int)$taxAmount;
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->inv_id }}</title>
</head>
<body style="margin:0; padding:20px; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center">

<table width="800" cellpadding="20" cellspacing="0" style="background:#ffffff; border:1px solid #e5e5e5;">
    
    <!-- HEADER -->
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td width="50%" valign="top">
                        <img src="{{ appLogo() }}" alt="Logo" style="max-width:140px; margin-bottom:10px;">
                        <p style="margin:2px 0;"><strong>Company:</strong> {{ $company->name }}</p>
                        <p style="margin:2px 0;"><strong>Address:</strong> {{ $company->address }}</p>
                        <p style="margin:2px 0;">
                            {{ $company->city ?? '' }}
                            {{ $company->province ?? '' }}
                            {{ $company->postal_code ?? '' }}
                        </p>
                    </td>

                    <td width="50%" valign="top" align="right">
                        <h2 style="margin:0; text-transform:uppercase;">
                            Invoice {{ $invoice->inv_id }}
                        </h2>
                        <p style="margin:4px 0;">Create Date: {{ format_date($invoice->created_at) }}</p>
                        <p style="margin:4px 0;">Start Date: {{ format_date($invoice->startDate) }}</p>
                        <p style="margin:4px 0;">Expiry Date: {{ format_date($invoice->expiryDate) }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- CLIENT INFO -->
    <tr>
        <td>
            <h3 style="margin-bottom:8px;">Invoice To</h3>
            <p style="margin:2px 0;"><strong>Client Name:</strong> {{ $client->fullname }}</p>
            <p style="margin:2px 0;"><strong>Client Address:</strong> {!! $invoice->client_address !!}</p>
            <p style="margin:2px 0;"><strong>Billing Address:</strong> {!! $invoice->billing_address !!}</p>
            <p style="margin:2px 0;"><strong>Phone:</strong> {{ $client->phoneNumber }}</p>
            <p style="margin:2px 0;"><strong>Email:</strong> {{ $client->email }}</p>
        </td>
    </tr>

    <!-- ITEMS TABLE -->
    <tr>
        <td>
            <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse:collapse;">
                <thead>
                    <tr style="background:#f0f0f0;">
                        <th align="left">#</th>
                        <th align="left">Item</th>
                        <th align="left">Description</th>
                        <th align="right">Unit Cost</th>
                        <th align="right">Qty</th>
                        <th align="right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
                            <td align="right">{{ LocaleSettings('currency_symbol') }} {{ number_format($item->unit_cost, 2) }}</td>
                            <td align="right">{{ $item->quantity }}</td>
                            <td align="right">{{ LocaleSettings('currency_symbol') }} {{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </td>
    </tr>

    <!-- TOTALS -->
    <tr>
        <td align="right">
            <table width="300" cellpadding="6" cellspacing="0">
                <tr>
                    <td>Subtotal</td>
                    <td align="right">{{ LocaleSettings('currency_symbol') }} {{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Tax ({{ optional($invoice->tax)->percentage ?? 0 }}%)</td>
                    <td align="right">{{ LocaleSettings('currency_symbol') }} {{ number_format($taxAmount, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount ({{ $invoice->discount ?? 0 }}%)</td>
                    <td align="right">{{ LocaleSettings('currency_symbol') }} {{ number_format($discountedAmount, 2) }}</td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Grand Total</td>
                    <td align="right" style="font-weight:bold; color:#0d6efd;">
                        {{ LocaleSettings('currency_symbol') }} {{ number_format($grandTotal, 2) }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- NOTE -->
    <tr>
        <td>
            <h4>Other Information</h4>
            <p style="color:#666;">{{ $invoice->note }}</p>
        </td>
    </tr>

</table>

</td>
</tr>
</table>

</body>
</html>
