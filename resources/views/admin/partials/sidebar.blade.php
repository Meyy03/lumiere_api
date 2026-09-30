<aside class="sidebar">

    {{-- =====================================================
    BRAND
    ===================================================== --}}

    <div class="sidebar-brand">

        <div class="sidebar-logo">

            <img src="{{ asset('images/admin/lumiere-logo.png') }}" alt="Lumière Logo" class="sidebar-logo-image">

        </div>


        <div class="sidebar-brand-text">

            <h2>
                LUMIÈRE
            </h2>

            <p>
                ADMIN SYSTEM
            </p>

        </div>

    </div>


    <div class="sidebar-divider"></div>


    {{-- =====================================================
    MENU
    ===================================================== --}}

    <nav class="sidebar-menu">


        {{-- DASHBOARD --}}

        <a href="{{ route('admin.dashboard') }}" class="menu-item
            {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

            <span class="menu-icon">
                ◫
            </span>

            <span>
                Dashboard
            </span>

        </a>


        {{-- PRODUCTS --}}

        <a href="{{ route('admin.products.index') }}" class="menu-item
            {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">

            <span class="menu-icon">
                ♡
            </span>

            <span>
                Products
            </span>

        </a>


        {{-- CATEGORIES --}}

        <a href="{{ route('admin.categories.index') }}" class="menu-item
            {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">

            <span class="menu-icon">
                ▦
            </span>

            <span>
                Categories
            </span>

        </a>


        {{-- ORDERS --}}

        <a href="{{ route('admin.orders.index') }}" class="menu-item
            {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">

            <span class="menu-icon">
                ◉
            </span>

            <span>
                Orders
            </span>

        </a>


        {{-- CUSTOMERS --}}

        <a href="{{ route('admin.customers.index') }}" class="menu-item
            {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">

            <span class="menu-icon">
                ♙
            </span>

            <span>
                Customers
            </span>

        </a>


        {{-- REPORTS --}}

        <a href="{{ route('admin.reports.index') }}" class="menu-item
            {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">

            <span class="menu-icon">
                ▥
            </span>

            <span>
                Reports
            </span>

        </a>

    </nav>


    {{-- =====================================================
    BOTTOM
    ===================================================== --}}

    <div class="sidebar-bottom">


        <div class="admin-profile">

            <div class="admin-avatar">

                <img src="{{ asset('images/admin/lumiere-logo.png') }}" alt="Lumière Admin" class="admin-avatar-image">

            </div>


            <div class="admin-profile-info">

                <strong>
                    {{ Auth::user()->name }}
                </strong>

                <span>
                    Administrator
                </span>

            </div>

        </div>


        {{-- =================================================
        LOGOUT
        ================================================= --}}

        <form method="POST" action="{{ route('admin.logout') }}" data-confirm data-confirm-type="logout"
            data-confirm-title="Log Out?"
            data-confirm-message="Are you sure you want to sign out of the Lumière Admin System?"
            data-confirm-button="Yes, Log Out">

            @csrf


            <button type="submit" class="logout-button">

                <span>
                    ↪
                </span>

                Logout

            </button>

        </form>

    </div>

</aside>


{{-- =========================================================
SHARED ADMIN MODAL
========================================================= --}}

@include('admin.partials.confirm-modal')