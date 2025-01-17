<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{url('/home')}}" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/images/fon_logo.png')}}" alt="">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    @include('components.navbar.search')

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->

            @include('components.navbar.notifications')

            @include('components.navbar.messages')

            @include('components.navbar.user')

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->