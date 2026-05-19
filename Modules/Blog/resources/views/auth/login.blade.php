<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Blog Panel Login</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family: Arial, Helvetica, sans-serif;
            background:#f4f7fb;
            height:100vh;
        }

        .login-wrapper{
            display:flex;
            min-height:100vh;
        }

        /* Left Section */
        .login-left{
            width:50%;
            background:linear-gradient(135deg,#2563eb,#1e40af);
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:60px;
        }

        .left-content{
            max-width:500px;
        }

        .left-content h1{
            font-size:48px;
            margin-bottom:20px;
        }

        .left-content h3{
            font-size:28px;
            margin-bottom:20px;
            font-weight:500;
        }

        .left-content p{
            line-height:1.8;
            font-size:16px;
            opacity:0.9;
        }

        /* Right Section */
        .login-right{
            width:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:40px;
            background:#fff;
        }

        .login-box{
            width:100%;
            max-width:420px;
        }

        .login-header{
            margin-bottom:35px;
        }

        .login-header h2{
            font-size:34px;
            color:#111827;
            margin-bottom:10px;
        }

        .login-header p{
            color:#6b7280;
            font-size:15px;
        }

        .form-group{
            margin-bottom:20px;
        }

        .form-group label{
            display:block;
            margin-bottom:8px;
            color:#374151;
            font-size:14px;
            font-weight:600;
        }

        .form-control{
            width:100%;
            height:50px;
            border:1px solid #d1d5db;
            border-radius:8px;
            padding:0 15px;
            font-size:15px;
            transition:0.3s;
            outline:none;
        }

        .form-control:focus{
            border-color:#2563eb;
            box-shadow:0 0 0 3px rgba(37,99,235,0.1);
        }

        .remember-box{
            display:flex;
            align-items:center;
            margin-bottom:25px;
        }

        .remember-box input{
            margin-right:10px;
        }

        .remember-box label{
            font-size:14px;
            color:#4b5563;
        }

        .btn-login{
            width:100%;
            height:50px;
            border:none;
            border-radius:8px;
            background:#2563eb;
            color:#fff;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:0.3s;
        }

        .btn-login:hover{
            background:#1d4ed8;
        }

        .alert{
            padding:14px 16px;
            border-radius:8px;
            margin-bottom:20px;
            font-size:14px;
        }

        .alert-success{
            background:#dcfce7;
            color:#166534;
        }

        .alert-danger{
            background:#fee2e2;
            color:#991b1b;
        }

        .error-text{
            margin-top:6px;
            color:#dc2626;
            font-size:13px;
        }

        .footer-text{
            text-align:center;
            margin-top:30px;
            color:#6b7280;
            font-size:13px;
        }

        @media(max-width:991px){

            .login-left{
                display:none;
            }

            .login-right{
                width:100%;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- Left -->
    <div class="login-left">

        <div class="left-content">

            <h1>
                {{ config('app.name') }}
            </h1>

            <h3>
                Blog Writer Panel
            </h3>

            <p>
                Create blogs, manage articles, upload featured images,
                and publish SEO-friendly content.
            </p>

        </div>

    </div>

    <!-- Right -->
    <div class="login-right">

        <div class="login-box">

            <div class="login-header">

                <h2>
                    Welcome Back
                </h2>

                <p>
                    Login to continue to your blog panel
                </p>

            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('blog.authenticate') }}">

                @csrf

                <!-- Email -->
                <div class="form-group">

                    <label>
                        Email Address
                    </label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Enter your email"
                           value="{{ old('email') }}"
                           required>

                    @error('email')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- Password -->
                <div class="form-group">

                    <label>
                        Password
                    </label>

                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Enter your password"
                           required>

                    @error('password')
                        <div class="error-text">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- Remember -->
                <div class="remember-box">

                    <input type="checkbox"
                           id="remember"
                           name="remember">

                    <label for="remember">
                        Remember Me
                    </label>

                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login">
                    Login
                </button>

            </form>

            <div class="footer-text">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>

        </div>

    </div>

</div>

<script>

    setTimeout(() => {

        const alerts = document.querySelectorAll('.alert');

        alerts.forEach(alert => {
            alert.style.display = 'none';
        });

    }, 4000);

</script>

</body>
</html>
