<div class="chat-main-wrapper">
    <!-- Chats View -->
    <div class="col-lg-9 message-view task-view">
        <div class="chat-window">
            @if (!empty($user))
            <div class="fixed-header">
                <div class="navbar">
                    <div class="user-details me-auto">
                        <div class="float-start user-img">
                            <a class="avatar" href="#" title="{{ $user->fullname }}">
                                <img src="{{ !empty($user->avatar) ? asset('storage/'.$user->avatar) : asset('images/user.jpg') }}" alt="User Image" class="rounded-circle">
                                @if (!empty($user->is_online)) 
                                <span class="status online"></span> 
                                @else
                                <span class="status offline"></span>
                                @endif
                            </a>
                        </div>
                        <div class="user-info float-start">
                            <a href="#" title="{{ $user->fullname }}"><span>{{ $user->fullname }}</span> </a>
                            @if (!$user->is_online && !empty($lastMessage))
                            <span class="last-seen">{{ __('Last seen') }} {{ $lastMessage->created_at->diffForHumans() ?? '' }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <ul class="nav custom-menu">
                        <li class="nav-item">
                            <a class="nav-link task-chat profile-rightbar float-end" id="task_chat" href="#task_window"><i class="fa-solid fa-user"></i></a>
                        </li>
                        <li class="nav-item dropdown dropdown-action">
                            <a aria-expanded="false" data-bs-toggle="dropdown" class="nav-link dropdown-toggle" href=""><i class="fa-solid fa-gear"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" data-route="{{ route('chat.delete-conversation',[
                                    'receiver' => $user->id,
                                ]) }}" data-title="{{ __('Delete Conversation') }}"
                                data-question="{{ __('Are you sure you want to delete your chat?') }}" class="dropdown-item deleteBtn">{{ __('Delete Conversation') }}</a>
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
                                @if ($messages && $messages->count() > 0)
                                    
                                    @foreach ($messages as $index => $message)

                                        {{-- Date Separator --}}
                                        @if ($index == 0 || $messages[$index - 1]->created_at->format('Y-m-d') != $message->created_at->format('Y-m-d'))
                                            <div class="chat-line">
                                                <span class="chat-date">
                                                    {{ $message->created_at->format('d M, Y') }}
                                                </span>
                                            </div>
                                        @endif
                                    
                                        @if (auth()->user()->id == $message->user_id)
                                    
                                            <div class="chat chat-right" wire:key="message-{{ $message->id }}">
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p class="mb-0 text-break" style="white-space: pre-wrap;">{{ $message->body }}</p>
                                    
                                                            @if ($message->getMedia('chat-attachments')->count())

                                                                <div class="mt-2 d-flex flex-column gap-2">
                                                                
                                                                        @foreach ($message->getMedia('chat-attachments') as $media)
                                                                
                                                                            @php
                                                                                $mime = $media->mime_type;
                                                                                $isImage = str_contains($mime, 'image');
                                                                            @endphp
                                                                
                                                                            @if ($isImage)
                                                                
                                                                                {{-- Image Attachment --}}
                                                                                <a
                                                                                    href="{{ $media->getUrl() }}"
                                                                                    target="_blank"
                                                                                    class="d-inline-block"
                                                                                    style="z-index: 999;"
                                                                                >
                                                                                    <img
                                                                                        src="{{ $media->getUrl() }}"
                                                                                        alt="Attachment"
                                                                                        style="
                                                                                            max-width:220px;
                                                                                            max-height:220px;
                                                                                            border-radius:12px;
                                                                                            object-fit:cover;
                                                                                            border:1px solid #ddd;
                                                                                        "
                                                                                    >
                                                                                </a>
                                                                
                                                                            @else
                                                                
                                                                                {{-- File Attachment --}}
                                                                                <a
                                                                                    href="{{ $media->getUrl() }}"
                                                                                    target="_blank"
                                                                                    class="d-flex align-items-center gap-2 p-2 border rounded text-decoration-none bg-light"
                                                                                    style="
                                                                                        max-width:250px;
                                                                                        color:#333;
                                                                                        z-index: 999;
                                                                                    "
                                                                                >
                                                                                    <i class="fa-solid fa-file fa-lg text-primary"></i>
                                                                
                                                                                    <div style="overflow:hidden;">
                                                                                        <div
                                                                                            style="
                                                                                                font-size:13px;
                                                                                                white-space:nowrap;
                                                                                                overflow:hidden;
                                                                                                text-overflow:ellipsis;
                                                                                                max-width:180px;
                                                                                            "
                                                                                        >
                                                                                            {{ $media->file_name }}
                                                                                        </div>
                                                                
                                                                                        <small class="text-muted">
                                                                                            {{ round($media->size / 1024, 1) }} KB
                                                                                        </small>
                                                                                    </div>
                                                                                </a>
                                                                
                                                                            @endif
                                                                
                                                                        @endforeach
                                                                
                                                                    </div>
                                                                
                                                            @endif
                                    
                                                            <span class="chat-time">
                                                                {{ tz($message->created_at, 'H:i a') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    
                                        @else
                                    
                                            <div class="chat chat-left" wire:key="message-{{ $message->id }}">
                                                <div class="chat-avatar">
                                                    <a href="#" class="avatar">
                                                        <img src="{{ !empty($message->sender->avatar)
                                                            ? asset('storage/'.$message->sender->avatar)
                                                            : asset('images/user.jpg') }}"
                                                            alt="Avatar">
                                                    </a>
                                                </div>
                                    
                                                <div class="chat-body">
                                                    <div class="chat-bubble">
                                                        <div class="chat-content">
                                                            <p class="mb-0 text-break" style="white-space: pre-wrap;">{{ $message->body }}</p>
                                    
                                                            @if ($message->getMedia('chat-attachments')->count())

                                                                <div class="mt-2 d-flex flex-column gap-2">
                                                                
                                                                        @foreach ($message->getMedia('chat-attachments') as $media)
                                                                
                                                                            @php
                                                                                $mime = $media->mime_type;
                                                                                $isImage = str_contains($mime, 'image');
                                                                            @endphp
                                                                
                                                                            @if ($isImage)
                                                                
                                                                                {{-- Image Attachment --}}
                                                                                <a
                                                                                    href="{{ $media->getUrl() }}"
                                                                                    target="_blank"
                                                                                    class="d-inline-block"
                                                                                    style="z-index: 999;"
                                                                                >
                                                                                    <img
                                                                                        src="{{ $media->getUrl() }}"
                                                                                        alt="Attachment"
                                                                                        style="
                                                                                            max-width:220px;
                                                                                            max-height:220px;
                                                                                            border-radius:12px;
                                                                                            object-fit:cover;
                                                                                            border:1px solid #ddd;
                                                                                        "
                                                                                    >
                                                                                </a>
                                                                
                                                                            @else
                                                                
                                                                                {{-- File Attachment --}}
                                                                                <a
                                                                                    href="{{ $media->getUrl() }}"
                                                                                    target="_blank"
                                                                                    class="d-flex align-items-center gap-2 p-2 border rounded text-decoration-none bg-light"
                                                                                    style="
                                                                                        max-width:250px;
                                                                                        color:#333;
                                                                                        z-index: 999;
                                                                                    "
                                                                                >
                                                                                    <i class="fa-solid fa-file fa-lg text-primary"></i>
                                                                
                                                                                    <div style="overflow:hidden;">
                                                                                        <div
                                                                                            style="
                                                                                                font-size:13px;
                                                                                                white-space:nowrap;
                                                                                                overflow:hidden;
                                                                                                text-overflow:ellipsis;
                                                                                                max-width:180px;
                                                                                            "
                                                                                        >
                                                                                            {{ $media->file_name }}
                                                                                        </div>
                                                                
                                                                                        <small class="text-muted">
                                                                                            {{ round($media->size / 1024, 1) }} KB
                                                                                        </small>
                                                                                    </div>
                                                                                </a>
                                                                
                                                                            @endif
                                                                
                                                                        @endforeach
                                                                
                                                                    </div>
                                                                
                                                            @endif
                                    
                                                            <span class="chat-time">
                                                                {{ tz($message->created_at, 'H:i a') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    
                                        @endif
                                    
                                    @endforeach
                                @endif 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="chat-footer" x-data>
                <div class="message-bar">
                    <div class="message-inner">
            
                        {{-- Emoji Button --}}
                        <button type="button" class="btn btn-custom emoji-button me-3">
                            <i class="fa-solid fa-smile"></i>
                        </button>
            
                        <div class="message-area w-100">
            
                            {{-- Attachment Preview --}}
                            @if ($attachments && count($attachments) > 0)
            
                                <div class="d-flex flex-wrap gap-2 mb-3">
            
                                    @foreach ($attachments as $index => $file)
            
                                        <div
                                            class="position-relative border rounded overflow-hidden bg-light"
                                            style="width:90px;height:90px;"
                                            wire:key="attachment-preview-{{ $index }}"
                                        >
            
                                            {{-- Image Preview --}}
                                            @if (str_contains($file->getMimeType(), 'image'))
            
                                                <img
                                                    src="{{ $file->temporaryUrl() }}"
                                                    alt="Preview"
                                                    style="
                                                        width:100%;
                                                        height:100%;
                                                        object-fit:cover;
                                                    "
                                                >
            
                                            @else
            
                                                {{-- File Preview --}}
                                                <div
                                                    class="d-flex flex-column align-items-center justify-content-center h-100 p-2 text-center"
                                                    style="font-size:11px;"
                                                >
                                                    <i class="fa-solid fa-file fa-2x mb-1 text-secondary"></i>
            
                                                    <span
                                                        style="
                                                            word-break:break-word;
                                                            line-height:1.2;
                                                        "
                                                    >
                                                        {{ $file->getClientOriginalName() }}
                                                    </span>
                                                </div>
            
                                            @endif
            
                                            {{-- Remove Button --}}
                                            <button
                                                type="button"
                                                wire:click="removeAttachment({{ $index }})"
                                                class="btn btn-danger btn-sm position-absolute"
                                                style="
                                                    top:2px;
                                                    right:2px;
                                                    width:20px;
                                                    height:20px;
                                                    border-radius:50%;
                                                    padding:0;
                                                    line-height:18px;
                                                    font-size:12px;
                                                "
                                            >
                                                ×
                                            </button>
            
                                        </div>
            
                                    @endforeach
            
                                </div>
            
                            @endif
            
                            {{-- Input Area --}}
                            <div class="input-group align-items-center">
            
                                {{-- Attachment Button --}}
                                <label
                                    for="chat-attachment"
                                    class="btn btn-custom d-flex justify-content-center align-items-center"
                                    style="
                                        cursor:pointer;
                                        height:45px;
                                        min-width:45px;
                                    "
                                    title="Attach files"
                                >
                                    <i class="fa-solid fa-paperclip"></i>
                                </label>
            
                                {{-- Hidden File Input --}}
                                <input
                                    type="file"
                                    wire:model="attachments"
                                    id="chat-attachment"
                                    class="d-none"
                                    multiple
                                    accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.txt"
                                >
            
                                {{-- Message Input --}}
                                <textarea
                                    class="form-control"
                                    wire:model.defer="messageBody"
                                    x-on:keydown.enter="
                                        if (!$event.shiftKey) {
                                            $event.preventDefault();
                                            $wire.sendMessage();
                                        }
                                    "
                                    id="messageInput"
                                    rows="1"
                                    placeholder="Type your message..."
                                    style="
                                        resize:none;
                                        min-height:45px;
                                        max-height:120px;
                                    "
                                ></textarea>
            
                                {{-- Send Button --}}
                                <button
                                    class="btn btn-custom"
                                    type="button"
                                    wire:click="sendMessage"
                                    wire:loading.attr="disabled"
                                    style="
                                        height:45px;
                                        min-width:50px;
                                    "
                                >
            
                                    {{-- Loading --}}
                                    <span
                                        wire:loading
                                        wire:target="attachments,sendMessage"
                                    >
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    </span>
            
                                    {{-- Normal Icon --}}
                                    <span
                                        wire:loading.remove
                                        wire:target="attachments,sendMessage"
                                    >
                                        <i class="fa-solid fa-paper-plane"></i>
                                    </span>
            
                                </button>
            
                            </div>
            
                            {{-- Upload Loading --}}
                            <div
                                wire:loading
                                wire:target="attachments"
                                class="small text-muted mt-2"
                            >
                                Uploading files...
                            </div>
            
                            {{-- Validation Errors --}}
                            @error('attachments.*')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
            
                        </div>
            
                    </div>
                </div>
            </div>
            
            @else
            <div class="d-flex align-items-center justify-content-center flex-column text-center h-100" style="min-height: 70vh;">
    
                <div class="mb-4">
                    <img src="{{ asset('images/chat-empty.png') }}" alt="No Chat Selected" style="max-width: 220px; opacity: 0.8;">
                </div>

                <h3 class="fw-bold mb-2">{{ __('Start a Conversation') }}</h3>
                
                <p class="text-muted mb-4" style="max-width: 400px;">
                    {{ __('Select a user from the list to start chatting and exchange messages in real-time.') }}
                </p>

                <div>
                    <i class="fa-regular fa-comments fa-3x text-primary opacity-50"></i>
                </div>

            </div>
            @endif       
        </div>
        @script
        <script type="module" defer>
            Livewire.on('scroll-chat', () => {
                $wire.scrollDown()
            })
        </script>
        @endscript
    </div>
    <!-- /Chats View -->
    @if (!empty($user))
    <!-- Chat Right Sidebar -->
    <div class="col-lg-3 message-view chat-profile-view chat-sidebar" id="task_window">
        <div class="chat-window video-window">
            <div class="fixed-header">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item"><a class="nav-link active" href="#profile_tab" data-bs-toggle="tab">{{ __('Profile') }}</a></li>
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
                                            <img src="{{ !empty($user->avatar) ? asset('storage/'.$user->avatar): asset('images/user.jpg') }}" alt="{{ __('Avatar') }}" style="object-fit: cover;">
                                            <span class="change-img">Change Image</span>
                                        </div>
                                        <h3 class="user-name m-t-10 mb-0">{{ $user->fullname }}</h3>
                                        @if (($user->type === \App\Enums\UserType::EMPLOYEE) && !empty($user->employeeDetail->designation))
                                        <small class="text-muted">{{ $user->employeeDetail->designation->name ?? '' }}</small>
                                        @endif
                                    </div>
                                    <div class="chat-profile-info">
                                        <ul class="user-det-list">
                                            @if (!empty($user->username))
                                            <li>
                                                <span>{{ __('Username') }}:</span>
                                                <span class="float-end text-muted">{{ $user->username }}</span>
                                            </li>
                                            @endif
                                            @if (!empty($user->dob))
                                                <li>
                                                    <span>{{ __('DOB') }}:</span>
                                                    <span class="float-end text-muted">{{ format_date($user->employeeDetail->dob) }}</span>
                                                </li>
                                            @endif
                                            @if (!empty($user->email))
                                            <li>
                                                <span>{{ __('Email') }}:</span>
                                                <span class="float-end text-muted">{{ $user->email }}</span>
                                            </li>
                                            @endif
                                            @if (!empty($user->phone))
                                            <li>
                                                <span>{{ __('Designation') }}:</span>
                                                <span class="float-end text-muted">{{ $user->designation?->name ?? 'N/A' }}</span>
                                            </li>
                                            @endif
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
    @endif
</div>
