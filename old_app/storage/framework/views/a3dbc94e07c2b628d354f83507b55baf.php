<form action="<?php echo e(route('switch.role')); ?>" method="POST" class="d-inline">
    <?php echo csrf_field(); ?>
    <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
        <?php if(auth()->user()->roles): ?>
            <?php $__currentLoopData = auth()->user()->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($role->name); ?>" <?php echo e(session('active_role') === $role->name ? 'selected' : ''); ?>>
                    <?php echo e($role->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </select>
</form>

<?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/components/role-switcher.blade.php ENDPATH**/ ?>