<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="{{url('/home')}}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#assets-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-collection-fill"></i><span>Assets</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="assets-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('asset.assets') }}">
                        <i class="bi bi-circle"></i><span>View Assets</span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/items')}}">
                        <i class="bi bi-circle"></i><span>View Items</span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/items/create')}}">
                        <i class="bi bi-circle"></i><span>Add New Asset</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('serialnumbers.employee_devices.index')}}">
                        <i class="bi bi-circle"></i><span>Employee Devices</span>
                    </a>
                </li>

            </ul>
        </li><!-- End Components Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#dispatch-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-pencil-square"></i><span>Dispatch</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="dispatch-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{route('dispatch_requests.index')}}">
                        <i class="bi bi-circle"></i><span>View Requests</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('dispatch_requests.index')}}">
                        <i class="bi bi-circle"></i><span>Record Installation</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#maintenance-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-clock"></i><span>Maintenance</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="maintenance-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{route('dispatch_requests.index')}}">
                        <i class="bi bi-circle"></i><span>View Requests</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('dispatch_requests.index')}}">
                        <i class="bi bi-circle"></i><span>Record Returns</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->


        @if(auth()->user()->hasRole('Super Admin'))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Users</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="users-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('users.index') }}">
                        <i class="bi bi-circle"></i><span>All Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('designations.index') }}">
                        <i class="bi bi-circle"></i><span>Designations</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('roles.index') }}">
                        <i class="bi bi-circle"></i><span>Roles</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('permissions.index') }}">
                        <i class="bi bi-circle"></i><span>Permissions</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Tables Nav -->
        @endif

        <li class="nav-heading">Settings</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{url('/profile')}}">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li><!-- End Profile Page Nav -->

        @if(auth()->user()->hasRole('Super Admin'))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#site-settings-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Site Settings</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="site-settings-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{route('companies.index')}}">
                        <i class="bi bi-circle"></i><span>Company</span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/branches')}}">
                        <i class="bi bi-circle"></i><span>Branches</span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/categories')}}">
                        <i class="bi bi-circle"></i><span>Categories</span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/subcategories')}}">
                        <i class="bi bi-circle"></i><span>Subcategories</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('suppliers.index')}}">
                        <i class="bi bi-circle"></i><span>Suppliers</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('brands.index')}}">
                        <i class="bi bi-circle"></i><span>Brands</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Admin Settings Nav -->
        @endif

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('sidebar-logout-form').submit();">
                <i class="bi bi-power"></i>
                <span>Logout</span>
            </a>
            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li><!-- End Logout Page Nav -->

    </ul>

</aside><!-- End Sidebar-->