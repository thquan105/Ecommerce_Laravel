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
                <a href="#" class="nav-link">
                    <i class="nav-icon fa fa-image"></i>
                    <p>
                        {{ __('Slide') }}
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

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        {{ __('Seller') }}
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('admin.sellers.index') }}" class="nav-link">
                            <i class="fa fa-hashtag nav-icon"></i>
                            <p>Danh sách</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.sellers.show') }}" class="nav-link">
                            <i class="fa fa-user-plus nav-icon"></i>
                            <p>Phê Duyệt</p>
                        </a>
                    </li>    
                </ul>
            </li>

        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
