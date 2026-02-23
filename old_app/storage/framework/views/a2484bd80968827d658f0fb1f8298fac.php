    <!-- Navbar -->
    <style>
        .nav-link {
            font-size: 18px !important;
            color: black !important;
        }
    </style>

    <nav class="navbar navbar-expand-lg fixed-top glass" style="z-index: 999;">
        <div class="container-fluid px-3 px-md-4 px-lg-5">
            <!-- <a class="navbar-brand fw-bold" href="#">RenownCRM</a> -->
            <div>
                <a href="<?php echo e(route('front')); ?>" class="nav-link">
                    <img src="<?php echo e(asset('images/front/logo-home.png')); ?>" alt="RenownCRM" class="m-0 p-0"
                        style="height:50px;">
                    
                </a>
            </div>

            <a class="navbar-toggler p-0" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border: none;">
                <span class="navbar-toggler-icon"></span>
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('front')); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/#features')); ?>">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/#pricing')); ?>">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/#how-it-works')); ?>">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/#testimonials')); ?>">Testimonials</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(url('/#contact')); ?>">Contact</a></li>

                    <!-- Divider (optional for spacing) -->
                    <li class="nav-item d-none d-lg-block mx-2">
                        <span class="text-muted mx-5">|</span>
                    </li>

                    <?php if(Auth::check()): ?>
                        <div>
                            <li class="nav-item">
                                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-sm text-white"
                                    style="background: var(--gradient-blue);">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                        </div>

                    <?php else: ?>
                        <div class="d-flex">
                            <!-- Login Button -->
                            <li class="nav-item">
                                <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-sm me-2 border-0" id="login-btn">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </a>
                            </li>
                            <!-- Sign Up Button -->

                            
                        </div>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav><?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/pages/front/layouts/navbar.blade.php ENDPATH**/ ?>