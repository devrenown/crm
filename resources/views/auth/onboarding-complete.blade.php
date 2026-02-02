@extends('layouts.app')

@push('page-styles')
<style>
    .page-wrapper {
        margin: 0 !important;
    }

    .text-primary {
        color: #6876DF !important;
    }

    .welcome-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
    }

    .company-card {
        border: none;
        border-radius: 0.75rem;
        background-color: #fafafa;
        transition: all 0.3s ease;
    }

    .company-card:hover {
        background-color: #f1f5ff;
        transform: translateY(-3px);
    }

    .section-title {
        border-left: 4px solid #0d6efd;
        padding-left: 10px;
        font-weight: 600;
        color: #0d6efd;
    }
</style>
@endpush

@section('page-content')
<div class="content container-fluid d-flex justify-content-center align-items-center">
    <div class="col-lg-10">
        <div class="card p-4 welcome-card">

            {{-- Profile Image --}}
            <img src="{{ $user->avatar ? asset('storage/users/' . $user->avatar) : asset('images/user.jpg') }}" 
                alt="User Avatar" 
                class="logo rounded-circle mx-auto mb-3 shadow-sm" 
                style="height: 5rem; width: 5rem; object-fit: cover;">

            {{-- Title --}}
            <h2 class="fw-bold text-center mb-2">
                Welcome Aboard, <span class="text-primary">{{ $user->firstname . ' ' . $user->lastname }}</span>!
            </h2>

            <hr class="my-4">

            {!! $companySettings->about !!}
            
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
@endpush
