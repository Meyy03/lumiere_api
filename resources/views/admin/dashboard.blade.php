<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lumière | Admin Dashboard</title>

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>

<body>

    <div class="admin-layout">

        {{-- =====================================================
        SHARED SIDEBAR
        ===================================================== --}}

        @include('admin.partials.sidebar')


        {{-- =====================================================
        MAIN CONTENT
        ===================================================== --}}

        <main class="main-content">


            {{-- =================================================
            TOP BAR
            ================================================= --}}

            <header class="topbar">

                <div>

                    <h1>
                        Admin Dashboard
                    </h1>

                    <p>
                        Welcome back, {{ Auth::user()->name }}.
                    </p>

                </div>


                <div class="topbar-right">

                    <div class="date-box">

                        <span>
                            ◔
                        </span>

                        <span>
                            {{ now()->format('d M Y') }}
                        </span>

                    </div>

                </div>

            </header>


            {{-- =================================================
            DASHBOARD CONTENT
            ================================================= --}}

            <section class="dashboard-content">


                {{-- =================================================
                WELCOME / HERO
                ================================================= --}}

                <div class="welcome-card">

                    <div>

                        <p class="welcome-label">
                            LUMIÈRE ADMIN PORTAL
                        </p>


                        <h2>
                            Everything you need to manage your store.
                        </h2>


                        <p>
                            Manage products, categories, customer orders
                            and business information from one dashboard.
                        </p>

                    </div>


                    {{-- REAL LUMIÈRE LOGO --}}
                    <div class="welcome-symbol">

                        <img src="{{ asset('images/admin/lumiere-logo.png') }}" alt="Lumière Logo"
                            class="welcome-logo-image">

                    </div>

                </div>


                {{-- =================================================
                STATISTICS
                ================================================= --}}

                <div class="stats-grid">


                    {{-- TOTAL PRODUCTS --}}
                    <div class="stat-card">

                        <div class="stat-top">

                            <span class="stat-title">
                                Total Products
                            </span>

                            <div class="stat-icon">
                                ♡
                            </div>

                        </div>


                        <h3>
                            {{ $totalProducts ?? 0 }}
                        </h3>


                        <p>
                            Products available in the Lumière store
                        </p>

                    </div>


                    {{-- TOTAL ORDERS --}}
                    <div class="stat-card">

                        <div class="stat-top">

                            <span class="stat-title">
                                Total Orders
                            </span>

                            <div class="stat-icon">
                                ◉
                            </div>

                        </div>


                        <h3>
                            {{ $totalOrders ?? 0 }}
                        </h3>


                        <p>
                            Customer orders placed
                        </p>

                    </div>


                    {{-- TOTAL CUSTOMERS --}}
                    <div class="stat-card">

                        <div class="stat-top">

                            <span class="stat-title">
                                Customers
                            </span>

                            <div class="stat-icon">
                                ♙
                            </div>

                        </div>


                        <h3>
                            {{ $totalCustomers ?? 0 }}
                        </h3>


                        <p>
                            Registered customer accounts
                        </p>

                    </div>


                    {{-- REVENUE --}}
                    <div class="stat-card">

                        <div class="stat-top">

                            <span class="stat-title">
                                Revenue
                            </span>

                            <div class="stat-icon">
                                $
                            </div>

                        </div>


                        <h3>
                            ${{ number_format($totalRevenue ?? 0, 2) }}
                        </h3>


                        <p>
                            Revenue from paid orders
                        </p>

                    </div>

                </div>


                {{-- =================================================
                LOWER INFORMATION GRID
                ================================================= --}}

                <div class="dashboard-grid">


                    {{-- =================================================
                    ADMINISTRATOR ACCOUNT
                    ================================================= --}}

                    <div class="content-card">

                        <div class="card-header">

                            <h3>
                                Administrator Account
                            </h3>

                            <p>
                                Current authenticated administrator
                            </p>

                        </div>


                        <div class="account-info">


                            <div class="info-row">

                                <span>
                                    Name
                                </span>

                                <strong>
                                    {{ Auth::user()->name }}
                                </strong>

                            </div>


                            <div class="info-row">

                                <span>
                                    Email
                                </span>

                                <strong>
                                    {{ Auth::user()->email }}
                                </strong>

                            </div>


                            <div class="info-row">

                                <span>
                                    Role
                                </span>

                                <strong class="role-badge">
                                    Administrator
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    SYSTEM STATUS
                    ================================================= --}}

                    <div class="content-card">

                        <div class="card-header">

                            <h3>
                                System Status
                            </h3>

                            <p>
                                Lumière administration system
                            </p>

                        </div>


                        <div class="status-list">


                            {{-- ADMIN AUTH --}}
                            <div class="status-row">

                                <div>

                                    <span class="status-dot"></span>

                                    <span>
                                        Admin Authentication
                                    </span>

                                </div>

                                <strong>
                                    Active
                                </strong>

                            </div>


                            {{-- LARAVEL --}}
                            <div class="status-row">

                                <div>

                                    <span class="status-dot"></span>

                                    <span>
                                        Laravel Application
                                    </span>

                                </div>

                                <strong>
                                    Running
                                </strong>

                            </div>


                            {{-- DATABASE --}}
                            <div class="status-row">

                                <div>

                                    <span class="status-dot"></span>

                                    <span>
                                        Database Connection
                                    </span>

                                </div>

                                <strong>
                                    Connected
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                FOOTER
                ================================================= --}}

                <footer class="dashboard-footer">

                    © {{ date('Y') }} Lumière Admin System.
                    All rights reserved.

                </footer>

            </section>

        </main>

    </div>

</body>

</html>