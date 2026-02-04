<?php
  $tenant = app('tenant');
  $subscription     = $tenant->currentSubscription;
  $remainingDays    = max(0, now()->diffInDays($subscription->end_date, false));
  $isExpired        = $remainingDays === 0 ? true : false;
?>

<style>
    .user-img .status {
        border: 2px solid #ffffff;
        height: 10px;
        width: 10px;
        margin: 0;
        position: absolute;
        left: 5px;
        bottom: 12px;
        border-radius: 50%;
        display: inline-block;
    }
</style>

<?php if(number_format($remainingDays) <= 7): ?>

<marquee>
    <?php if($isExpired): ?>
    <div class="text-danger">
        <strong>Your subscription has expired.</strong>
        Access to this workspace is currently restricted for all users under this organization.
        To avoid service interruption, data access limitations, or user lockouts, please
        renew or upgrade your subscription as soon as possible. Once renewed, all features,
        user access, and integrations will be restored immediately.
    </div>
    <?php else: ?>
    <div class="text-primary">
        <strong>Subscription expiry notice:</strong>
        This organization’s subscription will expire in
        <strong>
            <?php echo e(number_format($remainingDays, 1)); ?>

            day<?php echo e(number_format($remainingDays) > 1 ? 's' : ''); ?>

        </strong>.
        To ensure uninterrupted access for all users, continued data availability,
        and smooth operation across modules, we recommend renewing or upgrading your plan
        before the expiration date.
    </div>
    <?php endif; ?>
</marquee>


<?php endif; ?>

