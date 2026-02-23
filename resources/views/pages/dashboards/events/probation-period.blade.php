<style>
    .probation-admin-card {
        background: linear-gradient(145deg, #5f72ff, #9b6bff);
        border-radius: 22px;
        overflow: hidden;
        min-height: 320px;
    }
</style>

@php
use Carbon\Carbon;
$today = Carbon::today();
@endphp

<div class="col-md-4 pe-lg-0">

    <div id="probationCarousel" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-inner">

            @if ($upcomingProbationCompleted->count() > 0)

                @foreach ($upcomingProbationCompleted as $index => $user)

                    @php
                        $end   = Carbon::parse($user->probation_end_date);
                    @endphp

                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }} mb-2">
                        <div class="probation-admin-card text-center text-white p-4 position-relative">

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

                            <p>Probation Completed In</p>
                            <div class="years">
                                @if ($end->isSameDay($today))
                                    <span>Today</span>
                                @else
                                    <span>{{ $today->diffInDays($end) }}</span> Days
                                @endif
                            </div>

                            <div class="mt-3 text-warning fw-semibold">
                                <i class="fa-solid fa-user-check"></i> Probation Completed
                            </div>

                            <div class="wave wave-blue"></div>
                        </div>
                    </div>
                @endforeach

            @else

                <!-- NO BIRTHDAYS SLIDE -->
                <div class="carousel-item active mb-2">
                    <div class="probation-admin-card text-center text-white p-4 position-relative">

                        <div class="avatar-wrap mx-auto d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-user-check fa-3x text-muted"></i>
                        </div>

                        <h5 class="mt-4 text-white fw-semibold">
                            No Probations
                        </h5>

                        <p class="text-light small mt-2 mb-0">
                            No probation ending in the Upcoming 7 days
                        </p>

                        <div class="wave wave-blue"></div>
                    </div>
                </div>

            @endif

        </div>

        <!-- CONTROLS -->
        @if (count($upcomingProbationCompleted) > 1)
            <button class="carousel-control-prev" type="button"
                data-bs-target="#probationCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button"
                data-bs-target="#probationCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        @endif
    </div>
</div>
