<?php
    $error = $errors->has($name) ? 'is-invalid' : '';
?>
<input <?php echo $attributes->merge(['class' => "form-control ". $error]); ?>>
<!--[if BLOCK]><![endif]--><?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
<div class="invalid-feedback">
   <?php echo e($message); ?>

</div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/components/form/input.blade.php ENDPATH**/ ?>