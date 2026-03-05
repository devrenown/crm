<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $theme = app(\App\Settings\ThemeSettings::class);
    @endphp

   @include('partials.styles')

    <style>

        body {
            /*background: url('/images/org-login.png');
            background-position: center;
            background-size: cover;*/
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-section h1 {
            font-size: 3.2rem;
        }

        .login-card {
            border-radius: 18px;
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

        .tenant-logo {
            height: 5rem;
        }

        .input-icon {
            position: absolute;
            left: 5%;
            top: 50%;
            transform: translate(-5%, -50%);
        }
        

        @media (max-width: 992px) {
            /*.left-section {
                display: none !important;
            }*/
        
            .login-wrapper {
                padding: 20px;
            }
        
            .login-card {
                padding: 30px;
            }

            .left-section h1 {
                font-size: 2.5rem;
            }
        }
    </style>

</head>

<body style="
        background:
            radial-gradient(circle at 0% 0%,
                color-mix(in srgb, {{ $theme->color_scheme }} 35%, transparent) 0%,
                transparent 50%
            ),
            radial-gradient(circle at 100% 0%,
                color-mix(in srgb, {{ $theme->color_scheme }} 35%, transparent) 0%,
                transparent 50%
            ),
            #f9faff;
        ">

    <div class="container login-wrapper">
        <div class="row bg-white shadow-sm rounded-3 p-3">

            <div class="text-center mb-4">

                @if($theme->logo_dark ?? false)
                    <img src="{{ asset('storage/' . $theme->logo_dark) }}" class="tenant-logo mb-3" alt="Tenant Logo">
                @else
                    <img src="{{ asset('images/company-placeholder.png') }}" class="tenant-logo mb-3" alt="Logo">
                @endif

                @php
                    $name = strtoupper($tenant->name ?? 'Company Name');
                
                @endphp
                
                <h3 class="">
                    <span class="fw-bold" style="color: {{ $theme->color_scheme }}">{{ Theme('name') ?? config('app.name') }}</span>
                   
                </h3>
            </div>

            <!-- LEFT SECTION -->
            <div class="col-lg-7 d-lg-flex flex-column justify-content-center px-lg-5 left-section">
                <img src="{{ asset('images/org-login-left.png') }}" class="img" alt="Renown System Login">
            </div>

            <!-- RIGHT SECTION -->
            <div class="col-lg-5 text-center">

                <div class="login-card shadow p-3" 
                    style="background: linear-gradient(
                         to bottom,
                         color-mix(in srgb, {{ $theme->color_scheme }} 30%, white),
                         white
                     );">

                    <h3 class="text-center fw-bold mb-2">LOGIN</h3>
                    <p class="text-center mb-4">
                        Enter your credentials to continue
                    </p>

                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <div class="position-relative">
                                <i class="fa-solid fa-user input-icon text-muted fs-5"></i>
                                <input type="email"
                                       name="email"
                                       class="form-control custom-input ps-5"
                                       placeholder="Username"
                                       value="{{ old('email') }}">
                            </div>

                            @error('email')
                            <div class="text-start">
                                <small class="text-danger">{{ $message }}</small>
                            </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <div class="position-relative">
                                <i class="fa-solid fa-lock input-icon text-muted fs-5"></i>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control custom-input ps-5"
                                       placeholder="Password">

                                <span class="toggle-password" id="toggle-password">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </span>
                            </div>

                            @error('password')
                            <div class="text-start">
                                <small class="text-danger">{{ $message }}</small>
                            </div>
                            @enderror
                        </div>

                        <button class="btn bg-{{ $theme->color_scheme }} w-100 login-btn">
                            Login Now <i class="fa-solid fa-arrow-right ms-1"></i>
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