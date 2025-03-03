<ul class="side-nav">

    {{-- <li class="side-nav-item">
        <a href="/dashboard" class="side-nav-link">
            <i class="uil-home-alt"></i>
            <span> Dashboards </span>
        </a>
    </li> --}}
    <li class="side-nav-title side-nav-item">Manajemen</li>

    <li class="side-nav-item">
        <a href="/managemen-video" class="side-nav-link">
            <i class="uil-box"></i>
            <span>Video </span>
        </a>
    </li>

    <li class="side-nav-title side-nav-item">Setting</li>

    <li class="side-nav-item">
        @can('view-user')
            <a href="/users" class="side-nav-link">
                <i class="dripicons-user-group"></i>
                <span>Users</span>
            </a>
        @endcan
        @can('view-permission')
            <a href="/permissions" class="side-nav-link">
                <i class="uil-lock-access"></i>
                <span>Permissions</span>
            </a>
        @endcan
        @can('view-role')
            <a href="/roles" class="side-nav-link">
                <i class="uil-gold"></i>
                <span>Roles</span>
            </a>
        </li>
    @endcan
    <li class="side-nav-item mt-4">
        <hr>
        <a href="/logout" class="side-nav-link">
            <i class="dripicons-user-group"></i>
            <span> Logput </span>
        </a>
    </li>

</ul>
