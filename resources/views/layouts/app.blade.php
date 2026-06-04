@extends('layouts.blank')

@php
    $theme = app(\App\Settings\ThemeSettings::class);
@endphp

@section('content')
    <!-- Header -->
    @hasSection('header')
        @yield('header')
    @else
        @include('partials.header')
    @endif
    <!-- /Header -->
    <!-- Sidebar -->
    
    @if (!request()->is('onboarding/welcome/*') && !request()->is('onboarding') && !request()->is('onboarding/start/*')) 

        @hasSection('sidebar')
            @yield('sidebar')
        @else
            @include('partials.sidebar')
        @endif

    @endif 
    <!-- /Sidebar -->
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div id="loader-wrapper">
            <div id="loader">
                <div class="loader-ellips">
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                </div>
            </div>
        </div>
        <!-- Page Content -->
        @yield('page-content')
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
    <!-- Delete Modal -->
    <div class="modal custom-modal fade" id="GeneralDeleteModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3 class="modal_title">Delete</h3>
                        <p class="modal_message">Are you sure want to delete?</p>
                    </div>
                    <form method="post">
                        @method('DELETE')
                        @csrf
                        <div class="modal-btn delete-action">
                            <input type="hidden" name="id">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-bs-dismiss="modal"
                                        class="btn btn-primary cancel-btn">{{ __('Cancel') }}</a>
                                </div>
                                <div class="col-6">
                                    <button type="submit"
                                        class="btn btn-primary continue-btn w-100">{{ __('Delete') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Modal -->

    <!-- Global Document Viewer Modal -->
    <div class="modal fade" id="globalDocumentModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Document</h5>
                    <button type="button" class="btn-close black" data-bs-dismiss="modal" >×</button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="globalDocumentFrame"
                            src=""
                            frameborder="0"
                            style="width:100%; height:85vh;">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

@php
    $iconUrl = !empty($theme->logo_light)
        ? asset('storage/' . $theme->logo_light)
        : (!empty($theme->logo_dark)
            ? asset('storage/' . $theme->logo_dark)
            : asset('images/company-placeholder.png'));
            
    $favIcon = Theme('favicon') ? asset('storage/' . Theme('favicon')) : null;
@endphp

@push('page-scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
            .then((registration) => {
                console.log('Service Worker Registered successfully with scope:', registration.scope);
            })
            .catch((error) => {
                console.error('SW registration failed:', error);
            });
        });
    }
</script>

<script>

    let userId = {{ auth()->id() }};
    
    if (Notification.permission !== "granted" && Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            console.log("Notification permission:", permission);
        });
    }

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: "{{ env('PUSHER_APP_KEY') }}",
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true,

        authEndpoint: '/broadcasting/auth',

        auth: {
            withCredentials: true,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        },
    });

    window.Echo.private('chat.user.' + userId)
    .listen('.chat.message.sent', function (e) {
        Livewire.dispatch('messageReceived', [e.sender_id]);

        let badge = document.getElementById('chat-unread-badge');

        if (badge) {
            let count = parseInt(badge.innerText || 0);
            count++;

            badge.innerText = count;
            badge.style.display = 'inline-block';
        }
        
        showBrowserNotification(e);
    });
    
    function markAsRead(userId) {
        if (window.Livewire) {
            Livewire.dispatch('markAsRead', [userId]);
        }

        let badge = document.getElementById('chat-unread-badge');
        if (badge) {
            badge.innerText = 0;
            badge.style.display = 'none';
        }
    }
    
    function openSecureDocument(url) {
        $('#globalDocumentFrame').attr('src', url);
        $('#globalDocumentModal').modal('show');
    }

    // Clear iframe when modal closes
    $(document).on('hidden.bs.modal', '#globalDocumentModal', function () {
        $('#globalDocumentFrame').attr('src', '');
    });

    $(document).on('change', '.status-dropdown', function () {
        let selectedVal = $(this).val();

        let parentRow = $(this).closest('.document-block');

        if (selectedVal == '2') { 
            parentRow.find('.remarks-container').slideDown();
            parentRow.find('input[name="remarks"]').attr('required', true);
        } else {
            parentRow.find('.remarks-container').slideUp();
            parentRow.find('input[name="remarks[]"]').val('');
            parentRow.find('input[name="remarks[]"]').attr('required', false);
        }
    });
    
    const notificationSound = new Audio('/sounds/marimba_notification.mp3');
    
    async function showBrowserNotification(data) {
        if (Notification.permission !== "granted") return;

        const title = data.sender_name || 'New Message';
        
        try {
            notificationSound.currentTime = 0;
            
            let playPromise = notificationSound.play();
            
            if (playPromise !== undefined) {
                playPromise.catch(error => {
                    console.warn("Audio autoplay blocked by browser policy. Sound will play once the user interacts with the app.", error);
                });
            }
        } catch (audioError) {
            console.error("Failed to play custom notification sound:", audioError);
        }
        
        const options = {
            body: data.message || '',
            icon: '{{ $iconUrl }}',
            badge: '{{ $favIcon }}',
            requireInteraction: true,
            vibrate: [200, 100, 200],
            tag: 'chat-message-' + (data.sender_id || 'general'), 
            data: {
                url: "/apps/chat?contact=" + data.contact
            }
        };

        try {
            const registration = await navigator.serviceWorker.ready;
            if (registration && 'showNotification' in registration) {
                await registration.showNotification(title, options);
                return;
            }
        } catch (e) {
            console.warn("Service Worker not ready for notification, falling back to standard Notification API.", e);
        }

        try {
            new Notification(title, options);
        } catch (err) {
            console.error("Both SW and standard Notification APIs failed:", err);
        }
    }

    window.routes = {
        onboardDeleteIdentityId: "{{ route('onboard.deleteIdentityId') }}",
    }
</script>
@endpush

    <!-- <script>
        function openSecureDocument(url) {
            document.getElementById('globalDocumentFrame').src = url;
            let modal = new bootstrap.Modal(document.getElementById('globalDocumentModal'));
            modal.show();
        }

        // Clear iframe when modal closes
        document.getElementById('globalDocumentModal')
            .addEventListener('hidden.bs.modal', function () {
                document.getElementById('globalDocumentFrame').src = '';
            });
    </script> -->

@endsection