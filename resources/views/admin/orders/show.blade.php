<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>

        Lumière | Order Details

    </title>

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/orders.css') }}">

</head>

<body>

    <div class="admin-layout">
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

                        Order Details

                    </h1>

                    <p>

                        {{ $order->order_number }}

                    </p>

                </div>



                <a href="{{ route('admin.orders.index') }}" class="order-back-button">

                    ← Back to Orders

                </a>

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



                @if($errors->any())

                    <div class="order-alert order-alert-error">

                        <span>

                            !

                        </span>

                        <div>

                            {{ $errors->first() }}

                        </div>

                    </div>

                @endif



                @php

                    $rawStatus = strtolower(

                        (string) $order->status

                    );

                    $displayStatus =

                        in_array(

                            $rawStatus,

                            ['pending', 'processing']

                        )

                        ? 'Processing'

                        : ucfirst($rawStatus);

                    $paymentStatus = strtolower(

                        (string) $order->payment_status

                    );

                @endphp



                {{-- =================================================

                ORDER HEADER

                ================================================= --}}

                <div class="order-details-card">

                    <div class="order-details-heading">

                        <div>

                            <span class="order-details-label">

                                ORDER #{{ $order->id }}

                            </span>

                            <h2>

                                {{ $order->order_number }}

                            </h2>

                            <p>

                                Placed

                                @if($order->ordered_at)

                                                                {{ \Carbon\Carbon::parse(

                                        $order->ordered_at

                                    )->format(

                                            'd M Y, h:i A'

                                        ) }}

                                @else

                                    —

                                @endif

                            </p>

                        </div>



                        <div class="order-details-badges">

                            <span class="

                                payment-status

                                {{ $paymentStatus }}

                            ">

                                {{ ucfirst($paymentStatus) }}

                            </span>



                            <span class="

                                order-status

                                {{ strtolower(

    $displayStatus

) }}

                            ">

                                {{ $displayStatus }}

                            </span>

                        </div>

                    </div>



                    {{-- =================================================

                    SUMMARY

                    ================================================= --}}

                    <div class="order-detail-summary">

                        <div class="order-detail-box">

                            <span>

                                Total

                            </span>

                            <strong>

                                ${{ number_format(

    (float) $order->total,

    2

) }}

                            </strong>

                        </div>



                        <div class="order-detail-box">

                            <span>

                                Items

                            </span>

                            <strong>

                                {{ $itemCount }}

                            </strong>

                        </div>



                        <div class="order-detail-box">

                            <span>

                                Payment Method

                            </span>

                            <strong>

                                {{ strtoupper(

    (string) $order->payment_method

) }}

                            </strong>

                        </div>



                        <div class="order-detail-box">

                            <span>

                                Payment Status

                            </span>

                            <strong>

                                {{ ucfirst(

    (string) $order->payment_status

) }}

                            </strong>

                        </div>

                    </div>

                </div>



                {{-- =================================================

                CUSTOMER + DELIVERY

                ================================================= --}}

                <div class="order-information-grid">
                    {{-- CUSTOMER INFORMATION --}}
                    <div class="order-info-card">

                        <div class="order-info-title">

                            <div class="order-info-icon">

                                ♙

                            </div>

                            <div>

                                <h3>

                                    Customer Information

                                </h3>

                                <p>

                                    Customer account and contact details

                                </p>

                            </div>

                        </div>



                        <div class="order-info-list">

                            <div class="order-info-row">

                                <span>

                                    Name

                                </span>

                                <strong>

                                    {{ $order->customer_name }}

                                </strong>

                            </div>



                            <div class="order-info-row">

                                <span>

                                    Email

                                </span>

                                <strong>

                                    {{ $order->customer_email ?? '—' }}

                                </strong>

                            </div>



                            <div class="order-info-row">

                                <span>

                                    Customer Phone

                                </span>

                                <strong>

                                    {{ $order->customer_phone }}

                                </strong>

                            </div>



                            <div class="order-info-row">

                                <span>

                                    Telegram Contact

                                </span>

                                <strong>

                                    {{ $order->contact_via_telegram

    ? 'Yes'

    : 'No'

                                }}

                                </strong>

                            </div>

                        </div>

                    </div>
                    {{-- DELIVERY INFORMATION --}}
                    <div class="order-info-card">

                        <div class="order-info-title">

                            <div class="order-info-icon">

                                ⌖

                            </div>

                            <div>

                                <h3>

                                    Delivery Information

                                </h3>

                                <p>

                                    Shipping and delivery destination

                                </p>

                            </div>

                        </div>



                        <div class="order-info-list">

                            <div class="order-info-row">

                                <span>

                                    Delivery Phone

                                </span>

                                <strong>

                                    {{ $order->delivery_phone }}

                                </strong>

                            </div>



                            <div class="order-info-row">

                                <span>

                                    Address

                                </span>

                                <strong>

                                    {{ $order->delivery_address }}

                                </strong>

                            </div>



                            <div class="order-info-row">

                                <span>

                                    Province / City

                                </span>

                                <strong>

                                    {{ $order->delivery_province }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================

                PURCHASED ITEMS

                ================================================= --}}

                <div class="order-info-card order-products-card">

                    <div class="order-info-title">

                        <div class="order-info-icon">

                            ♡

                        </div>

                        <div>

                            <h3>

                                Purchased Items

                            </h3>

                            <p>

                                Products included in this order

                            </p>

                        </div>

                    </div>



                    <div class="order-products-table-wrapper">

                        <table class="order-products-table">

                            <thead>

                                <tr>

                                    <th>

                                        PRODUCT

                                    </th>

                                    <th>

                                        SIZE

                                    </th>

                                    <th>

                                        UNIT PRICE

                                    </th>

                                    <th>

                                        QUANTITY

                                    </th>

                                    <th>

                                        LINE TOTAL

                                    </th>

                                </tr>

                            </thead>



                            <tbody>

                                @forelse($items as $item)

                                                                <tr>

                                                                    <td>

                                                                        <div class="order-product-name">

                                                                            @php

                                                                                $productImage = trim(

                                                                                    (string) ($item->product_image ?? '')

                                                                                );

                                                                                $productImageUrl = null;

                                                                                if ($productImage !== '') {


                                                                                    if (

                                                                                        str_starts_with(

                                                                                            $productImage,

                                                                                            'products/'

                                                                                        )

                                                                                    ) {

                                                                                        $productImageUrl = route(

                                                                                            'product.image',

                                                                                            [

                                                                                                'filename' =>

                                                                                                    basename($productImage)

                                                                                            ]

                                                                                        );


                                                                                    } elseif (

                                                                                        str_starts_with(

                                                                                            $productImage,

                                                                                            'storage/products/'

                                                                                        )

                                                                                    ) {

                                                                                        $productImageUrl = route(

                                                                                            'product.image',

                                                                                            [

                                                                                                'filename' =>

                                                                                                    basename($productImage)

                                                                                            ]

                                                                                        );


                                                                                    } elseif (

                                                                                        str_starts_with(

                                                                                            $productImage,

                                                                                            'assets/'

                                                                                        )

                                                                                    ) {

                                                                                        $productImageUrl = asset(

                                                                                            $productImage

                                                                                        );


                                                                                    } elseif (

                                                                                        str_starts_with(

                                                                                            $productImage,

                                                                                            'http://'

                                                                                        )

                                                                                        ||

                                                                                        str_starts_with(

                                                                                            $productImage,

                                                                                            'https://'

                                                                                        )

                                                                                    ) {

                                                                                        $productImageUrl =

                                                                                            $productImage;

                                                                                    }

                                                                                }

                                                                            @endphp



                                                                            <div class="order-product-avatar">

                                                                                @if($productImageUrl)

                                                                                                                            <img src="{{ $productImageUrl }}" alt="{{ $item->product_name }}"
                                                                                                                                class="order-product-image" onerror="

                                                                                                                                                                        this.style.display='none';

                                                                                                                                                                        this.nextElementSibling.style.display='flex';

                                                                                                                                                                    ">

                                                                                                                            <span class="order-product-image-fallback" style="display: none;">

                                                                                                                                {{ strtoupper(

                                                                                        substr(

                                                                                            $item->product_name,

                                                                                            0,

                                                                                            1

                                                                                        )

                                                                                    ) }}

                                                                                                                            </span>

                                                                                @else

                                                                                                                            <span class="order-product-image-fallback">

                                                                                                                                {{ strtoupper(

                                                                                        substr(

                                                                                            $item->product_name,

                                                                                            0,

                                                                                            1

                                                                                        )

                                                                                    ) }}

                                                                                                                            </span>

                                                                                @endif

                                                                            </div>



                                                                            <div>

                                                                                <strong>

                                                                                    {{ $item->product_name }}

                                                                                </strong>

                                                                                <span>

                                                                                    Product #{{ $item->product_id }}

                                                                                </span>

                                                                            </div>

                                                                        </div>

                                                                    </td>



                                                                    <td>

                                                                        {{ $item->selected_size }}

                                                                    </td>



                                                                    <td>

                                                                        ${{ number_format(

                                        (float) $item->unit_price,

                                        2

                                    ) }}

                                                                    </td>



                                                                    <td>

                                                                        {{ $item->quantity }}

                                                                    </td>



                                                                    <td>

                                                                        <strong class="order-line-total">

                                                                            ${{ number_format(

                                        (float) $item->line_total,

                                        2

                                    ) }}

                                                                        </strong>

                                                                    </td>

                                                                </tr>

                                @empty

                                    <tr>

                                        <td colspan="5" class="orders-empty">

                                            No purchased items found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>



                {{-- =================================================

                PAYMENT SUMMARY + STATUS UPDATE

                ================================================= --}}

                <div class="order-information-grid">
                    {{-- PAYMENT SUMMARY --}}
                    <div class="order-info-card">

                        <div class="order-info-title">

                            <div class="order-info-icon">

                                $

                            </div>

                            <div>

                                <h3>

                                    Payment Summary

                                </h3>

                                <p>

                                    Order financial information

                                </p>

                            </div>

                        </div>



                        <div class="payment-summary-list">

                            <div>

                                <span>

                                    Subtotal

                                </span>

                                <strong>

                                    ${{ number_format(

    (float) $order->subtotal,

    2

) }}

                                </strong>

                            </div>



                            <div>

                                <span>

                                    Shipping

                                </span>

                                <strong>

                                    ${{ number_format(

    (float) $order->shipping_fee,

    2

) }}

                                </strong>

                            </div>



                            <div>

                                <span>

                                    Discount

                                </span>

                                <strong>

                                    -${{ number_format(

    (float) $order->discount,

    2

) }}

                                </strong>

                            </div>



                            <div class="payment-summary-total">

                                <span>

                                    Total

                                </span>

                                <strong>

                                    ${{ number_format(

    (float) $order->total,

    2

) }}

                                </strong>

                            </div>

                        </div>

                    </div>
                    {{-- STATUS MANAGEMENT --}}
