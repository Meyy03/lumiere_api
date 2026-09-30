<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lumière | Orders</title>

    {{-- SHARED ADMIN CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    {{-- ORDERS CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin/orders.css') }}">
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
                        Order Management
                    </h1>

                    <p>
                        View and manage Lumière customer orders.
                    </p>

                </div>

            </header>


            {{-- =================================================
            CONTENT
            ================================================= --}}

            <section class="dashboard-content">


                {{-- =================================================
                SUCCESS MESSAGE
                ================================================= --}}

                @if(session('success'))

                    <div class="order-alert success">

                        <span>
                            ✓
                        </span>

                        <div>
                            {{ session('success') }}
                        </div>

                    </div>

                @endif


                {{-- =================================================
                STATISTICS
                ================================================= --}}

                <div class="order-stats">


                    {{-- TOTAL ORDERS --}}
                    <div class="order-stat-card">

                        <div>

                            <span class="stat-label">
                                Total Orders
                            </span>

                            <h2>
                                {{ number_format($totalOrders) }}
                            </h2>

                            <p>
                                Customer orders placed
                            </p>

                        </div>

                        <div class="order-stat-icon">
                            ◉
                        </div>

                    </div>


                    {{-- PROCESSING --}}
                    <div class="order-stat-card">

                        <div>

                            <span class="stat-label">
                                Processing
                            </span>

                            <h2>
                                {{ number_format($processingOrders) }}
                            </h2>

                            <p>
                                Orders being processed
                            </p>

                        </div>

                        <div class="order-stat-icon">
                            ◷
                        </div>

                    </div>


                    {{-- DELIVERED --}}
                    <div class="order-stat-card">

                        <div>

                            <span class="stat-label">
                                Delivered
                            </span>

                            <h2>
                                {{ number_format($deliveredOrders) }}
                            </h2>

                            <p>
                                Completed deliveries
                            </p>

                        </div>

                        <div class="order-stat-icon">
                            ✓
                        </div>

                    </div>


                    {{-- PAID REVENUE --}}
                    <div class="order-stat-card">

                        <div>

                            <span class="stat-label">
                                Paid Revenue
                            </span>

                            <h2>
                                ${{ number_format($paidRevenue, 2) }}
                            </h2>

                            <p>
                                Revenue from paid orders
                            </p>

                        </div>

                        <div class="order-stat-icon">
                            $
                        </div>

                    </div>

                </div>


                {{-- =================================================
                FILTERS
                ================================================= --}}

                <div class="order-toolbar">

                    <form method="GET" action="{{ route('admin.orders.index') }}" class="order-filter-form">

                        {{-- SEARCH --}}
                        <div class="order-search">

                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Search order number, customer, phone or email..." autocomplete="off">

                        </div>


                        {{-- ORDER STATUS --}}
                        <select name="status" class="order-select">

                            <option value="">
                                All Order Statuses
                            </option>

                            <option value="processing" @selected($status === 'processing')>
                                Processing
                            </option>

                            <option value="delivered" @selected($status === 'delivered')>
                                Delivered
                            </option>

                            <option value="cancelled" @selected($status === 'cancelled')>
                                Cancelled
                            </option>

                        </select>


                        {{-- PAYMENT STATUS --}}
                        <select name="payment_status" class="order-select">

                            <option value="">
                                All Payments
                            </option>

                            <option value="paid" @selected($paymentStatus === 'paid')>
                                Paid
                            </option>

                            <option value="pending" @selected($paymentStatus === 'pending')>
                                Pending
                            </option>

                        </select>


                        {{-- SEARCH BUTTON --}}
                        <button type="submit" class="order-search-button">
                            Search
                        </button>


                        {{-- CLEAR BUTTON --}}
                        <a href="{{ route('admin.orders.index') }}" class="order-clear-button">
                            Clear
                        </a>

                    </form>

                </div>


                {{-- =================================================
                ORDERS TABLE
                ================================================= --}}

                <div class="orders-table-card">


                    {{-- TABLE HEADER --}}
                    <div class="orders-card-header">

                        <div>

                            <h2>
                                Orders
                            </h2>

                            <p>
                                {{ number_format($orders->total()) }}
                                order(s) found
                            </p>

                        </div>

                    </div>


                    {{-- TABLE --}}
                    <div class="orders-table-wrapper">

                        <table class="orders-table">

                            <thead>

                                <tr>

                                    <th>
                                        ORDER
                                    </th>

                                    <th>
                                        CUSTOMER
                                    </th>

                                    <th>
                                        DATE
                                    </th>

                                    <th>
                                        ITEMS
                                    </th>

                                    <th>
                                        TOTAL
                                    </th>

                                    <th>
                                        PAYMENT
                                    </th>

                                    <th>
                                        PAYMENT STATUS
                                    </th>

                                    <th>
                                        ORDER STATUS
                                    </th>

                                    <th>
                                        ACTION
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($orders as $order)

                                                                @php

                                                                    /*
                                                                     * Convert the old "pending" order status
                                                                     * to "Processing" for the administrator UI.
                                                                     */

                                                                    $rawStatus = strtolower(
                                                                        (string) $order->status
                                                                    );

                                                                    $displayStatus = in_array(
                                                                        $rawStatus,
                                                                        ['pending', 'processing']
                                                                    )
                                                                        ? 'Processing'
                                                                        : ucfirst($rawStatus);


                                                                    /*
                                                                     * Payment status remains separate
                                                                     * from fulfilment/order status.
                                                                     */

                                                                    $payment = strtolower(
                                                                        (string) $order->payment_status
                                                                    );

                                                                @endphp


                                                                <tr>


                                                                    {{-- ORDER NUMBER --}}
                                                                    <td>

                                                                        <div class="order-number-cell">

                                                                            <strong>
                                                                                {{ $order->order_number }}
                                                                            </strong>

                                                                            <span>
                                                                                #{{ $order->id }}
                                                                            </span>

                                                                        </div>

                                                                    </td>


                                                                    {{-- CUSTOMER --}}
                                                                    <td>

                                                                        <div class="order-customer-cell">

                                                                            <strong>
                                                                                {{ $order->customer_name }}
                                                                            </strong>

                                                                            <span>
                                                                                {{ $order->customer_email ?? 'No email' }}
                                                                            </span>

                                                                            <small>
                                                                                {{ $order->customer_phone }}
                                                                            </small>

                                                                        </div>

                                                                    </td>


                                                                    {{-- DATE --}}
                                                                    <td>

                                                                        @if($order->ordered_at)

                                                                                                            {{ \Carbon\Carbon::parse(
                                                                                $order->ordered_at
                                                                            )->format('d M Y') }}

                                                                        @elseif($order->created_at)

                                                                                                            {{ \Carbon\Carbon::parse(
                                                                                $order->created_at
                                                                            )->format('d M Y') }}

                                                                        @else

                                                                            —

                                                                        @endif

                                                                    </td>


                                                                    {{-- ITEM COUNT --}}
                                                                    <td>

                                                                        <span class="item-count-badge">

                                                                            {{ number_format(
                                        $order->item_count
                                    ) }}

                                                                        </span>

                                                                    </td>


                                                                    {{-- TOTAL --}}
                                                                    <td>

                                                                        <strong class="order-total">

                                                                            ${{ number_format(
                                        (float) $order->total,
                                        2
                                    ) }}

                                                                        </strong>

                                                                    </td>


                                                                    {{-- PAYMENT METHOD --}}
                                                                    <td>

                                                                        <span class="payment-method">

                                                                            {{ strtoupper((string) $order->payment_method) }}

                                                                        </span>

                                                                    </td>


                                                                    {{-- PAYMENT STATUS --}}
                                                                    <td>

                                                                        <span class="payment-status {{ $payment }}">
                                                                            {{ ucfirst($payment) }}
                                                                        </span>

                                                                    </td>


                                                                    {{-- ORDER STATUS --}}
                                                                    <td>

                                                                        <span class="order-status {{ strtolower($displayStatus) }}">
                                                                            {{ $displayStatus }}
                                                                        </span>

                                                                    </td>


                                                                    {{-- ACTION --}}
                                                                    <td>

                                                                        <a href="{{ route(
                                        'admin.orders.show',
                                        $order->id
                                    ) }}" class="view-order-button">
                                                                            View Details
                                                                        </a>

                                                                    </td>

                                                                </tr>


                                @empty

                                    <tr>

                                        <td colspan="9" class="orders-empty">

                                            <div class="orders-empty-icon">
                                                ◉
                                            </div>

                                            <strong>
                                                No Orders Found
                                            </strong>

                                            <p>
                                                No orders match your current
                                                search or filter.
                                            </p>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                    PAGINATION
                    ================================================= --}}

                    @if($orders->hasPages())

                        <div class="order-pagination">

                            <div>

                                Showing

                                <strong>
                                    {{ $orders->firstItem() }}
                                </strong>

                                to

                                <strong>
                                    {{ $orders->lastItem() }}
                                </strong>

                                of

                                <strong>
                                    {{ $orders->total() }}
                                </strong>

                                orders

                            </div>


                            <div class="order-page-actions">


                                {{-- PREVIOUS --}}
                                @if($orders->onFirstPage())

                                    <span class="order-page-button disabled">
                                        ← Previous
                                    </span>

                                @else

                                    <a href="{{ $orders->previousPageUrl() }}" class="order-page-button">
                                        ← Previous
                                    </a>

                                @endif


                                {{-- CURRENT PAGE --}}
                                <span class="order-current-page">

                                    {{ $orders->currentPage() }}

                                    /

                                    {{ $orders->lastPage() }}

                                </span>


                                {{-- NEXT --}}
                                @if($orders->hasMorePages())

                                    <a href="{{ $orders->nextPageUrl() }}" class="order-page-button">
                                        Next →
                                    </a>

                                @else

                                    <span class="order-page-button disabled">
                                        Next →
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endif

                </div>

            </section>

        </main>

    </div>

</body>

</html>