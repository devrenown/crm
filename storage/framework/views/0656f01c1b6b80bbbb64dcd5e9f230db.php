<div class="chat-main-wrapper">
    <!-- Chats View -->
    <div class="col-lg-9 message-view task-view">
        <div class="chat-window">
            <!--[if BLOCK]><![endif]--><?php if(!empty($user)): ?>
            <div class="fixed-header">
                <div class="navbar">
                    <div class="user-details me-auto">
                        <div class="float-start user-img">
                            <a class="avatar" href="#" title="<?php echo e($user->fullname); ?>">
                                <img src="<?php echo e(!empty($user->avatar) ? asset('storage/users/'.$user->avatar) : asset('images/user.jpg')); ?>" alt="User Image" class="rounded-circle">
                                <!--[if BLOCK]><![endif]--><?php if(!empty($user->is_online)): ?> 
                                <span class="status online"></span> 
                                <?php else: ?>
                                <span class="status offline"></span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </a>
                        </div>
                        <div class="user-info float-start">
                            <a href="#" title="<?php echo e($user->fullname); ?>"><span><?php echo e($user->fullname); ?></span> </a>
                            <!--[if BLOCK]><![endif]--><?php if(!$user->is_online && !empty($lastMessage)): ?>
                            <span class="last-seen"><?php echo e(__('Last seen')); ?> <?php echo e($lastMessage->created_at->diffForHumans() ?? ''); ?></span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                    
                    <ul class="nav custom-menu">
                        <li class="nav-item">
                            <a class="nav-link task-chat profile-rightbar float-end" id="task_chat" href="#task_window"><i class="fa-solid fa-user"></i></a>
                        </li>
                        <li class="nav-item dropdown dropdown-action">
                            <a aria-expanded="false" data-bs-toggle="dropdown" class="nav-link dropdown-toggle" href=""><i class="fa-solid fa-gear"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" data-route="<?php echo e(route('chat.delete-conversation',[
                                    'receiver' => $user->id,
                                ])); ?>" data-title="<?php echo e(__('Delete Conversation')); ?>"
                                data-question="<?php echo e(__('Are you sure you want to delete your chat?')); ?>" class="dropdown-item deleteBtn"><?php echo e(__('Delete Conversation')); ?></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <blockquote></blockquote>
            <div class="chat-contents">
                <div class="chat-content-wrap">
                    <div class="chat-wrap-inner" x-data id="chatContent">
                        <div class="chat-box">
                            <div class="chats">
                                <!--[if BLOCK]><![endif]--><?php if(!empty($messages) > 0): ?>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <!--[if BLOCK]><![endif]--><?php if(auth()->user()->id === $message->user_id): ?>
                                    <div class="chat chat-right">
                                        <div class="chat-body">
                                            <div class="chat-bubble">
                                                <div class="chat-content">
                                                    <p><?php echo e($message->body); ?></p>
                                                    <span class="chat-time"><?php echo e(format_date($message->created_at, 'H:i a')); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    
                                    <div class="chat chat-left">
                                        <div class="chat-avatar">
                                            <a href="#" class="avatar">
                                                <img src="<?php echo e(!empty($message->sender->avatar) ? asset('storage/users/'.$message->sender->avatar): asset('images/user.jpg')); ?>" alt="<?php echo e(__('Avatar')); ?>">
                                            </a>
                                        </div>
                                        <div class="chat-body">
                                            <div class="chat-bubble">
                                                <div class="chat-content">
                                                    <?php echo e($message->body); ?>

                                                    <span class="chat-time"><?php echo e(format_date($message->created_at,'H:i a')); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <!--[if BLOCK]><![endif]--><?php if($message->created_at->format('y-m-d') != now()->format('y-m-d')): ?>
                                    <div class="chat-line">
                                        <span class="chat-date"><?php echo e($message->created_at->format('d M, Y')); ?></span>
                                    </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]--> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chat-footer" x-data>
                <div class="message-bar">
                    <div class="message-inner">
                        <button type="button" class="btn btn-custom emoji-button">
                            <i class="fa-solid fa-smile"></i>
                        </button>
                        <div class="message-area">
                            <div class="input-group">
                                <!-- <textarea class="form-control" wire:model="messageBody" wire:keydown.enter.prevent="$wire.sendMessage" @keyup.enter="$wire.sendMessage" id="messageInput" placeholder="Type message..."></textarea> -->

                                <textarea
                                    class="form-control"
                                    wire:model.defer="messageBody"
                                    wire:keydown.enter.prevent="$wire.sendMessage"
                                    id="messageInput"
                                    placeholder="Type message...">
                                </textarea>
                                <button class="btn btn-custom" type="button" wire:click="sendMessage"><i class="fa-solid fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="d-flex align-items-center justify-content-center my-5">
                <h4 class="text-info">Select User to Chat</h4>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->       
        </div>
            <?php
        $__scriptKey = '342503476-0';
        ob_start();
    ?>
        <script type="module" defer>
            Livewire.on('scroll-chat', () => {
                $wire.scrollDown()
            })
        </script>
            <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
    </div>
    <!-- /Chats View -->
    <!--[if BLOCK]><![endif]--><?php if(!empty($user)): ?>
    <!-- Chat Right Sidebar -->
    <div class="col-lg-3 message-view chat-profile-view chat-sidebar" id="task_window">
        <div class="chat-window video-window">
            <div class="fixed-header">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item"><a class="nav-link active" href="#profile_tab" data-bs-toggle="tab"><?php echo e(__('Profile')); ?></a></li>
                </ul>
            </div>
            <div class="tab-content chat-contents">
                <div class="content-full tab-pane show active" id="profile_tab">
                    <div class="display-table">
                        <div class="table-row">
                            <div class="table-body">
                                <div class="table-content">
                                    <div class="chat-profile-img">
                                        <div class="edit-profile-img">
                                            <img src="<?php echo e(!empty($user->avatar) ? asset('storage/users/'.$user->avatar): asset('images/user.jpg')); ?>" alt="<?php echo e(__('Avatar')); ?>">
                                            <span class="change-img">Change Image</span>
                                        </div>
                                        <h3 class="user-name m-t-10 mb-0"><?php echo e($user->fullname); ?></h3>
                                        <!--[if BLOCK]><![endif]--><?php if(($user->type === \App\Enums\UserType::EMPLOYEE) && !empty($user->employeeDetail->designation)): ?>
                                        <small class="text-muted"><?php echo e($user->employeeDetail->designation->name ?? ''); ?></small>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <div class="chat-profile-info">
                                        <ul class="user-det-list">
                                            <!--[if BLOCK]><![endif]--><?php if(!empty($user->username)): ?>
                                            <li>
                                                <span><?php echo e(__('Username')); ?>:</span>
                                                <span class="float-end text-muted"><?php echo e($user->username); ?></span>
                                            </li>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <!--[if BLOCK]><![endif]--><?php if(!empty($user->dob)): ?>
                                                <li>
                                                    <span><?php echo e(__('DOB')); ?>:</span>
                                                    <span class="float-end text-muted"><?php echo e(format_date($user->employeeDetail->dob)); ?>                                                    </span>
                                                </li>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <!--[if BLOCK]><![endif]--><?php if(!empty($user->email)): ?>
                                            <li>
                                                <span><?php echo e(__('Email')); ?>:</span>
                                                <span class="float-end text-muted"><?php echo e($user->email); ?></span>
                                            </li>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <!--[if BLOCK]><![endif]--><?php if(!empty($user->phone)): ?>
                                            <li>
                                                <span><?php echo e(__('Phone')); ?>:</span>
                                                <span class="float-end text-muted"><?php echo e($user->phoneNumber); ?></span>
                                            </li>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Chat Right Sidebar -->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/livewire/apps/chat/box.blade.php ENDPATH**/ ?>