<div class="row mb-3">
    
    <?php echo $__env->make('pages.dashboards.events.birthday', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('pages.dashboards.events.work-anniversary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('pages.dashboards.events.probation-period', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>

<div class="row">
    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-projects')): ?>
    <?php if(!empty($projects)): ?>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 p-1">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa-solid fa-cubes"></i></span>
                <div class="dash-widget-info">
                    <h3><?php echo e(!empty($projects) ? $projects->count(): 0); ?></h3>
                    <span><?php echo e(__('Projects')); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-clients')): ?>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 p-1">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa-solid fa-user-tie"></i></span>
                <div class="dash-widget-info">
                    <h3><?php echo e(!empty($clients) ? $clients->count() : 0); ?></h3>
                    <span><?php echo e(__('Clients')); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-tickets')): ?>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 p-1">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa-solid fa-ticket"></i></span>
                <div class="dash-widget-info">
                    <h3><?php echo e(!empty($tickets) ? $tickets->count(): 0); ?></h3>
                    <span><?php echo e(__('Tickets')); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-employees')): ?>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 p-1">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa-solid fa-user"></i></span>
                <div class="dash-widget-info">
                    <h3><?php echo e(!empty($employees) ? $employees->count(): 0); ?></h3>
                    <span><?php echo e(__('Employees')); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-invoices')): ?>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 p-1">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa-solid fa-file-invoice"></i></span>
                <div class="dash-widget-info">
                    <h3><?php echo e($allInvoiceCount); ?></h3>
                    <span><?php echo e(__('Invoices')); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-assets')): ?>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 p-1">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                <div class="dash-widget-info">
                    <h3><?php echo e($allAssetCount); ?></h3>
                    <span><?php echo e(__('Assets')); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-employees')): ?>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 p-1">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa-solid fa-user-check"></i></span>
                <div class="dash-widget-info">
                    <h3><?php echo e($totalActiveUser); ?></h3>
                    <span><?php echo e(__('Total Active users')); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(activeRole() === \App\Enums\UserType::ADMIN->value): ?>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3 p-1">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa-solid fa-users"></i></span>
                <div class="dash-widget-info">
                    <h3><?php echo e($allUsersCount); ?></h3>
                    <span><?php echo e(__('Total Users')); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
        
<?php if(!empty(module('Sales'))): ?>
    <div class="row p-2">
        <div class="col-md-12">
            <div class="row">
                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-employees')): ?>
                <div class="card col-lg-3 col-md-6">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <span class="d-block"><?php echo e(__('New Employees')); ?></span>
                            </div>
                        </div>
                        <h3 class="mb-3"><?php echo e($thisMonthTotalEmployees); ?></h3>
                        <div class="progress height-five mb-2">
                            <div class="progress-bar bg-primary w-70" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="mb-0"><?php echo e(__('Previous Month Employees')); ?> <?php echo e($prevMonthTotalEmployees); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-expenses')): ?>
                <div class="card col-lg-3 col-md-6">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <span class="d-block"><?php echo e(__('Expenses')); ?></span>
                            </div>
                        </div>
                        <h3 class="mb-3"><?php echo e(LocaleSettings('currency_symbol').' '.$thisMonthExpenses); ?></h3>
                        <div class="progress height-five mb-2">
                            <div class="progress-bar bg-primary w-70" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="mb-0"><?php echo e(__('Previous Month')); ?> <span class="text-muted"><?php echo e(LocaleSettings('currency_symbol').' '.$prevMonthExpenses); ?></span></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-estimates')): ?>
                <div class="card col-lg-3 col-md-6">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <span class="d-block"><?php echo e(__('Estimates')); ?></span>
                            </div>
                        </div>
                        <h3 class="mb-3"><?php echo e(LocaleSettings('currency_symbol').' '.$thisMonthEstimates); ?></h3>
                        <div class="progress height-five mb-2">
                            <div class="progress-bar bg-primary w-70" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="mb-0"><?php echo e(__('Previous Month')); ?> <span class="text-muted"><?php echo e(LocaleSettings('currency_symbol').' '.$prevMonthEstimates); ?></span></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-invoices')): ?>
                <div class="card col-lg-3 col-md-6">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <span class="d-block"><?php echo e(__('Invoices')); ?></span>
                            </div>
                        </div>
                        <h3 class="mb-3"><?php echo e(LocaleSettings('currency_symbol').' '.$thisMonthInvoices); ?></h3>
                        <div class="progress height-five mb-2">
                            <div class="progress-bar bg-primary w-70" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="mb-0"><?php echo e(__('Previous Month')); ?> <span class="text-muted"><?php echo e(LocaleSettings('currency_symbol').' '.$thisMonthInvoices); ?></span></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>  
    </div>
<?php endif; ?>
        
<!-- Statistics Widget -->
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-4 d-flex">
        <div class="card flex-fill dash-statistics">
            <div class="card-body">
                <h5 class="card-title"><?php echo e(__('Statistics')); ?></h5>
                <div class="stats-list">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-invoices')): ?>
                    <?php if(!empty($invoices) && $invoices->count() > 0): ?>
                    <div class="stats-info">
                        <p><?php echo e(__('Declined Invoices')); ?> <strong><?php echo e($invoices->where('status', '4')->count()); ?> <small>/ <?php echo e($invoices->count()); ?></small></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-danger w-31" role="progressbar" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="stats-info">
                        <p><?php echo e(__('Partially Paid Invoices')); ?> <strong><?php echo e($invoices->where('status', '3')->count()); ?> <small>/ <?php echo e($invoices->count()); ?></small></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-info w-31" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="stats-info">
                        <p><?php echo e(__('Paid Invoices')); ?> <strong><?php echo e($invoices->where('status', '2')->count()); ?> <small>/ <?php echo e($invoices->count()); ?></small></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-success w-31" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                        
                    <div class="stats-info">
                        <p><?php echo e(__('Sent Invoices')); ?> <strong><?php echo e($invoices->where('status', '1')->count()); ?> <small>/ <?php echo e($invoices->count()); ?></small></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-primary w-31" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>                                
                    <?php endif; ?>
                    <?php endif; ?>

                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-tickets')): ?>
                    <?php if(!empty($tickets) && $tickets->count() > 0): ?>
                    <div class="stats-info">
                        <p><?php echo e(__('Open Tickets')); ?> <strong><?php echo e($tickets->where('status', \App\Enums\TicketStatus::NEW)->count()); ?> <small>/ <?php echo e($tickets->count()); ?></small></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-danger w-62" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="stats-info">
                        <p><?php echo e(__('Closed Tickets')); ?> <strong><?php echo e($tickets->where('status', \App\Enums\TicketStatus::CLOSED)->count()); ?> <small>/ <?php echo e($tickets->count()); ?></small></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-info w-22" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-employees')): ?>
    <?php if(!empty($absentees) && $absentees->count() > 0): ?>
    <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
        <div class="card flex-fill">

            <h4 class="card-title p-3 mb-0"><?php echo e(__('Today Absent')); ?> <span class="badge bg-inverse-danger ms-2"><?php echo e($absentees->count()); ?></span></h4>

            <div class="card-body" style="max-height: 25rem; overflow-y: scroll;">
                <?php $__currentLoopData = $absentees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="leave-info-box">
                    <div class="media d-flex align-items-center">
                        <a <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'show-Employeeprofile')): ?> href="<?php echo e(route('employees.index')); ?>" <?php else: ?> href="#" <?php endif; ?> class="avatar"><img src="<?php echo e(!empty($user->avatar) ? asset('storage/users/'.$user->avatar) : asset('images/user.jpg')); ?>" alt="<?php echo e(__('Image')); ?>"></a>
                        <div class="media-body flex-grow-1">
                            <div class="text-sm my-0"><?php echo e($user->fullname); ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>     
                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-attendances')): ?>                  
                <div class="load-more text-center">
                    <a class="text-dark" href="<?php echo e(route('attendances.index')); ?>"><?php echo e(__('Load More')); ?></a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-employees')): ?>
    <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
        <div class="card flex-fill">

            <h4 class="card-title p-3 mb-0"><?php echo e(__('Today Present')); ?> <span class="badge bg-inverse-success ms-2"><?php echo e($persents->count()); ?></span></h4>

            <div class="card-body" style="max-height: 25rem; overflow-y: scroll;">

                <table class="table table-responsive">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Name</th>
                        <th>Shift</th>
                        <th>Location</th>
                        <th>Platform</th>
                        <th>Clock In</th>
                    </tr>

                    <?php $__currentLoopData = $persents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        $attendance = $user->attendances->first();
                    ?>
                    <tr>
                        <td><?php echo e(++$key); ?></td>
                        <td class="position-relative">
                            <span class="user-img">
                                <img src="<?php echo e(!empty($user->avatar) ? asset('storage/users/'.$user->avatar) : asset('images/user.jpg')); ?>" alt="<?php echo e(__('Image')); ?>" class="avatar">
                                <span class="status bg-<?php echo e($user->is_online == 1 ? 'success' : 'danger'); ?>"></span>
                            </span>
                        </td>
                        <td><?php echo e($user->fullname); ?></td>
                        <td><?php echo e(@$user->shift->shift->name ?? 'N/A'); ?></td>
                        <td><?php echo e($attendance->location ?? 'N/A'); ?></td>
                        <td><?php echo e($attendance->platform ?? 'N/A'); ?></td>
                        <td><?php echo e(date('H:i A', strtotime($attendance->created_at))); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>

            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
