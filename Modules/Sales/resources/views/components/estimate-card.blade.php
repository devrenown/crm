@props(['estimate' => $estimate])
<div class="card estimateCard">
    <div class="card-body">
        <div class="row">
            @php
                $company = app(\App\Settings\CompanySettings::class);
            @endphp
            <div class="col-sm-6 m-b-20">
                <img src="{{ appLogo() }}" class="inv-logo" alt="Logo">
                 <ul class="list-unstyled">
                    <li> <strong> Company: </strong> {{ $company->name }}</li>
                    <li> <strong> Address: </strong>{{ $company->address }}</li>
                    <li>{{ !empty($company->city) ? $company->city.' , ':'' }}{{ !empty($company->province) ? $company->province.' ,' :''.$company->postal_code}}</li>
                </ul>
            </div>
            <div class="col-sm-6 m-b-20">
                <div class="invoice-details">
                    <h3 class="text-uppercase">{{ __('Estimate') }} {{ $estimate->est_id }}</h3>
                    <ul class="list-unstyled">
                        <li>{{ __('Create Date') }}: <span>{{ format_date($estimate->created_at) }}</span></li>
                        <li>{{ __('Start Date') }}: <span>{{ format_date($estimate->startDate) }}</span></li>
                        <li>{{ __('Expiry date') }}: <span>{{ format_date($estimate->expiryDate) }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-12 m-b-20">
                <h5 class="fs-5">{{ __('Estimate to') }}:</h5>
                @php
                    $client = $estimate->client;
                @endphp
                 <ul class="list-unstyled">
                    <li><strong>Client Name: </strong> {{ $client->fullname }}</li>
                    {{-- <li>  {{ $client->address }}</li> --}}
                    <li>
                      <strong>Client Address: </strong>  {!! $estimate->client_address !!}
                    </li>
                    <li>
                      <strong>Billing Address: </strong>  {!! $estimate->billing_address !!}
                    </li>
                    <li><strong>Client Phone: </strong> {{ $client->phoneNumber }}</li>
                    <li><strong>Client Email: </strong> <a href="@activeCan('show-Clientprofile') {{ route('clients.show',['client' => \Crypt::encrypt($client->id)]) }} @else # @endactiveCan">{{ $client->email }}</a></li>
                </ul>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('ITEM') }}</th>
                    <th class="d-none d-sm-table-cell">{{ __('DESCRIPTION') }}</th>
                    <th>{{ __('UNIT COST') }}</th>
                    <th>{{ __('QUANTITY') }}</th>
                    <th class="text-end">{{ __('TOTAL') }}</th>
                </tr>
            </thead>
            <tbody>
                
                @if (!empty($estimate->items) && $estimate->items->count() > 0)
                    @foreach ($estimate->items as $i => $item)
                    <tr>
                        <td>{{ ++$i}}</td>
                        <td>{{ $item->name }}</td>
                        <td class="d-none d-sm-table-cell">
                            {{ $item->description }}
                        </td>
                        <td>{{ LocaleSettings('currency_symbol') }} {{ number_format($item->unit_cost, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-end">{{ LocaleSettings('currency_symbol') }} {{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div>
            <div class="row invoice-payment">
                <div class="col-sm-7">
                </div>
                <div class="col-sm-5">
                    <div class="m-b-20">
                        <div class="table-responsive no-border">
                            <table class="table">
                                <tbody>
                                    @php
                                      $discountedAmount = ((int)$estimate->subtotal * (int)$estimate->discount) / 100;
                                    @endphp
                                    <tr>
                                        <th>{{ __('Subtotal') }}:</th>
                                        <td class="text-end">{{ LocaleSettings('currency_symbol') }} {{ number_format($estimate->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Tax') }}: <span class="text-regular">({{ $estimate->tax->percentage }}%)</span></th>
                                        <td class="text-end">{{ LocaleSettings('currency_symbol') }} {{ number_format($estimate->tax_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('Discount') }}: <span class="text-regular">({{ $estimate->discount }}%)</span></th>
                                        <td class="text-end"> {{ LocaleSettings('currency_symbol') }} {{ number_format($discountedAmount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td class="text-end text-primary"><h5>{{ LocaleSettings('currency_symbol') }} {{ number_format((int)$estimate->subtotal - (int)$discountedAmount + (int)$estimate->tax_amount, 2) }}</h5></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="invoice-info">
                <h5>{{ __('Other information') }}</h5>
                <p class="text-muted">
                    {{ $estimate->note }}
                </p>
            </div>
        </div>
    </div>
</div>