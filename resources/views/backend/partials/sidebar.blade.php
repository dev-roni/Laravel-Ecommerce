    <div class="overlay" id="overlay"></div>
    <!-- সাইডবার -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span><a href="{{route('shop.index')}}" class="text-decoration-none text-reset" target="_blank"><i class="fa fa-home" aria-hidden="true"></i> Browse Site</a></span>
            <h3>E Shop Admin</h3>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>  
                <a href="{{ route('admin.sales.report') }}"class="nav-link {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Sales Report
                </a>
            </li>
            <li>  
                <a href="{{ route('admin.products.index') }}"class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Products Manage
                </a>
            </li>
            <li>  
                <a href="{{ route('admin.products.create') }}"class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                    <i class="fas fa-plus"></i> Product Add
                </a>
            </li>
            <li>  
                <a href="{{ route('admin.attributes.index') }}"class="nav-link {{ request()->routeIs('admin.attributes.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-sitemap"></i> Product Varient
                </a>
            </li>
            <li>  
                <a href="{{ route('admin.categories.index') }}"class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i> Category Manage
                </a>
            </li>
            <li>  
                <a href="{{ route('admin.categories.create') }}"class="nav-link {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i> Category Create
                </a>
            </li>
            <li> <a href="{{ route('admin.orders.index') }}"
                class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> Orders
                    @if(($pendingCount = \App\Models\Order::where('status','pending')->count()) > 0)
                        <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <li>
                <a href="{{ route('admin.reviews.index') }}"
                class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-pen"></i> Reviews
                </a>
            </li>

            <li>
                <a href="{{ route('admin.refunds.index') }}"
                    class="nav-link {{ request()->routeIs('admin.refunds.*') ? 'active' : '' }}">
                        <i class="fas fa-undo-alt"></i> Refunds
                        @php $pendingRefunds = \App\Models\Refund::where('status','pending')->count(); @endphp
                        @if($pendingRefunds > 0)
                            <span class="badge bg-danger ms-1">{{ $pendingRefunds }}</span>
                        @endif
                </a>
            </li>

            <li>
                <a href="{{ route('admin.coupons.index') }}"
                    class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> Coupons
                </a>
            </li>
            <li>
                <a href="{{ route('admin.coupons.create') }}"
                    class="nav-link {{ request()->routeIs('admin.coupons.create') ? 'active' : '' }}">
                    <i class="fa-solid fa-ticket-simple"></i> Coupon create
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Customers
                </a>
            </li>
            <li><a href="settings.html"><i class="fas fa-cog"></i> Settings</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="border-0 bg-transparent text-white w-100 text-start p-0">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>