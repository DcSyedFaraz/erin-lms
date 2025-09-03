<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="{{ asset('backend/dist/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Parent Panel</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('backend/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name ?? 'Parent' }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('parent.dashboard') }}" class="nav-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('parent.courses.index') }}" class="nav-link {{ request()->routeIs('parent.courses.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Browse Courses</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('parent.courses.my') }}" class="nav-link {{ request()->routeIs('parent.courses.my') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>My Courses</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('parent.courses.summary') }}" class="nav-link {{ request()->routeIs('parent.courses.summary') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Course Summary</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form-parent').submit();">
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
