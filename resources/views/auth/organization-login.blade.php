<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

   @include('partials.styles')

    <style>

        body {
            background: url('/images/org-login.png');
            background-position: center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-section h1 {
            font-size: 3.2rem;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 18px;
            padding: 45px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }

        .custom-input {
            height: 50px;
            border-radius: 12px;
            padding-left: 15px;
            background: #f1f3f8;
            border: none;
        }

        .custom-input:focus {
            box-shadow: none;
            background: #e9ecf5;
        }
        
        .login-btn {
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }
        
        /* Mobile */
        @media (max-width: 992px) {
            .left-section {
                display: none !important;
            }
        
            .login-wrapper {
                padding: 20px;
            }
        
            .login-card {
                padding: 30px;
            }
        }
    </style>

</head>

<body>

    <div class="container-fluid login-wrapper">
        <div class="row min-vh-100 align-items-center">

        <!-- LEFT SECTION -->
        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center px-5 left-section">

            <div class="mb-4">
                @php
                    $theme = app(\App\Settings\ThemeSettings::class);
                @endphp

                @if($theme->logo_dark ?? false)
                    <img src="{{ asset('storage/settings/theme/'.$theme->logo_dark) }}" class="tenant-logo mb-3" alt="Tenant Logo">
                @else
                    <img src="{{ asset('images/company-placeholder.png') }}" class="tenant-logo mb-3" alt="Logo">
                @endif
            </div>

            @php
                $name = strtoupper($tenant->name ?? 'RENOWN ALFA TECHNOLOGIES PRIVATE LIMITED');
                $words = explode(' ', $name);
            
                $firstTwo = implode(' ', array_slice($words, 0, 2));
                $remaining = implode(' ', array_slice($words, 2));
            @endphp
            
            <h2 class="fw-bold">
                <span style="color: {{ $theme->color_scheme }}">{{ $firstTwo }}</span>
                <span class="text-dark">{{ $remaining }}</span>
            </h2>

            <h1 class="display-4 fw-bold mt-5" style="color: {{ $theme->color_scheme }}">
                HELLO,<br>WELCOME!
            </h1>

            <p class="mt-3 fs-5">
                Log in to stay connected with your business insights.
                Access your dashboard and manage operations seamlessly.
            </p>
        </div>

        <!-- RIGHT SECTION -->
        <div class="col-lg-6 d-flex justify-content-center align-items-center px-3">

            <div class="login-card">

                <h3 class="text-center fw-bold mb-2">LOGIN</h3>
                <p class="text-center text-muted mb-4">
                    Enter your credentials to continue
                </p>

                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3 position-relative">
                        <input type="email"
                               name="email"
                               class="form-control custom-input"
                               placeholder="Username"
                               value="{{ old('email') }}">
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4 position-relative">
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control custom-input"
                               placeholder="Password">

                        <span class="toggle-password" id="toggle-password">
                            <i class="fa-solid fa-eye-slash"></i>
                        </span>

                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button class="btn bg-{{ $theme->color_scheme }} w-100 login-btn">
                        Login Now
                    </button>
                </form>

            </div>
        </div>

    </div>
    </div>


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

</body>

</html>