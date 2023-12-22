<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
            {{-- {{ route('admin.profile.show') }} --}}
            <a href="" class="d-block">Welcome, ADMIN</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        {{ __('Home') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                {{-- {{ route('admin.slides.index') }} --}}
                <a href="" class="nav-link">
                    <i class="nav-icon fa fa-image"></i>
                    <p>
                        {{ __('Slide') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                {{-- {{ route('admin.users.index') }} --}}
                <a href="" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        {{ __('Seller') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link">
                    <i class="fa fa-spinner nav-icon"></i>
                    <p>
                        {{ __('Category') }}
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
