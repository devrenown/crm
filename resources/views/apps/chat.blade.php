@extends('layouts.app')

@push('page-styles')
    <!-- Page Css -->
    <meta name="url" content="{{ url('').'/'.config('chatify.routes.prefix') }}" data-user="{{ Auth::user()->id }}">
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

    .header #toggle_btn {
        padding: 20px 10px !important;
    }

    .header .header-left {
        padding: 10px 20px !important;
    }
    </style>
@endpush

@section('sidebar')
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <nav class="greedy">
                    <ul class="link-item">
                        <li>
                            <a href="{{ route('dashboard') }}"><i class="la la-dashboard"></i> <span>{{ __('Back To Dashboard') }}</span></a>
                        </li>
                        @livewire('apps.chat.sidebar')
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- /Sidebar -->
@endsection

@section('page-content')
    <!-- Chat Main Row -->
    <div class="chat-main-row">

        <!-- Chat Main Wrapper -->
        @livewire('apps.chat.box',['userId' => request()->get('contact')])            
        <!-- /Chat Main Wrapper -->

    </div>
    <!-- /Chat Main Row -->
@endsection

@section('vendor-scripts')
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
@vite([
    'resources/js/app/chat/chat-app.js'
])
@endsection

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
