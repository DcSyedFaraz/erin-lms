 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-dark">
     <!-- Left navbar links -->
     <ul class="navbar-nav">
         <li class="nav-item">
             <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
         </li>
         {{-- <li class="nav-item d-none d-sm-inline-block">
             <a href="index3.html" class="nav-link">Home</a>
         </li>
         <li class="nav-item d-none d-sm-inline-block">
             <a href="#" class="nav-link">Contact</a>
         </li> --}}
     </ul>

     <!-- Right navbar links -->
     <ul class="navbar-nav ml-auto">
        @if(auth()->check() && auth()->user()->hasRole('Parent'))
        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="modal" data-target="#childrenProfilesModal" title="Profiles">
                <i class="fas fa-users"></i>
            </a>
        </li>
        @endif

         <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="theme-switcher" href="#" role="button">
                <i class="fas fa-sun"></i>
            </a>
        </li>
     </ul>
 </nav>
 <!-- /.navbar -->
