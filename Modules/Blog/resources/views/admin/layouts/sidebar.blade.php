<div class="sidebar" id="sidebar">

    <div class="sidebar-inner slimscroll">

        <div id="sidebar-menu" class="sidebar-menu">

            <ul>

                <li class="menu-title">
                    <span>Blog Panel</span>
                </li>

                <li>
                    <a href="{{ route('blog.index') }}"
                       class="{{ request()->routeIs('blog.index') ? 'active' : '' }}">

                        <i class="la la-dashboard"></i>
                        <span>Dashboard</span>

                    </a>
                </li>

                <li>
                    <a href="{{ route('blog.list') }}"
                       class="{{ request()->routeIs('blog.list') ? 'active' : '' }}">

                        <i class="la la-newspaper-o"></i>
                        <span>All Blogs</span>

                    </a>
                </li>

                <li>
                    <a href="{{ route('blog.create') }}"
                       class="{{ request()->routeIs('blog.create') ? 'active' : '' }}">

                        <i class="la la-pencil"></i>
                        <span>Create Blog</span>

                    </a>
                </li>

                <li>
                    <a href="{{ route('blog.categories') }}"
                        class="{{ request()->routeIs('blog.categories') ? 'active' : '' }}">
                        <i class="la la-folder"></i>
                        <span>Categories</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="la la-image"></i>
                        <span>Media</span>
                    </a>
                </li>

            </ul>

        </div>

    </div>

</div>