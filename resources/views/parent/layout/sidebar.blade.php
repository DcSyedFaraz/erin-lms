<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/" class="brand-link">
        <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="brand-image " style="opacity: .8">
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('backend/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name ?? 'Parent' }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('parent.dashboard') }}"
                        class="nav-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('parent.courses.index') }}"
                        class="nav-link {{ request()->routeIs('parent.courses.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Browse Courses</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('parent.subscriptions.index') }}"
                        class="nav-link {{ request()->routeIs('parent.subscriptions.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>My Subscription</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('video-library.index') }}"
                        class="nav-link {{ request()->routeIs('video-library.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-photo-video"></i>
                        <p>Video Library</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('parent.courses.my') }}"
                        class="nav-link {{ request()->routeIs('parent.courses.my') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>My Courses</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('parent.courses.summary') }}"
                        class="nav-link {{ request()->routeIs('parent.courses.summary') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Course Summary</p>
                    </a>
                </li>

                <li class="nav-item has-treeview {{ request()->routeIs('parent.children.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('parent.children.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-child"></i>
                        <p>
                            Children
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('parent.children.index') }}"
                                class="nav-link {{ request()->routeIs('parent.children.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profiles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('parent.children.analytics.overall') }}"
                                class="nav-link {{ request()->routeIs('parent.children.analytics.overall') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Analytics (All)</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form-parent').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form-parent" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
