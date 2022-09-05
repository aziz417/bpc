<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">

            <div class="image">
                <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                     alt="User Image">
            </div>

            <div class="info">
                <a href="#" class="d-block">{{ ucfirst(Auth::user()->name) }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @hasrole('supper-admin|admin')
                @hasrole('admin|supper-admin')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @endhasrole

                <li class="nav-item has-treeview {{ Request::routeIs('product','product.create', 'product.edit','stock','stock.edit') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{  Request::routeIs('product','product.create', 'product.edit','stock','stock.edit') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                        <p>
                            Product Manage
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @hasrole('supper-admin')
                        <li class="nav-item">
                            <a href="{{ route('product') }}"
                               class="nav-link {{ Request::routeIs('product','product.create','product.edit') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle text-warning"></i>
                                <p>Product</p>
                            </a>
                        </li>
                        @endhasrole
                        @hasrole('admin|supper-admin')
                        <li class="nav-item">
                            <a href="{{ route('stock') }}"
                               class="nav-link {{ Request::routeIs('stock','stock.edit') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle text-warning"></i>
                                <p>Stock</p>
                            </a>
                        </li>
                        @endhasrole

                    </ul>
                </li>
                @hasrole('admin|supper-admin')
                <li class="nav-item">
                    <a href="{{ route('product_distribution') }}"
                       class="nav-link {{ Request::routeIs('product_distribution','product_distribution.edit') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-sitemap" aria-hidden="true"></i>
                        <p>Product Distribution</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('report') }}"
                       class="nav-link {{ Request::routeIs('report','report') ? 'active' : '' }}">
                        <i class="fa fa-print" aria-hidden="true"></i>
                        <p>Report</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.sales.index') }}"
                       class="nav-link {{ Request::routeIs('admin.sales.index') ? 'active' : '' }}">
                        <i class="fa fa-print" aria-hidden="true"></i>
                        <p>Admin Sales</p>
                    </a>
                </li>
                @endhasrole
                @hasrole('supper-admin')
                <li class="nav-item">
                    <a href="{{ route('category') }}"
                       class="nav-link {{ Request::routeIs('category','category.create','category.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Category</p>
                    </a>
                </li>
                <li class="nav-item has-treeview {{ Request::routeIs('permission','permission.create','permission.edit', 'role','role.create','role.edit', 'user','user.create','user.edit') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{ Request::routeIs('permission','permission.create','permission.edit', 'role','role.create','role.edit', 'user','user.create','user.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            User Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">

                            <a href="{{ route('user') }}"
                               class="nav-link {{ Request::routeIs('user','user.create','user.edit') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle text-warning"></i>

                                <p>User</p>
                            </a>

                            <a href="{{ route('role') }}"
                               class="nav-link {{ Request::routeIs('role','role.create','role.edit') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle text-warning"></i>
                                <p>Role</p>
                            </a>

                            <a href="{{ route('permission') }}"
                               class="nav-link {{ Request::routeIs('permission','permission.create','permission.edit') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle text-warning"></i>
                                <p>Permission</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{ Request::routeIs('branch_type','branch_type.create','branch_type.edit','branch','branch.create','branch.edit', 'attribute', 'attribute.create','attribute.edit') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{  Request::routeIs('branch_type','branch_type.create','branch_type.edit','branch','branch.create','branch.edit', 'attribute', 'attribute.create','attribute.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">

                            <a href="{{ route('branch') }}"
                               class="nav-link {{ Request::routeIs('branch','branch.create','branch.edit') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle text-warning"></i>
                                <p>Branch</p>
                            </a>

                            <a href="{{ route('branch_type') }}"
                               class="nav-link {{ Request::routeIs('branch_type','branch_type.create','branch_type.edit') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle text-warning"></i>
                                <p>Branch Type</p>
                            </a>

                            <a href="{{ route('attribute') }}"
                               class="nav-link {{ Request::routeIs('attribute','attribute.create','attribute.edit') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle text-warning"></i>
                                <p>Attributes</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endhasrole
                @endhasrole

                @hasrole('seller|admin')
                <li class="nav-item">
                    <a href="{{ route('seller.dashboard') }}"
                       class="nav-link {{ Request::routeIs('seller.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Seller Dashboard</p>
                    </a>
                </li>


                <li class="nav-item has-treeview {{ Request::routeIs('seller.sale.create', 'seller.sale.index', 'seller.sale.show') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{  Request::routeIs('seller.sale.create', 'seller.sale.index', 'seller.sale.show') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                        <p>
                            Sale Manage
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('seller.sale.create') }}"
                               class="nav-link {{ Request::routeIs('seller.sale.create') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle text-warning"></i>
                                <p>Sale Create</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.sale.index') }}"
                               class="nav-link {{ Request::routeIs('seller.sale.index', 'seller.sale.show') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle text-warning"></i>
                                <p>Sale Index</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endhasrole

                @hasrole('seller')
                    <li class="nav-item">
                        <a href="{{ route('report') }}"
                           class="nav-link {{ Request::routeIs('report','report') ? 'active' : '' }}">
                            <i class="fa fa-print" aria-hidden="true"></i>
                            <p>Report</p>
                        </a>
                    </li>
                @endhasrole
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
