<div class="page-header">
    <div class="row <?php echo e($alignment ?? ''); ?>">
        <div class="<?php echo e($class ?? 'col-sm-12'); ?>">
            <?php if(isset($title)): ?>
                <h3 class="page-title"><?php echo e($title); ?></h3>
            <?php endif; ?>
            <ul class="breadcrumb">
                <?php echo e($slot); ?>

            </ul>
        </div>
        <?php if(isset($right)): ?>
            <?php echo $right; ?>

        <?php endif; ?>
    </div>
</div>
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/components/breadcrumb.blade.php ENDPATH**/ ?>