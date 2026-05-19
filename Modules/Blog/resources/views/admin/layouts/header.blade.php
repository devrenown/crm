<div class="header">

    <!-- Logo -->
    <div class="header-left">

        <a href="{{ route('blog.index') }}"
           class="logo">

            <img src="{{ asset('assets/img/logo.png') }}"
                 width="40"
                 height="40"
                 alt="Logo">

        </a>

    </div>
    <!-- /Logo -->

    <!-- Mobile Menu Toggle -->
    <a id="toggle_btn" href="javascript:void(0);">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <!-- Header Title -->
    <div class="page-title-box">
        <h3>
            Blog Writer Panel
        </h3>
    </div>

    <!-- Header Menu -->
    <ul class="nav user-menu">

        <!-- User Menu -->
        <li class="nav-item dropdown has-arrow main-drop">

            <a href="#"
               class="dropdown-toggle nav-link"
               data-bs-toggle="dropdown">

                <span class="user-img">

                    <img src="{{ asset('assets/img/profiles/avatar-21.jpg') }}"
                         alt="User">

                    <span class="status online"></span>

                </span>

                <span>
                    {{ auth()->user()->fullname ?? 'Blog Writer' }}
                </span>

            </a>

            <div class="dropdown-menu">

                <form method="POST"
                      action="{{ route('blog.logout') }}">

                    @csrf

                    <button type="submit"
                            class="dropdown-item text-danger">
                        Logout
                    </button>
                </form>

            </div>

        </li>
        <!-- /User Menu -->

    </ul>

</div>