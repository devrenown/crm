@extends('pages.front.layouts.app') 

<style>
    nav {
        background: #ffffff !important;
    }

    .nav-link {
        color: black !important;
    }
</style>

@section('content')
<div class="container" style="margin-top: 6rem;">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center">
                        <h3 class="account-title mb-2">{{ __('Login') }}</h3>
                        <p class="account-subtitle text-muted mb-4">{{ __('Access to our dashboard') }}</p>
                    </div>

                    <!-- Account Form -->
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control" id="email" name="email" tabindex="1"
                                   value="{{ old('email') }}" placeholder="Enter email" required>

                            @error('email')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label mb-0">{{ __('Password') }}</label>
                                <a href="{{ route('password.email') }}" class="text-muted small">
                                    {{ __('Forgot password?') }}
                                </a>
                            </div>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="******" tabindex="2" required>
                                <span class="input-group-text" id="toggle-password" style="cursor:pointer;">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </span>
                            </div>

                            @error('password')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                        </div>

                        <!-- Register Link -->
                        {{-- @if (Route::has('register'))
                            <div class="text-center">
                                <p class="mb-0">
                                    {{ __("Don't have an account yet?") }}
                                    <a href="{{ route('signup') }}">{{ __('Register') }}</a>
                                </p>
                            </div>
                        @endif --}}
                    </form>
                    <!-- /Account Form -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('toggle-password').addEventListener('click', function () {
    let passwordInput = document.getElementById('password');
    let icon = this.querySelector('i');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
});
</script>
@endpush
