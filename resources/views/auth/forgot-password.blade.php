@extends('pages.front.layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center">
                        <h3 class="account-title mb-2">{{ __('Forgot Password?') }}</h3>
                        <p class="account-subtitle text-muted mb-4">{{ __('Enter your email to get a password reset link') }}</p>
                    </div>

                    <!-- Forgot Password Form -->
                    <form action="{{ route('password.request') }}" method="POST">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control" id="email" name="email" tabindex="1"
                                   value="{{ old('email') }}" placeholder="example@smarthr.com" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
                        </div>

                        <!-- Back to Login -->
                        @if (Route::has('login'))
                            <div class="text-center">
                                <p class="mb-0">
                                    {{ __('Remember your password?') }} 
                                    <a href="{{ route('login') }}">{{ __('Login') }}</a>
                                </p>
                            </div>
                        @endif
                    </form>
                    <!-- /Forgot Password Form -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
