<style>
    .birthday-admin-card {
        background: linear-gradient(145deg, #ff6ec4, #7873f5);
        border-radius: 22px;
        overflow: hidden;
        min-height: 320px;
    }

    .avatar-wrap {
        width: 90px;
        height: 90px;
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
        font-size: 42px;
        font-weight: 800;
        color: gold;
        letter-spacing: 1px;
    }

    .years span {
        font-size: 56px;
        text-shadow: 0 4px 12px rgba(255, 215, 0, 0.6);
    }

    .wave {
        position: absolute;
        bottom: -20px;
        left: 0;
        width: 100%;
        height: 70px;
        border-radius: 100% 100% 0 0;
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

@php
use Carbon\Carbon;
$today = Carbon::today()->format('m-d');
@endphp

<div class="col-md-4 pe-lg-0">

    <div id="birthdayCarousel" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-inner">

            @if ($upcomingBirthdays->count() > 0)

                @foreach ($upcomingBirthdays as $index => $user)
                    @php
                        $years = Carbon::parse($user->dob)->diffInYears(Carbon::today());
                    @endphp

                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }} mb-2">
                        <div class="birthday-admin-card text-center text-white p-4 position-relative">

                            <div class="avatar-wrap mx-auto">
                                <img src="{{ asset('storage/users/' . $user->avatar) }}">
                            </div>

                            <h5 class="mt-3 text-white fw-semibold">
                                {{ $user->fullname }}
                            </h5>

                            <div class="role text-light small mb-2">
                                <i class="fa-solid fa-briefcase me-1"></i>
                                {{ @$user->employeeDetail->designation->name ?? 'Employee' }}
                            </div>

                            <div class="d-flex justify-content-center align-items-center">
                               
                                <img src="{{ asset('images/icons/birthday-cake.svg') }}" class="cake me-2">
                                <div class="years">
                                    <span>{{ number_format($years) }}</span> Years
                                </div>
                            </div>

                            <div class="mt-3 text-warning fw-semibold">
                                <p class="fs-4">{{ date('m-d', strtotime($user->dob)) == $today ? 'Today 🥳' :  date('d M', strtotime($user->dob)) }}</p>
                                <i class="fa-solid fa-crown me-1"></i> Happy Birthday
                            </div>

                            <div class="wave wave-blue"></div>
                        </div>
                    </div>
                @endforeach

            @else

                <!-- NO BIRTHDAYS SLIDE -->
                <div class="carousel-item active mb-2">
                    <div class="birthday-admin-card text-center text-white p-4 position-relative">

                        <div class="avatar-wrap mx-auto d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-cake-candles fa-3x text-muted"></i>
                        </div>

                        <h5 class="mt-4 text-white fw-semibold">
                            No Birthdays
                        </h5>

                        <p class="text-light small mt-2 mb-0">
                            No birthdays in the upcoming 7 days
                        </p>

                        <div class="wave wave-blue"></div>
                    </div>
                </div>

            @endif

        </div>

        <!-- CONTROLS -->
        @if (count($upcomingBirthdays) > 1)
            <button class="carousel-control-prev" type="button"
                data-bs-target="#birthdayCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button"
                data-bs-target="#birthdayCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        @endif
    </div>
</div>


