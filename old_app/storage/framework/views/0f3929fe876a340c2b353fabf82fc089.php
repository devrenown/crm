<label <?php echo $attributes->merge(['class' => 'col-form-label']); ?>>
    <?php echo e($slot); ?>

    <!--[if BLOCK]><![endif]--><?php if(!empty($required) || !empty($mandatory)): ?>
        <span class="text-danger">*</span>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</label>
<?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/components/form/label.blade.php ENDPATH**/ ?>