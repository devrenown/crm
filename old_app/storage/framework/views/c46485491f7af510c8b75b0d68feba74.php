<div class="header">

    <!-- Logo -->
    <?php if (isset($component)) { $__componentOriginal987d96ec78ed1cf75b349e2e5981978f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $attributes = $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $component = $__componentOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
    <!-- /Logo -->

    <?php if(!request()->is('onboarding/welcome/*') && !request()->is('onboarding') && !request()->is('onboarding/start/*')): ?>
        <a id="toggle_btn" href="javascript:void(0);">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>
    <?php endif; ?>


    <!-- Header Title -->
    <div class="page-title-box">
        <h3><?php echo e(Theme('name') ?? config('app.name')); ?></h3>
    </div>
    <!-- /Header Title -->

    <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa-solid fa-bars"></i></a>

    <!-- Header Menu -->
    <ul class="nav user-menu">


        <li class="nav-item dropdown has-arrow main-drop">
            <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <span class="user-img"><img
                        src="<?php echo e(asset( auth()->user()->avatar ? 'storage/users/' .auth()->user()->avatar : 'images/user.jpg')); ?>"
                        alt="User Image" style="height: 40px; width: 40px; object-fit: cover;">
                    <span class="status online"></span></span>
                <span><?php echo e(auth()->user()->fullname); ?></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="<?php echo e(route('profile')); ?>"><?php echo e(__('My Profile')); ?></a>
                <a onclick="document.getElementById('logout_user_form').submit()" class="dropdown-item logout_btn"
                    href="javascript:void(0);">Logout</a>
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                class="fa-solid fa-ellipsis-vertical"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="<?php echo e(route('profile')); ?>">My Profile</a>
            <a onclick="document.getElementById('logout_user_form').submit()" class="dropdown-item logout_btn"
                href="javascript:void(0);">Logout</a>
        </div>
    </div>
    <!-- /Mobile Menu -->
    <form action="<?php echo e(route('logout')); ?>" id="logout_user_form" method="post"><?php echo csrf_field(); ?></form>

</div><?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/partials/header.blade.php ENDPATH**/ ?>