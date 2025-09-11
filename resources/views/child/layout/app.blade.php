<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Child | Learning</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('backend/dist/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Child Mode Theme Styles */
        .child-navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .child-navbar .navbar-brand {
            font-weight: 700;
            letter-spacing: .5px;
            font-size: 1.2rem;
        }

        /* Dark mode child styles */
        body.dark-mode {
            background-color: #1a202c;
        }
        body.dark-mode .content-wrapper {
            background-color: #1a202c;
        }
        body.dark-mode .card {
            background-color: #2d3748;
            border-color: #4a5568;
        }
        body.dark-mode .card-header {
            background-color: #2d3748;
            border-color: #4a5568;
        }
        body.dark-mode .text-muted {
            color: #a0aec0 !important;
        }

        /* Light mode child styles */
        body.light-mode {
            background-color: #f8fafc;
        }
        body.light-mode .content-wrapper {
            background-color: #f8fafc;
        }
        body.light-mode .card {
            background-color: #ffffff;
            border-color: #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,.1);
        }
        body.light-mode .card-header {
            background-color: #f7fafc;
            border-color: #e2e8f0;
        }

        /* Course cards enhancement */
        .course-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        .course-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,.15);
        }

        /* Module list enhancement */
        .module-item {
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .module-item:hover {
            background-color: rgba(102, 126, 234, 0.1);
        }
        .module-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* Content viewer enhancements */
        .content-viewer {
            border-radius: 12px;
            overflow: hidden;
        }

        /* Profile avatars */
        .profile-avatar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        /* Theme toggle button */
        .theme-toggle {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            color: white;
            transition: all 0.3s ease;
        }
        .theme-toggle:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
    </style>
</head>
<body class="dark-mode layout-fixed">
    <nav class="navbar navbar-expand child-navbar navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap mr-2"></i> Learning Space
            </a>
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item mr-3">
                    <button class="btn btn-sm theme-toggle" id="child-theme-switcher" title="Toggle Theme">
                        <i class="fas fa-sun"></i>
                    </button>
                </li>
                <li class="nav-item mr-2 text-white-75 d-none d-md-inline">
                    <small><i class="fas fa-user-graduate mr-1"></i> Child Mode</small>
                </li>
                <li class="nav-item">
                    <a class="btn btn-sm btn-light" href="{{ route('parent.children.exit') }}" title="Exit to Parent">
                        <i class="fas fa-door-open mr-1"></i> Exit to Parent
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="content-wrapper" style="margin-left: 0;">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('backend/dist/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/dist/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/dist/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/adminlte.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Child theme switcher
        const childThemeSwitcher = document.getElementById('child-theme-switcher');
        const body = document.body;

        const toggleChildTheme = () => {
            body.classList.toggle('dark-mode');
            body.classList.toggle('light-mode');

            const isDarkMode = body.classList.contains('dark-mode');
            childThemeSwitcher.innerHTML = isDarkMode ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';

            localStorage.setItem('child-theme', isDarkMode ? 'dark' : 'light');
        };

        if (childThemeSwitcher) {
            childThemeSwitcher.addEventListener('click', (e) => {
                e.preventDefault();
                toggleChildTheme();
            });
        }

        // Load saved theme
        const savedTheme = localStorage.getItem('child-theme');
        if (savedTheme === 'light') {
            body.classList.remove('dark-mode');
            body.classList.add('light-mode');
            if (childThemeSwitcher) childThemeSwitcher.innerHTML = '<i class="fas fa-moon"></i>';
        } else {
            body.classList.remove('light-mode');
            body.classList.add('dark-mode');
            if (childThemeSwitcher) childThemeSwitcher.innerHTML = '<i class="fas fa-sun"></i>';
        }

        // Toastr notifications
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
    @yield('scripts')
</body>
</html>
