<div class="btn-group">

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view', $leave)): ?>
        <a href="<?php echo e(route('leaves.show', $leave)); ?>"
           class="btn btn-sm btn-info">
            <i class="fa-solid fa-eye"></i> View
        </a>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $leave)): ?>
        <a href="<?php echo e(route('leaves.edit', $leave)); ?>"
           class="btn btn-sm btn-primary">
            <i class="fa-solid fa-pen"></i> Update
        </a>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', $leave)): ?>
        <a href="<?php echo e(route('leaves.approve.edit', $leave)); ?>"
        class="btn btn-sm btn-success">
            <i class="fa-solid fa-check"></i> Approve
        </a>
    <?php endif; ?>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $leave)): ?>
        <form action="<?php echo e(route('leaves.destroy', $leave)); ?>"
              method="POST"
              style="display:inline-block"
              onsubmit="return confirm('Are you sure?');">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button class="btn btn-sm btn-danger">
                <i class="fa-solid fa-trash"></i> Delete
            </button>
        </form>
    <?php endif; ?>

</div>



<!--
<?php if (\Illuminate\Support\Facades\Blade::check('activeAnyCan', ['view-leave', 'edit-leave', 'delete-leave'])): ?>
<div class="btn-group">

    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'view-leave')): ?>
        <a href="<?php echo e(route('leaves.show', ['leave' => $leave->id])); ?>"
           class="btn btn-sm btn-info">
            <i class="fa-solid fa-eye"></i> View
        </a>
    <?php endif; ?>

    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'edit-leave')): ?>
        <a href="<?php echo e(route('leaves.edit', ['leave' => $leave->id])); ?>"
           class="btn btn-sm btn-primary">
            <i class="fa-solid fa-pen"></i> Update
        </a>
    <?php endif; ?>

    <?php if (\Illuminate\Support\Facades\Blade::check('activeCan', 'delete-leave')): ?>
    <form action="<?php echo e(route('leaves.destroy', ['leave' => $leave->id])); ?>"
        method="POST"
        style="display:inline-block;"
        onsubmit="return confirm('Are you sure you want to delete this leave request?');">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fa-solid fa-trash"></i> Delete
        </button>
    </form>
    <?php endif; ?>


</div>
<?php endif; ?>
-->


<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/leaves/partials/actions.blade.php ENDPATH**/ ?>