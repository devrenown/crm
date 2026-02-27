<style>

    .probation-admin-card {

        /*background: linear-gradient(145deg, #5f72ff, #9b6bff);*/

        background: url("{{ asset('images/probation-background.png')  }}");

        background-size: cover;

        border-radius: 22px;

        overflow: hidden;

        min-height: 320px;

    }



    .text-probation {

        color: #8106DD;

    }

    .border-probation {

        border-color: #8106DD !important;

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



                            <div class="avatar-wrap border border-3 border-probation mx-auto position-relative shadow">

                                <img src="{{ asset('storage/' . $user->avatar) }}">



                                <div class="avatar-icon position-absolute bottom-0 end-0 border border-probation rounded-circle">

                                    <i class="fa-solid fa-calendar-check text-probation"></i>

                                </div>

                            </div>



                            <h5 class="mt-3 fs-5 text-probation fw-bold">

                                {{ $user->fullname }}

                            </h5>



                            <div class="role text-dark small mb-2">

                                <i class="fa-solid fa-briefcase me-1"></i>

                                {{ @$user->employeeDetail->designation->name ?? 'Employee' }}

                            </div>



                            <p class="text-dark">Probation Completed In</p>

                            <div class="years">

                                @if ($end->isSameDay($today))

                                    <span class="text-probation">Today</span>

                                @else

                                    {{-- <span>{{ $today->diffInDays($end) }}</span> Days --}}

                                    <span class="text-probation">{{ date('d M', strtotime($end)) }}</span>

                                @endif

                            </div>



                            <div class="wave wave-blue">

                                <div class="border border-probation mb-4 px-4 py-1 rounded-pill text-probation shadow"><i class="fa-solid fa-calendar-check me-1"></i> Probation Completed</div>

                            </div>

                        </div>

                    </div>

                @endforeach



            @else



                <!-- NO PROBATION SLIDE -->

                <div class="carousel-item active mb-2">

                    <div class="probation-admin-card text-center text-white p-4 position-relative">



                        <div class="avatar-wrap mx-auto border border-3 border-probation d-flex align-items-center justify-content-center position-relative shadow">

                            <i class="fa-solid fa-user-check fa-3x text-muted"></i>



                            <div class="avatar-icon position-absolute bottom-0 end-0 border border-probation rounded-circle">

                                <i class="fa-solid fa-calendar-check text-probation"></i>

                            </div>

                        </div>



                        <h5 class="mt-4 text-probation fw-semibold">

                            No Probations

                        </h5>



                        <p class="text-probation small mt-2 mb-0">

                            No probation ending in the Upcoming 7 days

                        </p>



                        <div class="wave wave-blue">

                            <div class="border border-probation mb-4 px-4 py-1 rounded-pill text-probation shadow"><i class="fa-solid fa-calendar-check me-1"></i> Probation</div>

                        </div>

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

