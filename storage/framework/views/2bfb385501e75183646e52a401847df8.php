<div>
    <li>
        <div class="input-group m-b-30">
            <input placeholder="Search ..." class="form-control search-input" wire:model.live.debounce.250ms="searchQuery" type="search">
        </div>
    </li>
    <!--[if BLOCK]><![endif]--><?php if(!empty($users) && ($users->count() > 0)): ?>
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <!--[if BLOCK]><![endif]--><?php if($user->id !== auth()->user()->id): ?>
        <li>
            <a href="<?php echo e(route('app.chat').'?contact='.\Crypt::encrypt($user->id)); ?>">
                <span class="chat-avatar-sm user-img">
                    <img class="rounded-circle" src="<?php echo e(!empty($user->avatar) ? asset('storage/users/'.$user->avatar): asset('images/user.jpg')); ?>" alt="<?php echo e(__('avatar')); ?>" style="object-fit: cover;">
                </span> 
                <?php
                $fullname = "$user->firstname $user->middlename $user->lastname";
            ?>
            <span class="chat-user"><?php echo e(strlen($fullname) > 12 ? trim(substr($fullname,0,12)).'..' : $fullname); ?></span>
            </a>
        </li>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <hr>
    
    <!--[if BLOCK]><![endif]--><?php if(!empty($chats) && $chats->count() > 0): ?>
    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $chats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $contactId = \Crypt::encrypt($user->id);
        $requestContact = request()->input('contact');
        $unreadMessages = \App\Models\ChatMessage::where('user_id', $user->id)
                        ->where('receiver_id', auth()->user()->id)
                        ->where('is_read', false)->count();
    ?>
    <li class="<?php echo e(!empty($requestContact) && ($user->id == \Crypt::decrypt($requestContact)) ? 'active': ''); ?>">
        <a href="<?php echo e(route('app.chat').'?contact='.$contactId); ?>">
            <span class="chat-avatar-sm user-img">
                <img class="rounded-circle" src="<?php echo e(!empty($user->avatar) ? asset('storage/users/'.$user->avatar): asset('images/user.jpg')); ?>" alt="<?php echo e(__('avatar')); ?>" style="object-fit: cover;">
                <!--[if BLOCK]><![endif]--><?php if(!empty($user->is_online)): ?> 
                <span class="status online"></span> 
                <?php else: ?>
                <span class="status offline"></span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </span> 
            <?php
                $fullname = "$user->firstname $user->middlename $user->lastname";
            ?>
            <span class="chat-user"><?php echo e(strlen($fullname) > 12 ? trim(substr($fullname,0,12)).'..' : $fullname); ?></span> 
            <!--[if BLOCK]><![endif]--><?php if($unreadMessages > 0): ?>
            <span class="badge rounded-pill bg-danger"><?php echo e($unreadMessages ?? 0); ?></span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </a>
    </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/livewire/apps/chat/sidebar.blade.php ENDPATH**/ ?>