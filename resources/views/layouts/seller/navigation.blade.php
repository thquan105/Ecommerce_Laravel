<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
            {{-- {{ route('admin.profile.show') }} --}}
            <a href="" class="d-block">Welcome, {{ $user->data()['name'] ?? ""}}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('seller.dashboard') }}" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        {{ __('Thống kế') }}
                    </p>
                </a>
            </li>

            {{-- <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>
                {{ __('Users') }}
            </p>
            </a>
            </li> --}}

            {{-- <li class="nav-item">
                <a href="{{ route('admin.slides.index') }}" class="nav-link">
            <i class="nav-icon fa fa-image"></i>
            <p>
                {{ __('Slide') }}
            </p>
            </a>
            </li> --}}

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle nav-icon"></i>
                    <p>
                        Quản lý Sản Phẩm
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('seller.categories.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Danh mục</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('seller.products.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Sản Phẩm</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle nav-icon"></i>
                    <p>
                        Quản lý đơn hàng
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        {{-- {{ route('admin.orders.index') }} --}}
                        <a href="" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Order</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        {{-- {{ route('admin.shipments.index') }} --}}
                        <a href="" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Pengiriman</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle nav-icon"></i>
                    <p>
                        Báo cáo
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        {{-- {{ route('admin.reports.revenue') }} --}}
                        <a href="" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Keuntungan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        {{-- {{ route('admin.reports.product') }} --}}
                        <a href="" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Produk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        {{-- {{ route('admin.reports.inventory') }} --}}
                        <a href="" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Inventory</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        {{-- {{ route('admin.reports.payment') }} --}}
                        <a href="" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Payment</p>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->