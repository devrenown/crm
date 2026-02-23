@extends('pages.front.layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center">
                        <h3 class="account-title mb-2">{{ __('Register') }}</h3>
                        <p class="account-subtitle text-muted mb-4">{{ __('For access to our dashboard') }}</p>
                    </div>

                    <!-- Account Form -->
                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label">{{ __('Full Name') }}</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" tabindex="1"
                                   value="{{ old('full_name') }}" placeholder="Enter Name" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control" id="email" name="email" tabindex="1"
                                   value="{{ old('email') }}" placeholder="Enter email" required>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                            <input type="text" class="form-control" id="phone" name="phone" tabindex="1"
                                   value="{{ old('phone') }}" placeholder="Enter contact" maxlength="10" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="password" name="password" tabindex="1"
                                   placeholder="******" required>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                   name="password_confirmation" tabindex="1" placeholder="******" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                        </div>

                        <!-- Login Redirect -->
                        <div class="text-center">
                            <p class="mb-0">
                                I have already an account ? <a href="{{ route('login') }}">Login</a>
                            </p>
                        </div>
                    </form>
                    <!-- /Account Form -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
