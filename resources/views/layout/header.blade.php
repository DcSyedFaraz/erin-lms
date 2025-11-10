  <header class="header">
      <nav class="navbar-light navbar-expand-lg">
          <div class="container d-block">
              <div class="row align-items-center">
                  <div class="col-lg-2 col-md-6 col-6 px-2">
                      <a href="index.html">
                          <img class="navlogo" src="{{ asset('assets/images/logo-black.png') }}" alt="">
                      </a>
                  </div>
                  <div class="col-lg-8 col-md-6 d-none d-md-none d-lg-block px-2">
                      <div class="navbar">
                          <div class="nav-up">
                              <ul class="nav-ul">
                                  <li><a class="list active" href="{{ route('home') }}">Home</a></li>
                                  <li><a class="list" href="{{ route('program') }}">Program</a></li>
                                  <li><a class="list" href="{{ route('blog') }}">News & Blog</a></li>
                                  <li><a class="list" href="{{ route('about') }}">About Us</a></li>
                                  <li><a class="list" href="{{ route('contact') }}">Contact Us</a></li>
                                  <li><a class="list" href="{{ route('membership') }}">Membership Options</a></li>
                                  {{-- @auth
                                      <li><a class="list" href="{{ route('video-library.index') }}">Video Library</a></li>
                                  @endauth --}}
                                  <li><a class="list" href="{{ route('lesson') }}">Quick Lessons</a></li>
                              </ul>
                          </div>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-4 d-none d-md-none d-lg-block px-2">
                      <div class="loginbtns">
                          @auth
                              <a class="signinbtn" href="{{ Auth::user()->hasRole('Admin') ? route('admin.dashboard') : route('parent.dashboard') }}">Dashboard</a>
                          @else
                              <a class="signupbtn" href="{{ route('register') }}">Sign Up</a>
                              <a class="signinbtn" href="{{ route('login') }}">Sign In</a>
                          @endauth
                      </div>
                  </div>
                  <div class="col-6 col-md-6 d-lg-none d-md-block d-block">
                      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                          data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas" aria-expanded="false"
                          aria-label="Toggle navigation">
                          <i class="fa-solid fa-bars"></i>
                      </button>
                      <div class="offcanvas offcanvas-end bg-secondary secondary-1" id="navbarOffcanvas" tabindex="-1"
                          aria-labelledby="offcanvasNavbarLabel">
                          <div class="offcanvas-header">
                              <a class="navbar-brand" href="index.html"><img src="{{ asset('assets/images/logo-black.png') }}"
                                      alt="logo" class="logo"></a>
                              <button type="button" class="btn-close btn-close-white text-reset"
                                  data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                  <div class="nav-up">
                              <ul class="nav-ul">
                                  <li><a class="list active" href="{{ route('home') }}">Home</a></li>
                                  <li><a class="list" href="{{ route('program') }}">Program</a></li>
                                  <li><a class="list" href="{{ route('blog') }}">News & Blog</a></li>
                                  <li><a class="list" href="{{ route('about') }}">About Us</a></li>
                                  <li><a class="list" href="{{ route('contact') }}">Contact Us</a></li>
                                  <li><a class="list" href="{{ route('membership') }}">Membership Options</a></li>
                                  {{-- @auth
                                      <li><a class="list" href="{{ route('video-library.index') }}">Video Library</a></li>
                                  @endauth --}}
                                  <li><a class="list" href="{{ route('lesson') }}">Quick Lessons</a></li>
                              </ul>
                          </div>
                          <div class="loginbtns mt-3">
                              @auth
                                  <a class="signupbtn" href="{{ Auth::user()->hasRole('Admin') ? route('admin.dashboard') : route('parent.dashboard') }}">Dashboard</a>
                              @else
                                  <a class="signupbtn" href="{{ route('register') }}">Sign Up</a>
                                  <a class="signinbtn" href="{{ route('login') }}">Sign In</a>
                              @endauth
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </nav>
  </header>
