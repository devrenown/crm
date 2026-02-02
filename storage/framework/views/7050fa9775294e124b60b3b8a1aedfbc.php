<?php $__env->startPush('page-styles'); ?>
    <!-- Page Css -->
    <meta name="url" content="<?php echo e(url('').'/'.config('chatify.routes.prefix')); ?>" data-user="<?php echo e(Auth::user()->id); ?>">
    <!-- /Page Css -->

    <style>

        .chat-main-row,
        .chat-main-wrapper,
        .chat-window,
        .chat-contents,
        .chat-content-wrap,
        .chat-wrap-inner,
        .chat-box,
        .chats {
            max-width: 100%;
            overflow-x: hidden;
        }

        .chat-avatar-sm {
            width: 30px !important;
            height: 30px !important;
        }

        .chat-avatar-sm img {
            width: 100%;
            object-fit: cover !important;
        }

        @media (max-width: 768px) {
            .emoji-button {
                display: none !important;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('sidebar'); ?>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <nav class="greedy">
                    <ul class="link-item">
                        <li>
                            <a href="<?php echo e(route('dashboard')); ?>"><i class="la la-dashboard"></i> <span><?php echo e(__('Back To Dashboard')); ?></span></a>
                        </li>
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('apps.chat.sidebar');

$__html = app('livewire')->mount($__name, $__params, 'lw-839073960-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- /Sidebar -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-content'); ?>
    <!-- Chat Main Row -->
    <div class="chat-main-row">

        <!-- Chat Main Wrapper -->
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('apps.chat.box',['userId' => request()->get('contact')]);

$__html = app('livewire')->mount($__name, $__params, 'lw-839073960-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>            
        <!-- /Chat Main Wrapper -->

    </div>
    <!-- /Chat Main Row -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
<?php echo app('Illuminate\Foundation\Vite')([
    'resources/js/app/chat/chat-app.js'
]); ?>
<?php $__env->stopSection(); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const button = document.querySelector('.emoji-button');
    const textarea = document.getElementById('messageInput');

    if (!button || !textarea) return;

    const picker = new EmojiButton({
        position: 'top-start',
        theme: 'light'
    });

    picker.on('emoji', emoji => {
        textarea.value += emoji;
        textarea.dispatchEvent(new Event('input'));
        textarea.focus();
    });

    button.addEventListener('click', () => {
        picker.togglePicker(button);
    });
});
</script>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/apps/chat.blade.php ENDPATH**/ ?>