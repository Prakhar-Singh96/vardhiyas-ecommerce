<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                {{-- Logo hai toh: --}}
                {{-- <img src="{{ asset('admin/assets/img/logo/vardiyash.png') }}" alt="Logo" width="180"> --}}
                <span
                    style="font-size:1.3rem;font-weight:900;color:#696cff;
                             letter-spacing:-1px;font-style:italic">VARDIYASH</span>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- ════════════════════════════
             DASHBOARD
        ════════════════════════════ --}}
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        {{-- ════════════════════════════
             CATALOGUE
        ════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Catalogue</span>
        </li>

        {{-- Categories --}}
        <li class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div>Categories</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}" class="menu-link">
                        <div>All Categories</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.create') }}" class="menu-link">
                        <div>Add Category</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Filters --}}
        <li
            class="menu-item {{ request()->routeIs('admin.filter-types.*') || request()->routeIs('admin.filter-values.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-filter-alt"></i>
                <div>Filters</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.filter-types.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.filter-types.index') }}" class="menu-link">
                        <div>All Filters</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.filter-types.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.filter-types.create') }}" class="menu-link">
                        <div>Add Filter Type</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Products --}}
        <li class="menu-item {{ request()->routeIs('admin.products.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div>Products</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}" class="menu-link">
                        <div>All Products</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.create') }}" class="menu-link">
                        <div>Add Product</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- ════════════════════════════
             SALES
        ════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Sales</span>
        </li>

        {{-- Orders --}}
        <li class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cart-alt"></i>
                <div>Orders</div>
                {{-- Badge: pending orders count --}}
                {{-- <div class="badge bg-danger rounded-pill ms-auto">5</div> --}}
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}" class="menu-link">
                        <div>All Orders</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <a href="{{ route('admin.settings.edit') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div>Settings</div>
            </a>
        </li>

        {{-- Coupons --}}
        <li class="menu-item {{ request()->routeIs('admin.coupons.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-purchase-tag-alt"></i>
                <div>Coupons</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>All Coupons</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div>Add Coupon</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- ════════════════════════════
             MARKETING
        ════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Marketing</span>
        </li>

        {{-- Banners --}}
        <li class="menu-item {{ request()->routeIs('admin.banners.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-images"></i>
                <div>Banners</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.banners.index') }}" class="menu-link">
                        <div>All Banners</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.banners.create') }}" class="menu-link">
                        <div>Add Banner</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.mood-sections.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                <div>Match The Mood</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.mood-sections.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.mood-sections.index') }}" class="menu-link">
                        <div>All Mood Cards</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.mood-sections.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.mood-sections.create') }}" class="menu-link">
                        <div>Add Card</div>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Reviews --}}
        <li class="menu-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-star"></i>
                <div>Reviews</div>
            </a>
        </li>

        {{-- ════════════════════════════
             CUSTOMERS
        ════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Customers</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div>All Customers</div>
            </a>
        </li>

        {{-- ════════════════════════════
             SETTINGS
        ════════════════════════════ --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Settings</span>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div>Store Settings</div>
            </a>
        </li>

        {{-- Logout --}}
        <li class="menu-item mt-2">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="menu-link border-0 bg-transparent w-100 text-start"
                    style="color:#697a8d">
                    <i class="menu-icon tf-icons bx bx-log-out"></i>
                    <div>Logout</div>
                </button>
            </form>
        </li>

    </ul>
</aside>
