<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>

        Lumière | Customer Details

    </title>

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/customers.css') }}">

</head>

<body>

    <div class="admin-layout">
        @include('admin.partials.sidebar')



        {{-- =====================================================

        MAIN

        ===================================================== --}}

        <main class="main-content">



            <header class="topbar">

                <div>

                    <h1>

                        Customer Details

                    </h1>

                    <p>

                        Customer #{{ $customer->id }}

                    </p>

                </div>



                <a href="{{ route('admin.customers.index') }}" class="customer-back-button">

                    ← Back to Customers

                </a>

            </header>



            <section class="dashboard-content">



                {{-- =================================================

                CUSTOMER HERO

                ================================================= --}}

                <div class="customer-detail-hero">

                    <div class="customer-detail-profile">

                        @php
                            $hasProfileImage =
                                !empty($customer->profile_image) &&
                                \Illuminate\Support\Facades\Storage::disk('public')
                                    ->exists($customer->profile_image);
                        @endphp

                        <div class="customer-large-avatar {{ $hasProfileImage ? 'has-image' : '' }}">

                            @if($hasProfileImage)

                                                        <img src="{{ asset(
                                    'storage/' .
                                    ltrim($customer->profile_image, '/')
                                ) }}" alt="{{ $customer->name }} profile photo" class="customer-large-avatar-image">

                            @else

                                                        <span class="customer-large-avatar-letter">
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



                        <div>

                            <span class="customer-detail-label">

                                CUSTOMER #{{ $customer->id }}

                            </span>

                            <h2>

                                {{ $customer->name }}

                            </h2>

                            <p>

                                {{ $customer->email }}

                            </p>

                        </div>

                    </div>



                    <span class="customer-account-badge">

                        Customer Account

                    </span>

                </div>



                {{-- =================================================

                STATISTICS

                ================================================= --}}

                <div class="customer-detail-stats">



                    <div>

                        <span>

                            Total Orders

                        </span>

                        <strong>

                            {{ $orderCount }}

                        </strong>

                    </div>



                    <div>

                        <span>

                            Paid Amount

                        </span>

                        <strong>

                            ${{ number_format(

    $totalSpent,

    2

) }}

                        </strong>

                    </div>



                    <div>

                        <span>

                            Favorites

                        </span>

                        <strong>

                            {{ $favoriteCount }}

                        </strong>

                    </div>



                    <div>

                        <span>

                            Account Role

                        </span>

                        <strong>

                            {{ ucfirst(

    $customer->role

) }}

                        </strong>

                    </div>

                </div>



                {{-- =================================================

                CUSTOMER INFORMATION

                ================================================= --}}

                <div class="customer-info-grid">



                    <div class="customer-info-card">

                        <div class="customer-info-heading">

                            <div class="customer-info-icon">

                                ♙

                            </div>



                            <div>

                                <h3>

                                    Account Information

                                </h3>

                                <p>

                                    Customer profile details

                                </p>

                            </div>

                        </div>



                        <div class="customer-info-list">



                            <div class="customer-info-row">

                                <span>

                                    Name

                                </span>

                                <strong>

                                    {{ $customer->name }}

                                </strong>

                            </div>



                            <div class="customer-info-row">

                                <span>

                                    Email

                                </span>

                                <strong>

                                    {{ $customer->email }}

                                </strong>

                            </div>



                            <div class="customer-info-row">

                                <span>

                                    Phone

                                </span>

                                <strong>

                                    {{ $customer->phone ?: '—' }}

                                </strong>

                            </div>



                            <div class="customer-info-row">

                                <span>

                                    Email Verified

                                </span>

                                <strong>

                                    {{ $customer->email_verified_at

    ? 'Yes'

    : 'No'

                                }}

                                </strong>

                            </div>



                            <div class="customer-info-row">

                                <span>

                                    Joined

                                </span>

                                <strong>

                                    @if($customer->created_at)

                                                                        {{ \Carbon\Carbon::parse(

                                            $customer->created_at

                                        )->format(

                                                'd M Y, h:i A'

                                            ) }}

                                    @else

                                        —

                                    @endif

                                </strong>

                            </div>

                        </div>

                    </div>



                    {{-- =============================================

                    SHIPPING

                    ============================================= --}}

                    <div class="customer-info-card">

                        <div class="customer-info-heading">

                            <div class="customer-info-icon">

                                ⌖

                            </div>



                            <div>

                                <h3>

                                    Shipping Information

                                </h3>

                                <p>

                                    Saved mobile app delivery details

                                </p>

                            </div>

                        </div>



                        <div class="customer-info-list">



                            <div class="customer-info-row">

                                <span>

                                    Shipping Address

                                </span>

                                <strong>

                                    {{ $customer->shipping_address

    ?: 'Not provided'

                                }}

                                </strong>

                            </div>



                            <div class="customer-info-row">

                                <span>

                                    Province / City

                                </span>

                                <strong>

                                    {{ $customer->province

    ?: 'Not provided'

                                }}

                                </strong>

                            </div>



                            <div class="customer-info-row">

                                <span>

                                    Phone

                                </span>

                                <strong>

                                    {{ $customer->phone

    ?: 'Not provided'

                                }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================

                ORDER HISTORY

                ================================================= --}}

                <div class="customer-orders-card">



                    <div class="customer-orders-heading">

                        <div>

                            <h3>

                                Customer Order History

                            </h3>

                            <p>

                                {{ $orderCount }}

                                order(s) associated with this account

                            </p>

                        </div>

                    </div>



                    <div class="customer-orders-table-wrapper">

                        <table class="customer-orders-table">

                            <thead>

                                <tr>

                                    <th>ORDER</th>

                                    <th>DATE</th>

                                    <th>PAYMENT</th>

                                    <th>PAYMENT STATUS</th>

                                    <th>ORDER STATUS</th>

                                    <th>TOTAL</th>

                                    <th>ACTION</th>

                                </tr>

                            </thead>



                            <tbody>

                                @forelse($orders as $order)

                                                                @php

                                                                    $rawStatus = strtolower(

                                                                        (string) $order->status

                                                                    );

                                                                    $displayStatus = in_array(

                                                                        $rawStatus,

                                                                        ['pending', 'processing']

                                                                    )

                                                                        ? 'Processing'

                                                                        : ucfirst($rawStatus);

                                                                    $paymentStatus = strtolower(

                                                                        (string) $order->payment_status

                                                                    );

                                                                @endphp



                                                                <tr>

                                                                    <td>

                                                                        <div class="customer-order-number">

                                                                            <strong>

                                                                                {{ $order->order_number }}

                                                                            </strong>

                                                                            <span>

                                                                                #{{ $order->id }}

                                                                            </span>

                                                                        </div>

                                                                    </td>



                                                                    <td>

                                                                        @if($order->ordered_at)

                                                                                                            {{ \Carbon\Carbon::parse(

                                                                                $order->ordered_at

                                                                            )->format('d M Y') }}

                                                                        @else

                                                                            —

                                                                        @endif

                                                                    </td>



                                                                    <td>

                                                                        {{ strtoupper(

                                        $order->payment_method

                                    ) }}

                                                                    </td>



                                                                    <td>

                                                                        <span class="

                                                                                                                                            customer-payment-badge

                                                                                                                                            {{ $paymentStatus }}

                                                                                                                                        ">

                                                                            {{ ucfirst(

                                        $paymentStatus

                                    ) }}

                                                                        </span>

                                                                    </td>



                                                                    <td>

                                                                        <span class="

                                                                                                                                            customer-status-badge

                                                                                                                                            {{ strtolower(

                                        $displayStatus

                                    ) }}

                                                                                                                                        ">

                                                                            {{ $displayStatus }}

                                                                        </span>

                                                                    </td>



                                                                    <td>

                                                                        <strong class="customer-order-total">

                                                                            ${{ number_format(

                                        (float) $order->total,

                                        2

                                    ) }}

                                                                        </strong>

                                                                    </td>



                                                                    <td>

                                                                        <a href="{{ route(

                                        'admin.orders.show',

                                        $order->id

                                    ) }}" class="customer-order-button">

                                                                            View Order

                                                                        </a>

                                                                    </td>

                                                                </tr>

                                @empty

                                    <tr>

                                        <td colspan="7" class="customers-empty">

                                            This customer has not placed any orders.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </section>

        </main>

    </div>

</body>

</html>