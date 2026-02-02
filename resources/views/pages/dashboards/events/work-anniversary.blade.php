
<style>
	.anniversary-card {
	    /*background: linear-gradient(135deg, #8c6a2f, #3e2c00);*/
	    background: url("{{ asset('images/anniversary-background.png')  }}");
        background-size: cover;
	    border-radius: 22px;
	    overflow: hidden;
	    min-height: 320px;
	}

	.text-anniversary {
		color: #007B70;
	}

	.border-anniversary {
        border-color: #007B70 !important;
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
				        <div class="avatar-wrap border border-3 border-anniversary mx-auto mb-3 position-relative shadow">
				            <img src="{{ asset('storage/users/' . $user->avatar) }}">

				            <div class="avatar-icon position-absolute bottom-0 end-0 border border-anniversary rounded-circle">
                                <i class="fa-solid fa-crown text-anniversary"></i>
                            </div>
				        </div>

				        <!-- Name -->
				        <h5 class="fs-5 fw-bold text-anniversary mb-1">{{ $user->fullname }}</h5>

				        <!-- Role -->
				        <div class="small text-dark mb-3">
				            <i class="fa-solid fa-briefcase me-1"></i> 
				            {{ @$user->employeeDetail->designation->name ?? 'Employee' }}
				        </div>

				        <!-- Golden Years -->
				        <div class="years">
				            <span class="text-anniversary">{{ number_format($years) }} Years</span> 
				        </div>

				        <!-- Label -->
				        <div class="mt-3 text-anniversary fw-semibold">
				        	<p class="fs-5">{{ date('m-d', strtotime($user->date_joined)) == $today ? 'Today 🥳' :  date('d M', strtotime($user->date_joined)) }}</p>
				        </div>

				        <!-- Bottom wave -->
				        <div class="wave wave-blue">
				        	<div class="border border-anniversary mb-4 px-4 py-1 rounded-pill text-anniversary shadow"><i class="fa-solid fa-crown me-1"></i> Work Anniversary</div>
				        </div>
				    </div>
				</div>
				@endforeach

			@else

                <!-- NO BIRTHDAYS SLIDE -->
                <div class="carousel-item active mb-2">
                    <div class="anniversary-card text-center text-white p-4 position-relative">

                        <div class="avatar-wrap border border-3 border-anniversary mx-auto d-flex align-items-center justify-content-center position-relative shadow">
                            <i class="fa-solid fa-award fa-3x text-muted"></i>

                            <div class="avatar-icon position-absolute bottom-0 end-0 border border-anniversary rounded-circle">
                                <i class="fa-solid fa-crown text-anniversary"></i>
                            </div>
                        </div>

                        <h5 class="mt-4 text-anniversary fw-semibold">
                            No Work Anniversaries
                        </h5>

                        <p class="text-dark small mt-2 mb-0">
                            No Work Anniversaries in the upcoming 7 days
                        </p>

                        <div class="wave wave-blue">
                        	<div class="border border-anniversary mb-4 px-4 py-1 rounded-pill text-anniversary shadow"><i class="fa-solid fa-crown me-1"></i> Anniversary</div>
                        </div>
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