<?php $__env->startSection('page-header-section'); ?>
    <!-- Page Header -->
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
         <?php $__env->slot('title', null, []); ?> <?php echo e(__('Theme Settings')); ?> <?php $__env->endSlot(); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-section'); ?>
    <form action="<?php echo e(route('settings.theme.update')); ?>" method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-12">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?><?php echo e(__('App Name')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'text','name' => 'name','value' => ''.e($settings->name).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'name','value' => ''.e($settings->name).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
            <div class="col-12">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => ['class' => 'row']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'row']); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?><?php echo e(__('Logo Light')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <div class="col-lg-10">
                        <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'file','name' => 'logo_light','onchange' => 'document.getElementById(\'logo_light\').src = window.URL.createObjectURL(this.files[0])']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'file','name' => 'logo_light','onchange' => 'document.getElementById(\'logo_light\').src = window.URL.createObjectURL(this.files[0])']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                        <span class="form-text text-muted"><?php echo e(__('Recommended image size is 40px x 40px')); ?></span>
                    </div>
                    <div class="col-lg-2">
                        <div class="img-thumbnail float-end"><img id="logo_light"
                                src="<?php echo e(!empty($settings->logo_light) ? asset('storage/settings/theme/' . $settings->logo_light) : asset('images/logo2.png')); ?>"
                                alt="logo light" width="40" height="40"></div>
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
            <div class="col-12">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => ['class' => 'row']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'row']); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?><?php echo e(__('Logo Dark')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <div class="col-lg-10">
                        <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'file','name' => 'logo_dark','onchange' => 'document.getElementById(\'logo_dark\').src = window.URL.createObjectURL(this.files[0])']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'file','name' => 'logo_dark','onchange' => 'document.getElementById(\'logo_dark\').src = window.URL.createObjectURL(this.files[0])']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                        <span class="form-text text-muted"><?php echo e(__('Recommended image size is 40px x 40px')); ?></span>
                    </div>
                    <div class="col-lg-2">
                        <div class="img-thumbnail float-end"><img id="logo_dark"
                                src="<?php echo e(!empty($settings->logo_dark) ? asset('storage/settings/theme/' . $settings->logo_dark) : asset('images/logo2.png')); ?>"
                                alt="logo light" width="40" height="40"></div>
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
            <div class="col-12">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => ['class' => 'row']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'row']); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?><?php echo e(__('Favicon')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <div class="col-lg-10">
                        <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'file','name' => 'favicon','onchange' => 'document.getElementById(\'favicon\').src = window.URL.createObjectURL(this.files[0])']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'file','name' => 'favicon','onchange' => 'document.getElementById(\'favicon\').src = window.URL.createObjectURL(this.files[0])']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                        <span class="form-text text-muted"><?php echo e(__('Recommended image size is 16px x 16px')); ?></span>
                    </div>
                    <div class="col-lg-2">
                        <div class="img-thumbnail float-end">
                            <img id="favicon"
                                src="<?php echo e(!empty($settings->favicon) ? asset('storage/settings/theme/' . $settings->favicon) : asset('images/logo2.png')); ?>"
                                alt="Favicon img" width="16" height="16">
                        </div>
                    </div>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>

            
            <div class="col-sm-6">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?>Layout <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <select class="select" name="layout">
                        <option <?php echo e($settings->layout === 'vertical' ? 'selected' : ''); ?> value="vertical">Vertical
                        </option>
                        <option <?php echo e($settings->layout === 'horizontal' ? 'selected' : ''); ?> value="horizontal">Horizontal
                        </option>
                    </select>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
            <div class="col-sm-6">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?>Color Scheme <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <select class="select" name="color_scheme">
                        <option <?php echo e($settings->color_scheme === 'orange' ? 'selected' : ''); ?> value="orange">Orange</option>
                        <option <?php echo e($settings->color_scheme === 'light' ? 'selected' : ''); ?> value="light">Light</option>
                        <option <?php echo e($settings->color_scheme === 'dark' ? 'selected' : ''); ?> value="dark">Dark</option>
                        <option <?php echo e($settings->color_scheme === 'blue' ? 'selected' : ''); ?> value="blue">Blue</option>
                        <option <?php echo e($settings->color_scheme === 'maroon' ? 'selected' : ''); ?> value="maroon">Maroon</option>
                        <option <?php echo e($settings->color_scheme === 'purple' ? 'selected' : ''); ?> value="purple">Purple</option>
                    </select>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
            <div class="col-sm-6">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?>Layout Width <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <select class="select" name="layout_width">
                        <option <?php echo e($settings->layout_width === 'fluid' ? 'selected' : ''); ?> value="fluid">Fluid
                        </option>
                        <option <?php echo e($settings->layout_width === 'boxed' ? 'selected' : ''); ?> value="boxed">Boxed
                        </option>
                    </select>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
            <div class="col-sm-6">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?>Layout Position <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <select class="select" name="layout_pos">
                        <option <?php echo e($settings->layout_position === 'fixed' ? 'selected' : ''); ?> value="fixed">Fixed
                        </option>
                        <option <?php echo e($settings->layout_position === 'scrollable' ? 'selected' : ''); ?> value="scrollable">
                            Scrollable
                        </option>
                    </select>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
            <div class="col-sm-6">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?>Topbar Color <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <select class="select" name="topbar_color">
                        <option <?php echo e($settings->topbar_color === 'default' ? 'selected' : ''); ?> value="default">Default
                        </option>
                        <option <?php echo e($settings->topbar_color === 'light' ? 'selected' : ''); ?> value="light">Light</option>
                        <option <?php echo e($settings->topbar_color === 'dark' ? 'selected' : ''); ?> value="dark">Dark</option>
                    </select>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
            <div class="col-sm-6">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?><?php echo e(__('Sidebar Size')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <select class="select" name="sidebar_size">
                        <option <?php echo e($settings->sidebar_size === 'lg' ? 'selected' : ''); ?> value="default">
                            <?php echo e(__('Default')); ?></option>
                        <option <?php echo e($settings->sidebar_size === 'md' ? 'selected' : ''); ?> value="compact">
                            <?php echo e(__('Compact')); ?></option>
                        <option <?php echo e($settings->sidebar_size === 'sm-hover' ? 'selected' : ''); ?> value="compact">
                            <?php echo e(__('Small
                                                                                                                                                                                                                                                                                                                                                Hover View')); ?>

                        </option>
                    </select>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
            <div class="col-sm-6">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?><?php echo e(__('Sidebar View')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <select class="select" name="sidebar_view">
                        <option <?php echo e($settings->sidebar_view === 'lg' ? 'selected' : ''); ?> value="default">
                            <?php echo e(__('Default')); ?></option>
                        <option <?php echo e($settings->sidebar_view === 'detached' ? 'selected' : ''); ?> value="detached">
                            <?php echo e(__('Detached')); ?></option>
                    </select>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
            <div class="col-sm-6">
                <?php if (isset($component)) { $__componentOriginal4655fd8c65a18572fb62908c30ba0d39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-block','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-block'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal306f477fe089d4f950325a3d0a498c1c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal306f477fe089d4f950325a3d0a498c1c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['class' => 'col-form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'col-form-label']); ?><?php echo e(__('Sidebar Color')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <select class="select" name="sidebar_color">
                        <option <?php echo e($settings->sidebar_color === 'light' ? 'selected' : ''); ?> value="light">
                            <?php echo e(__('Light')); ?></option>
                        <option <?php echo e($settings->sidebar_color === 'dark' ? 'selected' : ''); ?> value="dark">
                            <?php echo e(__('Dark')); ?></option>
                        <option <?php echo e($settings->sidebar_color === 'gradient' ? 'selected' : ''); ?> value="gradient">
                            <?php echo e(__('Gradient')); ?></option>
                    </select>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $attributes = $__attributesOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__attributesOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39)): ?>
<?php $component = $__componentOriginal4655fd8c65a18572fb62908c30ba0d39; ?>
<?php unset($__componentOriginal4655fd8c65a18572fb62908c30ba0d39); ?>
<?php endif; ?>
            </div>
        </div>
        <div class="submit-section">
            <button class="btn btn-primary submit-btn"><?php echo e(__('Save')); ?></button>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('page-scripts'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('pages.settings.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/settings/theme.blade.php ENDPATH**/ ?>