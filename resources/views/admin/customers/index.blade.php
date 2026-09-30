<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lumière | Customers</title>

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/customers.css') }}">
</head>

<body>

    <div class="admin-layout">

        {{-- =====================================================
        SIDEBAR
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
                        Customer Management
                    </h1>

                    <p>
                        View and manage Lumière customer information.
                    </p>
                </div>

            </header>


            {{-- =================================================
            CONTENT
            ================================================= --}}
            <section class="dashboard-content">


                {{-- =================================================
                STATISTICS
                ================================================= --}}
                <div class="customer-stat-grid">

                    {{-- TOTAL CUSTOMERS --}}
                    <div class="customer-stat-card">

                        <div>
                            <span class="stat-label">
                                Total Customers
                            </span>

                            <strong class="stat-value">
                                {{ number_format($totalCustomers) }}
                            </strong>

                            <p>
                                Registered customer accounts
                            </p>
                        </div>

                        <div class="stat-icon">
                            ♙
                        </div>

                    </div>


                    {{-- CUSTOMERS WITH ORDERS --}}
                    <div class="customer-stat-card">

                        <div>
                            <span class="stat-label">
                                Customers With Orders
                            </span>

                            <strong class="stat-value">
                                {{ number_format($customersWithOrders) }}
                            </strong>

                            <p>
                                Customers who placed orders
                            </p>
                        </div>

                        <div class="stat-icon">
                            ✓
                        </div>

                    </div>


                    {{-- TOTAL CUSTOMER ORDERS --}}
                    <div class="customer-stat-card">

                        <div>
                            <span class="stat-label">
                                Total Customer Orders
                            </span>

                            <strong class="stat-value">
                                {{ number_format($totalCustomerOrders) }}
                            </strong>

                            <p>
                                Orders from customer accounts
                            </p>
                        </div>

                        <div class="stat-icon">
                            ◉
                        </div>

                    </div>


                    {{-- PAID REVENUE --}}
                    <div class="customer-stat-card">

                        <div>
                            <span class="stat-label">
                                Paid Revenue
                            </span>

                            <strong class="stat-value">
                                ${{ number_format(
    (float) $totalCustomerRevenue,
    2
) }}
                            </strong>

                            <p>
                                Revenue from paid orders
                            </p>
                        </div>

                        <div class="stat-icon">
                            $
                        </div>

                    </div>

                </div>


                {{-- =================================================
                SEARCH
                ================================================= --}}
                <div class="customer-search-card">

                    <form method="GET" action="{{ route('admin.customers.index') }}" class="customer-search-form">

                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Search customer name, email, phone or ID..." autocomplete="off">

                        <button type="submit" class="customer-search-button">
                            Search
                        </button>

                        <a href="{{ route('admin.customers.index') }}" class="customer-clear-button">
                            Clear
                        </a>

                    </form>

                </div>


                {{-- =================================================
                CUSTOMERS TABLE
                ================================================= --}}
                <div class="customers-card">

                    {{-- CARD HEADER --}}
                    <div class="customers-card-header">

                        <div>
                            <h2>
                                Customers
                            </h2>

                            <p>
                                {{ number_format($customers->total()) }}
                                customer(s) found
                            </p>
                        </div>

                    </div>


                    {{-- TABLE --}}
                    <div class="customers-table-wrapper">

                        <table class="customers-table">

                            <thead>

                                <tr>
                                    <th>ID</th>
                                    <th>CUSTOMER</th>
                                    <th>PHONE</th>
                                    <th>PROVINCE</th>
                                    <th>ORDERS</th>
                                    <th>FAVORITES</th>
                                    <th>TOTAL SPENT</th>
                                    <th>JOINED</th>
                                    <th>ACTION</th>
                                </tr>

                            </thead>


                            <tbody>

                                @forelse($customers as $customer)

                                                                @php
                                                                    /*
                                                                    |--------------------------------------------------------------------------
                                                                    | PROFILE IMAGE
                                                                    |--------------------------------------------------------------------------
                                                                    |
                                                                    | Customer profile photos are uploaded by the
                                                                    | Flutter mobile app to Laravel public storage.
                                                                    |
                                                                    | If the image exists, show it.
                                                                    | Otherwise show the first letter of the name.
                                                                    |
                                                                    */

                                                                    $hasProfileImage =
                                                                        !empty($customer->profile_image) &&
                                                                        \Illuminate\Support\Facades\Storage::disk('public')
                                                                            ->exists($customer->profile_image);
                                                                @endphp


                                                                <tr>

                                                                    {{-- ID --}}
                                                                    <td>

                                                                        <span class="customer-id">
                                                                            #{{ $customer->id }}
                                                                        </span>

                                                                    </td>


                                                                    {{-- CUSTOMER --}}
                                                                    <td>

                                                                        <div class="customer-profile-cell">

                                                                            {{-- PROFILE AVATAR --}}
                                                                            <div class="
                                                                                customer-avatar
                                                                                {{ $hasProfileImage ? 'has-image' : '' }}
                                                                            ">

                                                                                @if($hasProfileImage)

                                                                                                                            <img src="{{ asset(
                                                                                        'storage/' .
                                                                                        ltrim(
                                                                                            $customer->profile_image,
                                                                                            '/'
                                                                                        )
                                                                                    ) }}" alt="{{ $customer->name }} profile photo"
                                                                                                                                class="customer-avatar-image" loading="lazy">

                                                                                @else

                                                                                                                            <span class="customer-avatar-letter">

                                                                                                                                {{ strtoupper(
                                                                                        substr(
                                                                                            $customer->name,
                                                                                            0,
                                                                                            1
                                                                                        )
                                                                                    ) }}

                                                                                                                            </span>

                                                                                @endif

                                                                            </div>


                                                                            {{-- CUSTOMER NAME / EMAIL --}}
                                                                            <div>

                                                                                <strong>
                                                                                    {{ $customer->name }}
                                                                                </strong>

                                                                                <span>
                                                                                    {{ $customer->email }}
                                                                                </span>

                                                                            </div>

                                                                        </div>

                                                                    </td>


                                                                    {{-- PHONE --}}
                                                                    <td>
                                                                        {{ $customer->phone ?: '—' }}
                                                                    </td>


                                                                    {{-- PROVINCE --}}
                                                                    <td>
                                                                        {{ $customer->province ?: '—' }}
                                                                    </td>


                                                                    {{-- ORDERS --}}
                                                                    <td>

                                                                        <span class="customer-count-badge">

                                                                            {{ number_format(
                                        (int) ($customer->order_count ?? 0)
                                    ) }}

                                                                        </span>

                                                                    </td>


                                                                    {{-- FAVORITES --}}
                                                                    <td>

                                                                        <span class="customer-count-badge favorite">

                                                                            {{ number_format(
                                        (int) ($customer->favorite_count ?? 0)
                                    ) }}

                                                                        </span>

                                                                    </td>


                                                                    {{-- TOTAL SPENT --}}
                                                                    <td>

                                                                        <strong class="customer-spent">

                                                                            ${{ number_format(
                                        (float) ($customer->total_spent ?? 0),
                                        2
                                    ) }}

                                                                        </strong>

                                                                    </td>


                                                                    {{-- JOINED DATE --}}
                                                                    <td>

                                                                        @if($customer->created_at)

                                                                                                            {{ \Carbon\Carbon::parse(
                                                                                $customer->created_at
                                                                            )->format('d M Y') }}

                                                                        @else

                                                                            —

                                                                        @endif

                                                                    </td>


                                                                    {{-- ACTION --}}
                                                                    <td>

                                                                        <a href="{{ route(
                                        'admin.customers.show',
                                        $customer->id
                                    ) }}" class="customer-view-button">
                                                                            View Details
                                                                        </a>

                                                                    </td>

                                                                </tr>


                                @empty

                                    <tr>

                                        <td colspan="9" class="customers-empty">
                                            No customers found.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                    PAGINATION
                    ================================================= --}}
                    @if($customers->hasPages())

                        <div class="customers-pagination">

                            {{ $customers->links() }}

                        </div>

                    @endif

                </div>

            </section>

        </main>

    </div>

</body>

</html>