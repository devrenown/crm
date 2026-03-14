<style>
    .notification-bell{
        font-size:20px;
        color:#6b7280;
        animation: bellRing 2s infinite;
    }

    .notification-badge{
        position:absolute;
        top:0;
        right:0;
        font-size:10px;
    }

    /* Bell animation */
    @keyframes bellRing{
        0%{ transform: rotate(0); }
        5%{ transform: rotate(15deg); }
        10%{ transform: rotate(-15deg); }
        15%{ transform: rotate(10deg); }
        20%{ transform: rotate(-10deg); }
        25%{ transform: rotate(0); }
        100%{ transform: rotate(0); }
    }

    .notification-menu{
        width:320px;
        max-height:350px;
        overflow-y:auto;
    }
</style>

<div class="header">

    <!-- Logo -->
    <x-logo />
    <!-- /Logo -->

    @if (!request()->is('onboarding/welcome/*') && !request()->is('onboarding') && !request()->is('onboarding/start/*'))
        <a id="toggle_btn" href="javascript:void(0);">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>
    @endif


    <!-- Header Title -->
    <div class="page-title-box">
        <h3>{{ Theme('name') ?? config('app.name') }}</h3>
    </div>
    <!-- /Header Title -->

    <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa-solid fa-bars"></i></a>

    <!-- Header Menu -->
    <ul class="nav user-menu">


        <li class="nav-item d-flex align-items-center dropdown has-arrow main-drop">
            <div>
                <a href="#" class="nav-link position-relative" data-bs-toggle="dropdown">

                    <i class="fa-regular fa-bell notification-bell fs-5"></i>

                    {{-- Notification Count --}}
                    <span class="badge rounded-pill bg-primary notification-badge py-1">
                        {{ $notificationCount ?? 0 }}
                    </span>

                </a>
                <div class="dropdown-menu dropdown-menu-end notification-menu">
                    <div class="p-3 border-bottom fw-semibold">
                        Notifications
                    </div>

                    @forelse($notifications ?? [] as $notification)
                        <a href="#" class="dropdown-item small">
                            {{ $notification->message }}
                            <div class="text-muted small">
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </a>
                    @empty
                        <div class="text-center text-muted p-3">
                            No new notifications
                        </div>
                    @endforelse
                </div>
            </div>

            <div>
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <span class="user-img"><img
                        src="{{ asset( auth()->user()->avatar ? 'storage/' . auth()->user()->avatar : 'images/user.jpg') }}"
                        alt="User Image"
                        style="height: 40px; width: 40px; object-fit: cover;">
                        <span class="status online"></span></span>
                    <span>{{ auth()->user()->fullname }}</span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('profile') }}">{{ __('My Profile') }}</a>
                    <a onclick="document.getElementById('logout_user_form').submit()" class="dropdown-item logout_btn"
                        href="javascript:void(0);">Logout</a>
                </div>
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                class="fa-solid fa-ellipsis-vertical"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('profile') }}">My Profile</a>
            <a onclick="document.getElementById('logout_user_form').submit()" class="dropdown-item logout_btn"
                href="javascript:void(0);">Logout</a>
        </div>
    </div>
    <!-- /Mobile Menu -->
    <form action="{{ route('logout') }}" id="logout_user_form" method="post">@csrf</form>

</div>