<!-- /Statistics Widget -->

<?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-invoices')): ?>        
<?php if(!empty(module('Sales')) && module('Sales')->isEnabled()): ?>
    <div class="row">
        <div class="col-md-12 d-flex">
            <div class="card card-table flex-fill">
                <div class="card-header">
                    <h3 class="card-title mb-0"><?php echo e(__('Invoices')); ?></h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap custom-table mb-0">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Invoice ID')); ?></th>
                                    <th><?php echo e(__('Client')); ?></th>
                                    <th><?php echo e(__('Due Date')); ?></th>
                                    <th><?php echo e(__('Total')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($thisMonthInvoiceList)): ?>
                                    <?php $__currentLoopData = $thisMonthInvoiceList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><a <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'show-invoice')): ?> target="_blank" href="<?php echo e(route('invoices.show', ['invoice' => Crypt::encrypt($invoice->id)])); ?>" <?php else: ?> href="#" <?php endif; ?>><?php echo e($invoice->inv_id); ?></a></td>
                                        <td>
                                            <h2><?php echo e($invoice->client->user->fullname ?? ''); ?></h2>
                                        </td>
                                        <td><?php echo e(format_date($invoice->expiryDate) ?? ''); ?></td>
                                        <td><?php echo e(LocaleSettings('currency_symbol')); ?> <?php echo e($invoice->grand_total); ?></td>
                                        <td>
                                            <span class="badge bg-inverse-<?php echo e($invoice->statusName['color'] ?? 'primary'); ?>"><?php echo e($invoice->statusName['name'] ?? ''); ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-invoices')): ?>
                <div class="card-footer">
                    <a target="_blank" href="<?php echo e(route('invoices.index')); ?>"><?php echo e(__('View all invoices')); ?></a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php endif; ?>
        