<div class="order-info-card">

    <div class="order-info-title">

        <div class="order-info-icon">
            ✓
        </div>

        <div>
            <h3>
                Order Status
            </h3>

            <p>
                Update fulfilment status
            </p>
        </div>

    </div>


    <div class="current-status-box">

        <span>
            Current Status
        </span>

        <strong>
            {{ $displayStatus }}
        </strong>

    </div>


    @if(
        !in_array(
            $rawStatus,
            ['delivered', 'cancelled']
        )
    )

        <form
            method="POST"
            action="{{ route(
                'admin.orders.status.update',
                $order->id
            ) }}"
            class="order-status-form"
        >

            @csrf

            @method('PATCH')


            <label for="status">
                New Order Status
            </label>


            <select
                name="status"
                id="status"
                required
            >

                <option
                    value="processing"
                    @selected(
                        $rawStatus === 'pending'
                        ||
                        $rawStatus === 'processing'
                    )
                >
                    Processing
                </option>


                <option value="delivered">
                    Delivered
                </option>


                <option value="cancelled">
                    Cancelled
                </option>

            </select>


            <button
                type="submit"
                class="update-status-button"
            >
                ✓ Update Status
            </button>

        </form>

    @else

        <div class="order-status-note">

            @if($rawStatus === 'delivered')

                This order has been delivered and is complete.

            @elseif($rawStatus === 'cancelled')

                This order has been cancelled and cannot be reopened.

            @endif

        </div>

    @endif


    <div class="order-status-note">

        @if(
            strtolower(
                (string) $order->payment_method
            ) === 'cod'
        )

            COD payment stays pending until the order is delivered.

        @else

            Online payment status is recorded separately from fulfilment status.

        @endif

    </div>

</div>

                </div>

            </section>

        </main>

    </div>

</body>

</html>