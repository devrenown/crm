<div class="modal-body">
    <form action="<?php echo e(route('employee.emergency-contacts', $employeeDetail->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
      <div class="card">
        <div class="card-body">
          <h3 class="card-title"><?php echo e(__('Primary Contact')); ?></h3>
          <div class="row">
            <div class="col-md-6">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['required' => true]); ?> <?php echo e(__('Name')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'text','name' => 'primary[name]','value' => ''.e($employeeDetail->emergency_contacts['primary']['name'] ?? '').'','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'primary[name]','value' => ''.e($employeeDetail->emergency_contacts['primary']['name'] ?? '').'','required' => true]); ?>
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
            <div class="col-md-6">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['required' => true]); ?> <?php echo e(__('Relationship')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'text','name' => 'primary[relationship]','value' => ''.e($employeeDetail->emergency_contacts['primary']['relationship'] ?? '').'','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'primary[relationship]','value' => ''.e($employeeDetail->emergency_contacts['primary']['relationship'] ?? '').'','required' => true]); ?>
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

            <div class="col-md-6">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['required' => true]); ?> <?php echo e(__('Phone')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalacf9423fc862a8f1bed137bbf97563ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalacf9423fc862a8f1bed137bbf97563ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.phone','data' => ['type' => 'text','name' => 'primary[phone]','value' => ''.e($employeeDetail->emergency_contacts['primary']['phone'] ?? '').'','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.phone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'primary[phone]','value' => ''.e($employeeDetail->emergency_contacts['primary']['phone'] ?? '').'','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalacf9423fc862a8f1bed137bbf97563ec)): ?>
<?php $attributes = $__attributesOriginalacf9423fc862a8f1bed137bbf97563ec; ?>
<?php unset($__attributesOriginalacf9423fc862a8f1bed137bbf97563ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalacf9423fc862a8f1bed137bbf97563ec)): ?>
<?php $component = $__componentOriginalacf9423fc862a8f1bed137bbf97563ec; ?>
<?php unset($__componentOriginalacf9423fc862a8f1bed137bbf97563ec); ?>
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
            <div class="col-md-6">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => ['required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['required' => true]); ?> <?php echo e(__('Address')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'text','name' => 'primary[address]','value' => ''.e($employeeDetail->emergency_contacts['primary']['address'] ?? '').'','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'primary[address]','value' => ''.e($employeeDetail->emergency_contacts['primary']['address'] ?? '').'','required' => true]); ?>
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

          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h3 class="card-title"><?php echo e(__('Secondary Contact')); ?></h3>
          <div class="row">
            <div class="col-md-6">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo e(__('Name')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'text','name' => 'secondary[name]','value' => ''.e($employeeDetail->emergency_contacts['secondary']['name'] ?? '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'secondary[name]','value' => ''.e($employeeDetail->emergency_contacts['secondary']['name'] ?? '').'']); ?>
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
            <div class="col-md-6">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo e(__('Relationship')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'text','name' => 'secondary[relationship]','value' => ''.e($employeeDetail->emergency_contacts['secondary']['relationship'] ?? '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'secondary[relationship]','value' => ''.e($employeeDetail->emergency_contacts['secondary']['relationship'] ?? '').'']); ?>
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

            <div class="col-md-6">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo e(__('Phone')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $attributes = $__attributesOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__attributesOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal306f477fe089d4f950325a3d0a498c1c)): ?>
<?php $component = $__componentOriginal306f477fe089d4f950325a3d0a498c1c; ?>
<?php unset($__componentOriginal306f477fe089d4f950325a3d0a498c1c); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginalacf9423fc862a8f1bed137bbf97563ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalacf9423fc862a8f1bed137bbf97563ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.phone','data' => ['type' => 'text','name' => 'secondary[phone]','value' => ''.e($employeeDetail->emergency_contacts['secondary']['phone'] ?? '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.phone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'secondary[phone]','value' => ''.e($employeeDetail->emergency_contacts['secondary']['phone'] ?? '').'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalacf9423fc862a8f1bed137bbf97563ec)): ?>
<?php $attributes = $__attributesOriginalacf9423fc862a8f1bed137bbf97563ec; ?>
<?php unset($__attributesOriginalacf9423fc862a8f1bed137bbf97563ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalacf9423fc862a8f1bed137bbf97563ec)): ?>
<?php $component = $__componentOriginalacf9423fc862a8f1bed137bbf97563ec; ?>
<?php unset($__componentOriginalacf9423fc862a8f1bed137bbf97563ec); ?>
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
            <div class="col-md-6">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.label','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo e(__('Address')); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['type' => 'text','name' => 'secondary[address]','value' => ''.e($employeeDetail->emergency_contacts['secondary']['address'] ?? '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','name' => 'secondary[address]','value' => ''.e($employeeDetail->emergency_contacts['secondary']['address'] ?? '').'']); ?>
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

          </div>
        </div>
      </div>

      <div class="submit-section my-3">
        <button class="btn btn-primary submit-btn"><?php echo e(__('Submit')); ?></button>
      </div>
    </form>
  </div>
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/employees/modals/emergency-contacts.blade.php ENDPATH**/ ?>