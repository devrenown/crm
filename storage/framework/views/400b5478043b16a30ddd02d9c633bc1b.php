<?php $__env->startPush('page-style'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-content'); ?>

	<div class="content container-fluid">

        <!-- Page Header -->
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['class' => 'col']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col']); ?>
             <?php $__env->slot('title', null, []); ?> <?php echo e(__('Organizations')); ?> <?php $__env->endSlot(); ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                    <?php echo e(__('Organizations')); ?>

                </li>
            </ul>
             <?php $__env->slot('right', null, []); ?> 
                <div class="col-auto float-end ms-auto">
                    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'create-organization')): ?>
                    <a href="javascript:void(0)" data-url="<?php echo e(route('tenant.create')); ?>" class="btn add-btn"
                        data-ajax-modal="true" data-size="lg" data-title="Add Organization">
                        <i class="fa-solid fa-plus"></i> <?php echo e(__('Add Organization')); ?>

                    </a>
                    <?php endif; ?>
                    <div class="view-icons">
                        <a href="<?php echo e(route('tenant.index')); ?>" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
                        <a href="#" class="list-view btn btn-link"><i class="fa-solid fa-bars"></i></a>
                    </div>
                </div>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
        <!-- /Page Header -->

        <div class="row staff-grid-row">
            <?php if(!empty($tenants)): ?>
                <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php
	                $theme      = $settings[$tenant->id]['theme'] ?? collect();
	        		$company    = $settings[$tenant->id]['company'] ?? collect();

	        		$logo = $theme->firstWhere('name', 'logo_dark')
	             	?? $theme->firstWhere('name', 'logo_light');

	             	$adr = $company->firstWhere('name', 'address');
	             	$address = trim($adr->payload, '"');

	             	$file = trim($logo->payload, '"');

	             	$badge = match($tenant->status) {
				        \App\Enums\TenantStatus::ACTIVE => 'success',
				        \App\Enums\TenantStatus::INACTIVE => 'warning',
				        default => 'danger',
				    };
             	?>

                <div class="col-md-4 col-sm-6 col-12 col-lg-4">
                    <div class="card">

                    	<?php if($logo): ?>
                    	<div class="p-3">
                        	<img src="<?php echo e($file ? asset('storage/settings/theme/' . $file) : asset('images/company-placeholder.png')); ?>" class="card-img-top" alt="Organization" style="height: 6rem; object-fit: contain;">
                        </div>
                        <?php endif; ?>

                        <div class="dropdown profile-action">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            
                            <div class="dropdown-menu dropdown-menu-right">

                            	<a class="dropdown-item" href="<?php echo e(route('tenant.show', encrypt($tenant->id))); ?>">
                                    <i class="fa-solid fa-eye m-r-5"></i>
                                    <?php echo e(__('View Details')); ?>

                                </a>
                                
                                <a class="dropdown-item" href="javascript:void(0)" data-url="<?php echo e(route('tenant.edit', $tenant->id)); ?>" data-ajax-modal="true"
                                    data-title="Edit Organization" data-size="lg">
                                    <i class="fa-solid fa-pencil m-r-5"></i>
                                    <?php echo e(__('Edit')); ?>

                                </a>
                               
                                
                                
                            </div>
                            
                        </div>

                        <div class="p-3">
                        	<h4 class="card-title mb-2"><?php echo e($tenant->name); ?></h4>
                        	<div class="small text-muted"><?php echo e($address ?? 'N/A'); ?></div>
                        </div>

                        <div class="bg-light pt-3 px-3 row">
                        	<p class="col-6">Status:</p>
                        	<div class="col-6">
                        		<span class="badge bg-inverse-<?php echo e($badge); ?> "><?php echo e($tenant->status); ?></span>
                        	</div>

                        	<p class="col-6">Join Date:</p>
                        	<p class="col-6"><?php echo e(date('d M Y', strtotime($tenant->created_at))); ?></p>

                        	<p class="col-lg-4">Domain:</p>
                        	<small class="col-lg-8"><?php echo e($tenant->domain); ?> <a href="<?php echo e('https://' . $tenant->domain); ?>" target="_blank" title="Visit"><i class="bi bi-box-arrow-up-right ms-2"></i> </a></small>
                        </div>

                        
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/tenant/index.blade.php ENDPATH**/ ?>