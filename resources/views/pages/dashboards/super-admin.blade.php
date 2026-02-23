{{--============ Dashboard Cards  ===============--}}

<div class="row">

    <!-- Total Organizations -->
    <div class="col-xl-3 col-sm-6">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon bg-inverse-primary">
                    <i class="fa-solid fa-building"></i>
                </span>
                <div class="dash-widget-info">
                    <h3>{{ $tenants->count() ?? 0 }}</h3>
                    <span>{{ __('Organizations') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Subscriptions -->
    <div class="col-xl-3 col-sm-6">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon bg-inverse-success">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </span>
                <div class="dash-widget-info">
                    <h3>{{ @$activeTenants ?? 0 }}</h3>
                    <span>{{ __('Active Subscriptions') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue -->
    <div class="col-xl-3 col-sm-6">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon bg-inverse-warning">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </span>
                <div class="dash-widget-info">
                    <h3>{{ @$monthlyRevenue ?? 0 }}</h3>
                    <span>{{ __('Monthly Revenue') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="col-xl-3 col-sm-6">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon bg-inverse-danger">
                    <i class="fa-solid fa-users"></i>
                </span>
                <div class="dash-widget-info">
                    <h3>{{ @$totalUsers ?? 0 }}</h3>
                    <span>{{ __('Total Users') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="col-xl-3 col-sm-6">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon bg-inverse-warning">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                </span>
                <div class="dash-widget-info">
                    <h3>{{ @$totalRevenue ?? 0 }}</h3>
                    <span>{{ __('Total Revenue') }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{--============ Dashboard Chart  ===============--}}
<div class="row">

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Platform Revenue vs Expenses') }}</h5>
            </div>
            <div class="card-body">
                <div id="bar-charts"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Subscriptions Growth') }}</h5>
            </div>
            <div class="card-body">
                <div id="line-charts"></div>
            </div>
        </div>
    </div>

</div>

{{--============ PLAN DISTRIBUTION  ===============--}}
<div class="row">

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Plan Distribution') }}</h5>
            </div>
            <div class="card-body">
                <div id="plan-distribution"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Monthly Platform Expenses') }}</h5>
            </div>
            <div class="card-body">
                <div id="monthly_expense_barchart"></div>
            </div>
        </div>
    </div>

</div>

{{--============ RECENT TENANT ACTIVITY  ===============--}}
<div class="card">
    <div class="card-header">
        <h5>{{ __('Recent Tenant Activity') }}</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ __('Organization') }}</th>
                    <th>{{ __('Action') }}</th>
                    <th>{{ __('Plan') }}</th>
                    <th>{{ __('Date') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentActivities ?? [] as $activity)
                    <tr>
                        <td>{{ $activity->tenant_name }}</td>
                        <td>{{ $activity->action }}</td>
                        <td>{{ $activity->plan }}</td>
                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@push('page-scripts')
    <script src="{{ asset('js/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('js/plugins/morris/morris.min.js') }}"></script>

    <script>
    $(function () {

        let currency = "{{ LocaleSettings('currency_symbol') }}";

        /* Revenue vs Expense */
        Morris.Bar({
            element: 'bar-charts',
            data: @json($platformFinance ?? []),
            xkey: 'month',
            ykeys: ['revenue', 'expense'],
            labels: ['Revenue', 'Expense'],
            barColors: ['#26AF48', '#ea5455'],
            resize: true
        });

        /* Subscriptions Growth */
        Morris.Line({
            element: 'line-charts',
            data: @json($subscriptionGrowth ?? []),
            xkey: 'month',
            ykeys: ['new', 'upgrade'],
            labels: ['New Subscriptions', 'Upgrades'],
            lineColors: ['#7367f0', '#ff9f43'],
            resize: true
        });

        /* Plan Distribution */
        Morris.Donut({
            element: 'plan-distribution',
            data: @json($planDistribution ?? []),
            colors: ['#7367f0','#28c76f','#ea5455']
        });

        /* Monthly Expense */
        Morris.Bar({
            element: 'monthly_expense_barchart',
            data: @json($monthlyExpense ?? []),
            xkey: 'month',
            ykeys: ['amount'],
            labels: [`Expense (${currency})`],
            barColors: ['#ff9f43'],
            resize: true
        });

    });
    </script>
@endpush