<div class="row">
    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-clients')): ?>
    <div class="col-md-6 d-flex">
        <div class="card card-table flex-fill">
            <div class="card-header">
                <h3 class="card-title mb-0"><?php echo e(__('Clients')); ?></h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table custom-table mb-0">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Email')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-client','delete-client'])): ?>
                                <th class="text-end"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($thisMonthClients) && $thisMonthClients->count() > 0): ?>
                                <?php $__currentLoopData = $thisMonthClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php
                                        $img = !empty($client->avatar) ? asset('storage/users/'.$client->avatar): asset('images/user.jpg');
                                        $link = (auth()->user()->can('show-ClientProfile')) ? route('clients.show', ['client' => Crypt::encrypt($client->id)]): '#';
                                    ?>
                                    <td>
                                        <?php echo \Spatie\Menu\Laravel\Html::userAvatar($client->fullname, $img, $link); ?>

                                    </td>
                                    <td><?php echo e($client->email); ?></td>
                                    <td>
                                        <?php echo e($client->status->name ?? ''); ?>

                                    </td>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-client','delete-client'])): ?>
                                    <td class="text-end">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-client')): ?>
                                                <a class="dropdown-item" href="javascript:void(0)" data-url="<?php echo e(route('clients.edit', ['client' => \Crypt::encrypt($client->id)])); ?>" data-ajax-modal="true"
                                                    data-title="Edit Client" data-size="lg"><i class="fa-solid fa-pencil m-r-5"></i>
                                                    <?php echo e(__('Edit')); ?>

                                                </a>
                                                <?php endif; ?>
                                                <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-client')): ?>
                                                <a class="dropdown-item deleteBtn" data-route="<?php echo e(route('clients.destroy', $client->id)); ?>" data-title="<?php echo e(__('Delete Client')); ?>"
                                                    data-question="Are you sure you want to delete?" href="javascript:void(0)">
                                                    <i class="fa-regular fa-trash-can m-r-5"></i>
                                                    <?php echo e(__('Delete')); ?>

                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>  
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-clients')): ?>
            <div class="card-footer">
                <a href="<?php echo e(route('clients.index')); ?>"><?php echo e(__('View all clients')); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-projects')): ?>
    <?php if(module('Project') && module('Project')->isEnabled()): ?>
    <div class="col-md-6 d-flex">
        <div class="card card-table flex-fill">
            <div class="card-header">
                <h3 class="card-title mb-0"><?php echo e(__('Recent Projects')); ?></h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table custom-table mb-0">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Project Name')); ?> </th>
                                <th><?php echo e(__('Date')); ?></th>
                                <th><?php echo e(__('Priority')); ?></th>
                                <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-project', 'delete-project'])): ?>   
                                <th class="text-end"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($recentProjects) && $recentProjects->count() > 0): ?>
                            <?php $__currentLoopData = $recentProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <tr>
                                <td>
                                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'show-project')): ?>
                                        <h2><a target="_blank" href="<?php echo e(route('projects.show', ['project' => \Crypt::encrypt($project->id)])); ?>"><?php echo e($project->name); ?></a></h2>
                                    <?php else: ?>   
                                    <h2><a href="#"><?php echo e($project->name); ?></a></h2>
                                    <?php endif; ?>
                                    <small class="block text-ellipsis m-b-15">
                                        <span class="text-xs"><?php echo e($project->tasks->count() ?? 0); ?></span> <span class="text-muted"><?php echo e(__('Opened Tasks')); ?></span>
                                        <span class="text-xs"><?php echo e($project->tasks->count() ?? 0); ?></span> <span class="text-muted"><?php echo e(__('Tasks Completed')); ?></span>
                                    </small>
                                </td>
                                
                                <td>
                                    <?php echo e(format_date($project->startDate)); ?> - <?php echo e(format_date($project->endDate)); ?>

                                </td>
                                <td>
                                    <?php echo e($project->priority); ?>

                                </td>
                                <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['edit-project','delete-project'])): ?>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-project')): ?>
                                            <a class="dropdown-item" href="javascript:void(0)" data-url="<?php echo e(route('projects.edit', ['project' => ($project->id)])); ?>" data-ajax-modal="true"
                                                data-title="Edit Project" data-size="lg">
                                                <i class="fa-solid fa-pencil m-r-5"></i>
                                                <?php echo e(__('Edit')); ?>

                                            </a>
                                            <?php endif; ?>
                                            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-project')): ?>
                                            <a class="dropdown-item deleteBtn" data-route="<?php echo e(route('projects.destroy', $project->id)); ?>" data-title="Delete Project"
                                                data-question="Are you sure you want to delete project?" href="javascript:void(0)">
                                                <i class="fa-regular fa-trash-can m-r-5"></i>
                                                <?php echo e(__('Delete')); ?>

                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-projects')): ?>
            <div class="card-footer">
                <a href="<?php echo e(route('projects.index')); ?>"><?php echo e(__('View all projects')); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-budgets')): ?>
            <div class="col-md-6 text-center">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo e(__('Budget')); ?></h3>
                        <div id="bar-charts"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['view-estimates', 'view-invoices'])): ?>
            <div class="col-md-6 text-center">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo e(__('Estimates & Invoices Overview')); ?></h3>
                        <div id="line-charts"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-expenses')): ?>
            <div class="col-md-6 text-center">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo e(__('Expenses')); ?></h3>
                        <div id="monthly_expense_barchart"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php $__env->startPush('page-scripts'); ?>
    <!-- ChartJS -->
    <script defer src="<?php echo e(asset('js/plugins/morris/morris.min.js')); ?>"></script>
    <script defer src="<?php echo e(asset('js/plugins/raphael/raphael.min.js')); ?>"></script>
    <script type="module" defer>
    $(document).ready(function() {
            let currency_symbol = "<?php echo e(LocaleSettings('currency_symbol')); ?>"
            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-budgets')): ?>
            <?php if(!empty($budget_collection)): ?>
            Morris.Bar({
                element: 'bar-charts',
                redrawOnParentResize: true,
                data: [
                    { y: 'Jan', a: "<?php echo e($budget_collection->get(0)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(0)->sum('total_expense')); ?>" },
                    { y: 'Feb', a: "<?php echo e($budget_collection->get(1)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(1)->sum('total_expense')); ?>" },
                    { y: 'Mar', a: "<?php echo e($budget_collection->get(2)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(2)->sum('total_expense')); ?>" },
                    { y: 'Apr', a: "<?php echo e($budget_collection->get(3)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(3)->sum('total_expense')); ?>"},
                    { y: 'May', a: "<?php echo e($budget_collection->get(4)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(4)->sum('total_expense')); ?>" },
                    { y: 'Jun', a: "<?php echo e($budget_collection->get(5)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(5)->sum('total_expense')); ?>"},
                    { y: 'Jul', a: "<?php echo e($budget_collection->get(6)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(6)->sum('total_expense')); ?>" },
                    { y: 'Aug', a: "<?php echo e($budget_collection->get(7)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(7)->sum('total_expense')); ?>"},
                    { y: 'Sept', a: "<?php echo e($budget_collection->get(8)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(8)->sum('total_expense')); ?>"},
                    { y: 'Oct', a: "<?php echo e($budget_collection->get(9)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(9)->sum('total_expense')); ?>"},
                    { y: 'Nov', a: "<?php echo e($budget_collection->get(10)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(10)->sum('total_expense')); ?>"},
                    { y: 'Dec', a: "<?php echo e($budget_collection->get(11)->sum('total_revenue')); ?>", b: "<?php echo e($budget_collection->get(11)->sum('total_expense')); ?>"},
                ],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ["<?php echo e(__('Expected Revenue')); ?>", "<?php echo e(__('Expected Expenses')); ?>"],
                lineColors: ['#ff9b44','#fc6075'],
                lineWidth: '3px',
                barColors: ['#ff9b44','#fc6075'],
                resize: true,
                redraw: true
            });
            <?php endif; ?>
            <?php endif; ?>

            // Line Chart
            <?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['view-estimates', 'view-invoices'])): ?>
            <?php if(!empty($invoice_collection)): ?>
            Morris.Line({
                element: 'line-charts',
                redrawOnParentResize: true,
                data: [
                    { y: 1, a: "<?php echo e($invoice_collection->get(0)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(0)->sum('grand_total')); ?>" },
                    { y: 2, a: "<?php echo e($invoice_collection->get(1)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(1)->sum('grand_total')); ?>" },
                    { y: 3, a: "<?php echo e($invoice_collection->get(2)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(2)->sum('grand_total')); ?>" },
                    { y: 4, a: "<?php echo e($invoice_collection->get(3)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(3)->sum('grand_total')); ?>"},
                    { y: 5, a: "<?php echo e($invoice_collection->get(4)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(4)->sum('grand_total')); ?>" },
                    { y: 6, a: "<?php echo e($invoice_collection->get(5)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(5)->sum('grand_total')); ?>"},
                    { y: 7, a: "<?php echo e($invoice_collection->get(6)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(6)->sum('grand_total')); ?>" },
                    { y: 8, a: "<?php echo e($invoice_collection->get(7)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(7)->sum('grand_total')); ?>"},
                    { y: 9, a: "<?php echo e($invoice_collection->get(8)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(8)->sum('grand_total')); ?>"},
                    { y: 10, a: "<?php echo e($invoice_collection->get(9)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(9)->sum('grand_total')); ?>"},
                    { y: 11, a: "<?php echo e($invoice_collection->get(10)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(10)->sum('grand_total')); ?>"},
                    { y: 12, a: "<?php echo e($invoice_collection->get(11)->sum('grand_total')); ?>", b: "<?php echo e($estimates_collection->get(11)->sum('grand_total')); ?>"},
                ],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ['Invoices', 'Estimates'],
                lineColors: ['#ff9b44','#fc6075'],
                lineWidth: '3px',
                resize: true,
                redraw: true
            });
            <?php endif; ?>
            <?php endif; ?>

            <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-expenses')): ?>
            <?php if(!empty($monthly_expense)): ?>
            Morris.Bar({
                element: 'monthly_expense_barchart',
                data: [
                    <?php if(!empty($monthly_expense)): ?>
                    <?php $__currentLoopData = $monthly_expense; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $m = $key;
                    ?>
                    { y: "<?php echo e(\Carbon\Carbon::create(0,$m,1)->format('M')); ?>", a: "<?php echo e(!empty($expense->get($key)) ? $expense->get($key)->sum('amount'): 0); ?>", b: "<?php echo e(!empty($expense->get($key)) ? $expense->get($key)->count() : 0); ?>"},
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                ],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: [`Total Expense (${currency_symbol})`, 'Total Expenses'],
                lineColors: ['#ff9b44','#fc6075'],
                lineWidth: '3px',
                barColors: ['#ff9b44','#fc6075'],
                resize: true,
                redraw: true
            });
            <?php endif; ?>
            <?php endif; ?>
        });

        <?php if(session('events')): ?>
            <?php $__currentLoopData = session('events'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                Livewire.dispatch('<?php echo e($event); ?>');
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </script>
<?php $__env->stopPush(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/dashboards/admin.blade.php ENDPATH**/ ?>