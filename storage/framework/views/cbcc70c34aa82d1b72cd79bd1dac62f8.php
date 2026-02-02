<!-- Logo -->
<?php
  $theme = app(\App\Settings\ThemeSettings::class);
?>
<div class="header-left">
    <?php if($theme->color_scheme == 'maroon' || $theme->color_scheme == 'light'): ?>
    <a href="<?php echo e(route('dashboard')); ?>" class="logo2">
        <img src="<?php echo e(Theme('logo_dark') ? asset('storage/settings/theme/' . Theme('logo_dark')) : asset('images/logo2.png')); ?>" style="height: 40px; width: auto;" alt="Logo">
    </a>
    <?php else: ?>
    <a href="<?php echo e(route('dashboard')); ?>" class="logo">
        <img src="<?php echo e(Theme('logo_light') ? asset('storage/settings/theme/' . Theme('logo_light')) : asset('images/logo.png')); ?>" style="height: 40px; width: auto;" alt="Logo">
    </a>
    <?php endif; ?>
</div>
<!-- /Logo -->
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/components/logo.blade.php ENDPATH**/ ?>