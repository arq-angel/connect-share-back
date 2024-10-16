<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                        class="fas fa-search"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="../assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">Logged in {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Never' }}</div>
                <a href="{{route('profile.edit')}}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                {{--<a href="#" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Settings
                </a>--}}
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                       class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </form>

            </div>
        </li>
    </ul>
</nav>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url("/") }}">Connect-Share</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item {{ setSidebarActive(['dashboard']) }}">
                <a href="{{ route("dashboard") }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Sections</li>

            <li class="{{ setSidebarActive(['admin.company.*']) }}">
                <a class="nav-link" href="{{ route("admin.company.index") }}"><i class="far fa-building"></i> <span>Company</span></a>
            </li>

            <li class="{{ setSidebarActive(['admin.job-title.*']) }}">
                <a class="nav-link" href="{{ route("admin.job-title.index") }}"><i class="fas fa-briefcase"></i> <span>Job Title</span></a>
            </li>

            <li class="{{ setSidebarActive(['admin.department.*']) }}">
                <a class="nav-link" href="{{ route("admin.department.index") }}"><i class="fas fa-sitemap"></i> <span>Department</span></a>
            </li>

            <li class="{{ setSidebarActive(['admin.facility.*']) }}">
                <a class="nav-link" href="{{ route("admin.facility.index") }}"><i class="far fa-hospital"></i> <span>Facility</span></a>
            </li>

            <li class="{{ setSidebarActive(['admin.employee.*']) }}">
                <a class="nav-link" href="{{ route("admin.employee.index") }}"><i class="fas fa-user"></i> <span>Employee</span></a>
            </li>

            <li class="{{ setSidebarActive(['admin.assignment.*']) }}">
                <a class="nav-link" href="{{ route("admin.assignment.index") }}"><i class="fas fa-user-plus"></i> <span>Assignment</span></a>
            </li>

            <li class="{{ setSidebarActive(['admin.organization.*']) }}">
                <a class="nav-link" href="{{ route("admin.organization.index") }}"><i class="fas fa-project-diagram"></i> <span>Organizational Chart</span></a>
            </li>

            {{--            <li class="nav-item dropdown {{ setSidebarActive(['admin.typer-title.*', 'admin.hero.*']) }}">--}}
            {{--                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Hero</span></a>--}}
            {{--                <ul class="dropdown-menu" style="display: none;">--}}
            {{--                    <li class="{{ setSidebarActive(['admin.employee.*']) }}">--}}
            {{--                        <a class="nav-link" href="{{ route("admin.employee.index") }}">Typer title</a>--}}
            {{--                    </li>--}}
            {{--                    <li class="{{ setSidebarActive(['admin..*']) }}">--}}
            {{--                        <a class="nav-link" href="{{ route("admin.hero.index") }}">Hero section</a>--}}
            {{--                    </li>--}}
            {{--                </ul>--}}
            {{--            </li>--}}


        </ul>
    </aside>
</div>

