<style>
    .birthday-admin-card {
        /*background: linear-gradient(145deg, #ff6ec4, #7873f5);*/
        background: url("<?php echo e(asset('images/birthday-background.png')); ?>");
        background-size: cover;
        border-radius: 22px;
        overflow: hidden;
        min-height: 320px;
    }

    .text-birthday {
        color: #3917BF;
    }

    .border-birthday {
        border-color: #3917BF !important;
    }

    .avatar-wrap {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        padding: 4px;
        background: linear-gradient(135deg, white, #ffffff);
    }

    .avatar-wrap img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .avatar-icon {
        width: 25px;
        height: 25px;
        background: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .birthday-badge {
        background: #ffeb3b;
        color: #083b6f;
        font-weight: 600;
    }

    .birthday-text {
        color: #ffeb3b;
        font-weight: 600;
        letter-spacing: .5px;
    }

    .years {
        font-size: 40px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .years span {
        text-shadow: 0 4px 0px rgba(255, 215, 0, 0.6);
    }

    .wave {
        position: absolute;
        bottom: -20px;
        left: 0;
        width: 100%;
        height: 70px;
        border-radius: 100% 100% 0 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .wave-blue {
        background: rgba(255,255,255,.18);
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-size: 70% 70%;
        filter: brightness(0) invert(1);
    }

    .cake {
        max-height: 3rem;
    }
</style>

<?php
use Carbon\Carbon;
$today = Carbon::today()->format('m-d');
?>

<div class="col-md-4 pe-lg-0">

    <div id="birthdayCarousel" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-inner">

            <?php if($upcomingBirthdays->count() > 0): ?>

                <?php $__currentLoopData = $upcomingBirthdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $years = Carbon::parse($user->dob)->diffInYears(Carbon::today());
                    ?>

                    <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?> mb-2">
                        <div class="birthday-admin-card text-center text-white p-4 position-relative">

                            <div class="avatar-wrap border border-3 border-birthday mx-auto position-relative shadow">
                                <img src="<?php echo e(asset('storage/users/' . $user->avatar)); ?>">
                                
                                <div class="avatar-icon position-absolute bottom-0 end-0 border border-birthday rounded-circle">
                                    <i class="fa-solid fa-gift text-birthday"></i>
                                </div>
                            </div>

                            <h5 class=" fs-5 mt-3 text-birthday fw-bold">
                                <?php echo e($user->fullname); ?>

                            </h5>

                            <div class="role text-dark small mb-3">
                                <i class="fa-solid fa-briefcase me-1"></i>
                                <?php echo e(@$user->employeeDetail->designation->name ?? 'Employee'); ?>

                            </div>

                            <div class="years">
                                <span class="text-birthday"><?php echo e(number_format($years)); ?> Years</span>
                            </div>

                            <div class="mt-3 text-birthday fw-semibold">
                                <p class="fs-5"><?php echo e(date('m-d', strtotime($user->dob)) == $today ? 'Today 🥳' :  date('d M', strtotime($user->dob))); ?></p>
                                <!-- <i class="fa-solid fa-crown me-1"></i> Happy Birthday -->
                            </div>

                            <div class="wave wave-blue">
                                <div class="border border-birthday mb-4 px-4 py-1 rounded-pill text-birthday shadow"><i class="fa-solid fa-gift me-1"></i> Happy Birthday</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php else: ?>

                <!-- NO BIRTHDAYS SLIDE -->
                <div class="carousel-item active mb-2">
                    <div class="birthday-admin-card text-center text-white p-4 position-relative">

                        <div class="avatar-wrap border border-3 border-birthday mx-auto d-flex align-items-center justify-content-center position-relative shadow">
                            <i class="fa-solid fa-cake-candles fa-3x text-muted"></i>

                            <div class="avatar-icon position-absolute bottom-0 end-0 border border-birthday rounded-circle">
                                <i class="fa-solid fa-gift text-birthday"></i>
                            </div>
                        </div>

                        <h5 class="mt-4 text-birthday fw-semibold">
                            No Birthdays
                        </h5>

                        <p class="text-dark small mt-2 mb-0">
                            No birthdays in the upcoming 7 days
                        </p>

                        <div class="wave wave-blue">
                            <div class="border border-birthday mb-4 px-4 py-1 rounded-pill text-birthday shadow"><i class="fa-solid fa-gift me-1"></i> Birthday</div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        </div>

        <!-- CONTROLS -->
        <?php if(count($upcomingBirthdays) > 1): ?>
            <button class="carousel-control-prev" type="button"
                data-bs-target="#birthdayCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button"
                data-bs-target="#birthdayCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        <?php endif; ?>
    </div>
</div>


<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/dashboards/events/birthday.blade.php ENDPATH**/ ?>