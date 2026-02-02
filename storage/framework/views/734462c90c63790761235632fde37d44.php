

<div class="row">

    <!-- Total Organizations -->
    <div class="col-xl-3 col-sm-6">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon bg-inverse-primary">
                    <i class="fa-solid fa-building"></i>
                </span>
                <div class="dash-widget-info">
                    <h3><?php echo e($tenants->count() ?? 0); ?></h3>
                    <span><?php echo e(__('Organizations')); ?></span>
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
                    <h3><?php echo e(@$activeTenants ?? 0); ?></h3>
                    <span><?php echo e(__('Active Subscriptions')); ?></span>
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
                    <h3><?php echo e(@$monthlyRevenue ?? 0); ?></h3>
                    <span><?php echo e(__('Monthly Revenue')); ?></span>
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
                    <h3><?php echo e(@$totalUsers ?? 0); ?></h3>
                    <span><?php echo e(__('Total Users')); ?></span>
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
                    <h3><?php echo e(@$totalRevenue ?? 0); ?></h3>
                    <span><?php echo e(__('Total Revenue')); ?></span>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row">

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><?php echo e(__('Platform Revenue vs Expenses')); ?></h5>
            </div>
            <div class="card-body">
                <div id="bar-charts"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><?php echo e(__('Subscriptions Growth')); ?></h5>
            </div>
            <div class="card-body">
                <div id="line-charts"></div>
            </div>
        </div>
    </div>

</div>


<div class="row">

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><?php echo e(__('Plan Distribution')); ?></h5>
            </div>
            <div class="card-body">
                <div id="plan-distribution"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><?php echo e(__('Monthly Platform Expenses')); ?></h5>
            </div>
            <div class="card-body">
                <div id="monthly_expense_barchart"></div>
            </div>
        </div>
    </div>

</div>


<div class="card">
    <div class="card-header">
        <h5><?php echo e(__('Recent Tenant Activity')); ?></h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo e(__('Organization')); ?></th>
                    <th><?php echo e(__('Action')); ?></th>
                    <th><?php echo e(__('Plan')); ?></th>
                    <th><?php echo e(__('Date')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $recentActivities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($activity->tenant_name); ?></td>
                        <td><?php echo e($activity->action); ?></td>
                        <td><?php echo e($activity->plan); ?></td>
                        <td><?php echo e($activity->created_at->diffForHumans()); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>


<?php $__env->startPush('page-scripts'); ?>
    <script src="<?php echo e(asset('js/plugins/raphael/raphael.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/plugins/morris/morris.min.js')); ?>"></script>

    <script>
    $(function () {

        let currency = "<?php echo e(LocaleSettings('currency_symbol')); ?>";

        /* Revenue vs Expense */
        Morris.Bar({
            element: 'bar-charts',
            data: <?php echo json_encode($platformFinance ?? [], 15, 512) ?>,
            xkey: 'month',
            ykeys: ['revenue', 'expense'],
            labels: ['Revenue', 'Expense'],
            barColors: ['#26AF48', '#ea5455'],
            resize: true
        });

        /* Subscriptions Growth */
        Morris.Line({
            element: 'line-charts',
            data: <?php echo json_encode($subscriptionGrowth ?? [], 15, 512) ?>,
            xkey: 'month',
            ykeys: ['new', 'upgrade'],
            labels: ['New Subscriptions', 'Upgrades'],
            lineColors: ['#7367f0', '#ff9f43'],
            resize: true
        });

        /* Plan Distribution */
        Morris.Donut({
            element: 'plan-distribution',
            data: <?php echo json_encode($planDistribution ?? [], 15, 512) ?>,
            colors: ['#7367f0','#28c76f','#ea5455']
        });

        /* Monthly Expense */
        Morris.Bar({
            element: 'monthly_expense_barchart',
            data: <?php echo json_encode($monthlyExpense ?? [], 15, 512) ?>,
            xkey: 'month',
            ykeys: ['amount'],
            labels: [`Expense (${currency})`],
            barColors: ['#ff9f43'],
            resize: true
        });

    });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/dashboards/super-admin.blade.php ENDPATH**/ ?>