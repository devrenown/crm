<!-- Favicon -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo e(Theme('favicon') ? asset('storage/settings/theme/' . Theme('favicon')) : Vite::asset('resources/assets/img/favicon.png')); ?>">

<link rel="stylesheet" href="<?php echo e(asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css')); ?>">
<!-- Jquery Steps CSS -->
<link rel="stylesheet" href="<?php echo e(asset('assets/plugins/jquery-steps-master/demo/css/jquery.steps.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/fullcalendar.min.css')); ?>">

<!-- Pdf viewer -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/5.4.149/pdf_viewer.min.css" integrity="sha512-qbvpAGzPFbd9HG4VorZWXYAkAnbwKIxiLinTA1RW8KGJEZqYK04yjvd+Felx2HOeKPDKVLetAqg8RIJqHewaIg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

<?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/css/bootstrap.min.css',
    'resources/assets/css/line-awesome.min.css',
    'resources/assets/css/material.css',
    'resources/assets/css/ckeditor.css',
    'resources/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
    'resources/assets/css/style.css',
    'resources/css/app.scss',
]); ?>
<!-- Vendor CSS -->
<?php echo $__env->yieldPushContent('vendor-styles'); ?>
<?php echo $__env->yieldContent('vendor-styles'); ?>
<!-- Custom CSS -->
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

<?php echo $__env->yieldPushContent('page-styles'); ?>
<?php echo $__env->yieldPushContent('style'); ?><?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/partials/styles.blade.php ENDPATH**/ ?>