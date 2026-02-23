
<style>
	.anniversary-card {
	    background: linear-gradient(135deg, #8c6a2f, #3e2c00);
	    border-radius: 22px;
	    overflow: hidden;
	    min-height: 320px;
	}

	.wave-gold {
        background: rgba(255, 215, 0, .18);
	}

</style>

@php
use Carbon\Carbon;
$today = Carbon::today()->format('m-d');
@endphp

<div class="col-md-4 pe-lg-0">
	<div id="anniversaryCarousel" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-inner">
			@if ($upcomingWorkAnniversaries->count() > 0)

				@foreach ($upcomingWorkAnniversaries as $index => $user)

				@php
				    $years = Carbon::parse($user->date_joined)->diffInYears(Carbon::today());
				@endphp

				<div class="carousel-item {{ $index === 0 ? 'active' : '' }} mb-2">
				    <div class="anniversary-card text-center text-white p-4 position-relative">
				        <!-- Avatar -->
				        <div class="avatar-wrap mx-auto mb-3">
				            <img src="{{ asset('storage/users/' . $user->avatar) }}">
				        </div>

				        <!-- Name -->
				        <h5 class="fw-semibold mb-1">{{ $user->fullname }}</h5>

				        <!-- Role -->
				        <div class="small text-light mb-3">
				            <i class="fa-solid fa-briefcase me-1"></i> 
				            {{ @$user->employeeDetail->designation->name ?? 'Employee' }}
				        </div>

				        <!-- Golden Years -->
				        <div class="years">
				            <i class="fa-solid fa-award me-2"></i>
				            <span>{{ number_format($years) }}</span> Years
				        </div>

				        <!-- Label -->
				        <div class="mt-3 text-warning fw-semibold">
				        	<p class="fs-4">{{ date('m-d', strtotime($user->date_joined)) == $today ? 'Today 🥳' :  date('d M', strtotime($user->date_joined)) }}</p>
				            <i class="fa-solid fa-crown me-1"></i> Work Anniversary
				        </div>

				        <!-- Bottom wave -->
				        <div class="wave wave-gold"></div>
				    </div>
				</div>
				@endforeach

			@else

                <!-- NO BIRTHDAYS SLIDE -->
                <div class="carousel-item active mb-2">
                    <div class="anniversary-card text-center text-white p-4 position-relative">

                        <div class="avatar-wrap mx-auto d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-award fa-3x text-muted"></i>
                        </div>

                        <h5 class="mt-4 text-white fw-semibold">
                            No Work Anniversaries
                        </h5>

                        <p class="text-light small mt-2 mb-0">
                            No Work Anniversaries in the upcoming 7 days
                        </p>

                        <div class="wave wave-gold"></div>
                    </div>
                </div>
            @endif
		</div>

		<!-- CONTROLS -->
        @if (count($upcomingWorkAnniversaries) > 1)
            <button class="carousel-control-prev" type="button"
                data-bs-target="#anniversaryCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button"
                data-bs-target="#anniversaryCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        @endif
	</div>
</div>