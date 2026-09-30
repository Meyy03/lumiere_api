<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        Lumière | Sales & Analytics Report
    </title>

    @php
        $pdfCssPath = public_path(
            'css/admin/report-pdf.css'
        );
    @endphp

    <style>
        {!! file_exists($pdfCssPath)
    ? file_get_contents($pdfCssPath)
    : ''
        !!}
    </style>
</head>

<body>

    @php

        $ownerName = 'Lymey Nguon';

        $administratorName =
            auth()->check()
            ? auth()->user()->name
            : 'Lumière Admin';


        $reportReference =
            'LUM-RPT-' .
            now()->format('Ymd-His');


        $safeRangeLabel =
            $rangeLabel
            ?? 'Last 30 Days';


        $periodStart =
            match ((string) ($selectedRange ?? '30')) {

                '7' =>
                    now()
                        ->copy()
                        ->subDays(6),

                '30' =>
                    now()
                        ->copy()
                        ->subDays(29),

                '90' =>
                    now()
                        ->copy()
                        ->subDays(89),

                default =>
                    null,
            };


        $periodText =
            $periodStart
            ? $periodStart->format('d M Y')
            . ' - '
            . now()->format('d M Y')
            : 'All Time';


        $pdfProducts =
            collect(
                $topProducts ?? []
            )->take(5);


        $pdfOrders =
            collect(
                $recentOrders ?? []
            )->take(6);


        $pdfPayments =
            collect(
                $paymentMethods ?? []
            )->take(4);


        $pdfCategories =
            collect(
                $categoryPerformance ?? []
            )->take(5);


        $pdfCustomers =
            collect(
                $topCustomers ?? []
            )->take(5);


        $safeTotalRevenue =
            (float) ($totalRevenue ?? 0);

        $safePaidOrders =
            (int) ($paidOrders ?? 0);

        $safeTotalOrders =
            (int) ($totalOrders ?? 0);

        $safeItemsSold =
            (int) ($totalItemsSold ?? 0);

        $safeAverageOrderValue =
            (float) ($averageOrderValue ?? 0);


        $paymentLabel =
            function ($method) {

                $method =
                    strtolower(
                        trim(
                            (string) $method
                        )
                    );

                return match ($method) {

                    'aba' =>
                        'ABA',

                    'acleda' =>
                        'ACLEDA',

                    'cod',
                    'cash on delivery' =>
                        'COD',

                    default =>
                        strtoupper($method),
                };
            };


        $statusLabel =
            function ($status) {

                $status =
                    strtolower(
                        trim(
                            (string) $status
                        )
                    );

                return in_array(
                    $status,
                    [
                        'pending',
                        'processing',
                    ],
                    true
                )
                    ? 'Processing'
                    : ucfirst($status);
            };


        $statusClass =
            function ($status) {

                $status =
                    strtolower(
                        trim(
                            (string) $status
                        )
                    );

                if (
                    in_array(
                        $status,
                        [
                            'pending',
                            'processing',
                        ],
                        true
                    )
                ) {
                    return 'badge-processing';
                }

                return match ($status) {

                    'delivered' =>
                        'badge-delivered',

                    'cancelled' =>
                        'badge-cancelled',

                    default =>
                        'badge-processing',
                };
            };

    @endphp


    {{-- =========================================================
    FIXED BRAND FOOTER
    Page number is added by ReportController.
    ========================================================= --}}

    <div class="pdf-footer">

        <table>
            <tr>

                <td>
                    <span class="pdf-footer-brand">
                        LUMIÈRE
                    </span>

                    &nbsp; | &nbsp;

                    Sales &amp; Analytics Report
                </td>

                <td></td>

            </tr>
        </table>

    </div>


    {{-- =========================================================
    PAGE 1
    ========================================================= --}}

    <div class="page-shell">

        {{-- HEADER --}}
        <table class="header">
            <tr>

                <td class="logo-cell">

                    @if(!empty($logoData))

                        <div class="logo-wrap">

                            <img src="{{ $logoData }}" alt="Lumière Logo" class="logo-image">

                        </div>

                    @else

                        <div class="logo-wrap logo-fallback">
                            L
                        </div>

                    @endif

                </td>


                <td class="brand-cell">

                    <div class="brand-name">
                        LUMIÈRE
                    </div>

                    <div class="brand-subtitle">
                        Cosmetic &amp; Skincare E-Commerce Management System
                    </div>

                    <div class="brand-tagline">
                        PURE INGREDIENTS • REAL RESULTS • RADIANT YOU
                    </div>

                </td>


                <td class="ref-cell">

                    <div class="ref-label">
                        REPORT REFERENCE
                    </div>

                    <div class="ref-value">
                        {{ $reportReference }}
                    </div>

                    <div class="ref-date">
                        {{ now()->format(
    'd M Y, h:i A'
) }}
                    </div>

                </td>

            </tr>
        </table>


        {{-- TITLE --}}
        <div class="report-title">

            <h1>
                Sales &amp; Analytics Report
            </h1>

            <p>
                A complete overview of Lumière sales performance
            </p>

        </div>


        {{-- META --}}
        <table class="meta">
            <tr>

                <td>
                    <div class="meta-label">
                        STORE OWNER
                    </div>

                    <div class="meta-value">
                        {{ $ownerName }}
                    </div>
                </td>


                <td>
                    <div class="meta-label">
                        REPORT PERIOD
                    </div>

                    <div class="meta-value">
                        {{ $safeRangeLabel }}
                    </div>

                    <div class="meta-note">
                        {{ $periodText }}
                    </div>
                </td>


                <td>
                    <div class="meta-label">
                        DATA SOURCE
                    </div>

                    <div class="meta-value">
                        Lumière Database
                    </div>

                    <div class="meta-note">
                        Live system records
                    </div>
                </td>


                <td>
                    <div class="meta-label">
                        REPORT STATUS
                    </div>

                    <div class="meta-value money">
                        Final
                    </div>

                    <div class="meta-note">
                        System Generated
                    </div>
                </td>

            </tr>
        </table>


        {{-- KPIS --}}
        <table class="kpis">
            <tr>

                <td>
                    <div class="kpi">
                        <div class="kpi-label">
                            Total Revenue
                        </div>

                        <div class="kpi-value">
                            ${{ number_format(
    $safeTotalRevenue,
    2
) }}
                        </div>

                        <div class="kpi-note">
                            Paid non-cancelled revenue
                        </div>
                    </div>
                </td>


                <td>
                    <div class="kpi">
                        <div class="kpi-label">
                            Paid Orders
                        </div>

                        <div class="kpi-value">
                            {{ number_format(
    $safePaidOrders
) }}
                        </div>

                        <div class="kpi-note">
                            {{ number_format(
    $safeTotalOrders
) }}
                            total order(s)
                        </div>
                    </div>
                </td>


                <td>
                    <div class="kpi">
                        <div class="kpi-label">
                            Items Sold
                        </div>

                        <div class="kpi-value">
                            {{ number_format(
    $safeItemsSold
) }}
                        </div>

                        <div class="kpi-note">
                            Units from valid paid sales
                        </div>
                    </div>
                </td>


                <td>
                    <div class="kpi">
                        <div class="kpi-label">
                            Avg. Order Value
                        </div>

                        <div class="kpi-value">
                            ${{ number_format(
    $safeAverageOrderValue,
    2
) }}
                        </div>

                        <div class="kpi-note">
                            Average paid order
                        </div>
                    </div>
                </td>

            </tr>
        </table>


        {{-- ORDER STATUS --}}
        <table class="section-head">
            <tr>
                <td class="section-title">
                    Order Status Summary
                </td>

                <td class="section-note">
                    {{ $safeRangeLabel }}
                </td>
            </tr>
        </table>


        <table class="status-row">
            <tr>

                <td>
                    <div class="status-card">

                        <div class="status-name">
                            Processing
                        </div>

                        <div class="status-number processing">
                            {{ number_format(
    $processingOrders ?? 0
) }}
                        </div>

                    </div>
                </td>


                <td>
                    <div class="status-card">

                        <div class="status-name">
                            Delivered
                        </div>

                        <div class="status-number delivered">
                            {{ number_format(
    $deliveredOrders ?? 0
) }}
                        </div>

                    </div>
                </td>


                <td>
                    <div class="status-card">

                        <div class="status-name">
                            Cancelled
                        </div>

                        <div class="status-number cancelled">
                            {{ number_format(
    $cancelledOrders ?? 0
) }}
                        </div>

                    </div>
                </td>

            </tr>
        </table>


        {{-- TOP PRODUCTS --}}
        <table class="section-head">
            <tr>

                <td class="section-title">
                    Top Selling Products
                </td>

                <td class="section-note">
                    Ranked by paid quantity
                </td>

            </tr>
        </table>


        <table class="data-table">

            <thead>
                <tr>

                    <th style="width: 7%;">
                        #
                    </th>

                    <th style="width: 31%;">
                        PRODUCT
                    </th>

                    <th style="width: 20%;">
                        CATEGORY
                    </th>

                    <th style="width: 12%;" class="center">
                        QTY
                    </th>

                    <th style="width: 14%;" class="right">
                        AVG.
                    </th>

                    <th style="width: 16%;" class="right">
                        REVENUE
                    </th>

                </tr>
            </thead>


            <tbody>

                @forelse(
                                    $pdfProducts
                                    as $index => $product
                                )

                                @php

                                    $qty =
                                        (int) 
                                        ($product->quantity_sold ?? 0);

                                    $sales =
                                        (float) 
                                        ($product->sales_amount ?? 0);

                                    $averagePrice =
                                        $qty > 0
                                        ? $sales / $qty
                                        : 0;

                                @endphp


                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>

                                        <div class="primary">
                                            {{
                        $product->product_name
                        ??
                        'Unknown Product'
                                        }}
                                        </div>

                                        <div class="secondary">
                                            Product
                                            #{{ $product->product_id ?? '—' }}
                                        </div>

                                    </td>


                                    <td>

                                        <span class="badge badge-pink">
                                            {{
                        $product->category_name
                        ??
                        'General'
                                        }}
                                        </span>

                                    </td>


                                    <td class="center">
                                        {{ number_format(
                        $qty
                    ) }}
                                    </td>


                                    <td class="right">
                                        ${{ number_format(
                        $averagePrice,
                        2
                    ) }}
                                    </td>


                                    <td class="right">
                                        <span class="money">
                                            ${{ number_format(
                        $sales,
                        2
                    ) }}
                                        </span>
                                    </td>

                                </tr>

                @empty

                    <tr>
                        <td colspan="6" class="center">
                            No product sales available.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>


        {{-- PAYMENT + CATEGORY --}}
        <table class="two-col">
            <tr>

                <td class="col-left">

                    <div class="panel">

                        <div class="panel-title">
                            Payment Methods
                        </div>

                        <div class="panel-subtitle">
                            Paid revenue distribution
                        </div>


                        <table class="mini-table">

                            <thead>
                                <tr>

                                    <th style="width: 42%;">
                                        METHOD
                                    </th>

                                    <th style="width: 20%;" class="center">
                                        ORDERS
                                    </th>

                                    <th style="width: 38%;" class="right">
                                        REVENUE
                                    </th>

                                </tr>
                            </thead>


                            <tbody>

                                @forelse(
                                                                    $pdfPayments
                                                                    as $payment
                                                                )

                                                                @php

                                                                    $paymentRevenue =
                                                                        (float) 
                                                                        ($payment->revenue ?? 0);

                                                                    $paymentPercent =
                                                                        $safeTotalRevenue > 0
                                                                        ? round(
                                                                            (
                                                                                $paymentRevenue
                                                                                /
                                                                                $safeTotalRevenue
                                                                            )
                                                                            * 100
                                                                        )
                                                                        : 0;

                                                                @endphp


                                                                <tr>

                                                                    <td>

                                                                        <strong>
                                                                            {{
                                        $paymentLabel(
                                            $payment->payment_method
                                            ??
                                            ''
                                        )
                                                                        }}
                                                                        </strong>

                                                                        <div class="progress">

                                                                            <div class="progress-bar" style="width: {{ min(
                                        100,
                                        $paymentPercent
                                    ) }}%;"></div>

                                                                        </div>

                                                                    </td>


                                                                    <td class="center">
                                                                        {{ number_format(
                                        $payment->order_count
                                        ??
                                        0
                                    ) }}
                                                                    </td>


                                                                    <td class="right">

                                                                        <strong>
                                                                            ${{ number_format(
                                        $paymentRevenue,
                                        2
                                    ) }}
                                                                        </strong>

                                                                        <div class="secondary">
                                                                            {{ $paymentPercent }}%
                                                                        </div>

                                                                    </td>

                                                                </tr>

                                @empty

                                    <tr>
                                        <td colspan="3" class="center">
                                            No payment data.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>
                        </table>

                    </div>

                </td>


                <td class="col-right">

                    <div class="panel">

                        <div class="panel-title">
                            Sales by Category
                        </div>

                        <div class="panel-subtitle">
                            Paid units and revenue
                        </div>


                        <table class="mini-table">

                            <thead>
                                <tr>

                                    <th style="width: 50%;">
                                        CATEGORY
                                    </th>

                                    <th style="width: 18%;" class="center">
                                        UNITS
                                    </th>

                                    <th style="width: 32%;" class="right">
                                        REVENUE
                                    </th>

                                </tr>
                            </thead>


                            <tbody>

                                @forelse(
                                                                    $pdfCategories
                                                                    as $category
                                                                )

                                                                <tr>

                                                                    <td>
                                                                        <strong>
                                                                            {{
                                        $category->category_name
                                        ??
                                        'Uncategorized'
                                                                        }}
                                                                        </strong>
                                                                    </td>


                                                                    <td class="center">
                                                                        {{ number_format(
                                        $category->units_sold
                                        ??
                                        0
                                    ) }}
                                                                    </td>


                                                                    <td class="right">
                                                                        <span class="money">
                                                                            ${{ number_format(
                                        (float) 
                                        ($category->revenue ?? 0),
                                        2
                                    ) }}
                                                                        </span>
                                                                    </td>

                                                                </tr>

                                @empty

                                    <tr>
                                        <td colspan="3" class="center">
                                            No category data.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>
                        </table>

                    </div>

                </td>

            </tr>
        </table>


        <div class="summary-note">

            <strong>
                Report Summary:
            </strong>

            This report summarizes paid sales, order activity,
            product performance, payment distribution,
            and customer purchasing behavior for the selected period.

        </div>

    </div>


    {{-- =========================================================
    PAGE 2
    ========================================================= --}}

    <div class="page-break"></div>

    <div class="page-shell">

        {{-- HEADER --}}
        <table class="header">
            <tr>

                <td class="logo-cell">

                    @if(!empty($logoData))

                        <div class="logo-wrap">

                            <img src="{{ $logoData }}" alt="Lumière Logo" class="logo-image">

                        </div>

                    @else

                        <div class="logo-wrap logo-fallback">
                            L
                        </div>

                    @endif

                </td>


                <td class="brand-cell">

                    <div class="brand-name">
                        LUMIÈRE
                    </div>

                    <div class="brand-subtitle">
                        Cosmetic &amp; Skincare E-Commerce Management System
                    </div>

                </td>


                <td class="ref-cell">

                    <div class="ref-label">
                        REPORT DETAILS
                    </div>

                    <div class="ref-value">
                        {{ $safeRangeLabel }}
                    </div>

                </td>

            </tr>
        </table>


        <div class="report-title">

            <h1>
                Report Details
            </h1>

            <p>
                Recent transactions and financial summary
            </p>

        </div>


        {{-- TRANSACTIONS --}}
        <table class="section-head">
            <tr>

                <td class="section-title">
                    Recent Transactions
                </td>

                <td class="section-note">
                    Latest {{ $pdfOrders->count() }} record(s)
                </td>

            </tr>
        </table>


        <table class="data-table">

            <thead>
                <tr>

                    <th style="width: 5%;">
                        #
                    </th>

                    <th style="width: 25%;">
                        ORDER
                    </th>

                    <th style="width: 16%;">
                        DATE
                    </th>

                    <th style="width: 20%;">
                        CUSTOMER
                    </th>

                    <th style="width: 12%;">
                        PAYMENT
                    </th>

                    <th style="width: 14%;">
                        STATUS
                    </th>

                    <th style="width: 8%;" class="right">
                        TOTAL
                    </th>

                </tr>
            </thead>


            <tbody>

                @forelse(
                                    $pdfOrders
                                    as $index => $order
                                )

                                @php

                                    $orderDate =
                                        $order->ordered_at
                                        ??
                                        $order->created_at
                                        ??
                                        null;

                                    $rawPaymentStatus =
                                        strtolower(
                                            (string) 
                                            ($order->payment_status ?? '')
                                        );

                                    $rawOrderStatus =
                                        strtolower(
                                            (string) 
                                            ($order->status ?? '')
                                        );

                                @endphp


                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>
                                        <div class="primary">
                                            {{
                        $order->order_number
                        ??
                        (
                            'Order #'
                            .
                            ($order->id ?? '—')
                        )
                                        }}
                                        </div>
                                    </td>


                                    <td>

                                        @if($orderDate)

                                                        {{
                                            \Carbon\Carbon::parse(
                                                $orderDate
                                            )->format(
                                                    'd M Y'
                                                )
                                                        }}

                                                        <div class="secondary">
                                                            {{
                                            \Carbon\Carbon::parse(
                                                $orderDate
                                            )->format(
                                                    'h:i A'
                                                )
                                                            }}
                                                        </div>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        <div class="primary">
                                            {{
                        $order->customer_name
                        ??
                        'Customer'
                                        }}
                                        </div>

                                        <div class="secondary">
                                            {{
                        $order->customer_email
                        ??
                        '—'
                                        }}
                                        </div>

                                    </td>


                                    <td>

                                        <span class="badge badge-pink">
                                            {{
                        $paymentLabel(
                            $order->payment_method
                            ??
                            ''
                        )
                                        }}
                                        </span>

                                    </td>


                                    <td>

                                        <span class="
                                        badge
                                        {{
                        $statusClass(
                            $rawOrderStatus
                        )
                                        }}
                                    ">
                                            {{
                        $statusLabel(
                            $rawOrderStatus
                        )
                                        }}
                                        </span>

                                        <div class="secondary">
                                            Payment:
                                            {{
                        ucfirst(
                            $rawPaymentStatus
                        )
                                        }}
                                        </div>

                                    </td>


                                    <td class="right">

                                        <span class="money">
                                            ${{ number_format(
                        (float) 
                        ($order->total ?? 0),
                        2
                    ) }}
                                        </span>

                                    </td>

                                </tr>

                @empty

                    <tr>
                        <td colspan="7" class="center">
                            No transactions available.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>


        {{-- STATUS SUMMARY --}}
        <table class="section-head">
            <tr>

                <td class="section-title">
                    Order Status Summary
                </td>

                <td class="section-note">
                    {{ $safeRangeLabel }}
                </td>

            </tr>
        </table>


        <table class="status-row">
            <tr>

                <td>
                    <div class="status-card">

                        <div class="status-name">
                            Processing
                        </div>

                        <div class="status-number processing">
                            {{ number_format(
    $processingOrders ?? 0
) }}
                        </div>

                    </div>
                </td>


                <td>
                    <div class="status-card">

                        <div class="status-name">
                            Delivered
                        </div>

                        <div class="status-number delivered">
                            {{ number_format(
    $deliveredOrders ?? 0
) }}
                        </div>

                    </div>
                </td>


                <td>
                    <div class="status-card">

                        <div class="status-name">
                            Cancelled
                        </div>

                        <div class="status-number cancelled">
                            {{ number_format(
    $cancelledOrders ?? 0
) }}
                        </div>

                    </div>
                </td>

            </tr>
        </table>


        {{-- CUSTOMERS + FINANCE --}}
        <table class="two-col">
            <tr>

                <td class="col-left">

                    <div class="panel">

                        <div class="panel-title">
                            Top Customers
                        </div>

                        <div class="panel-subtitle">
                            Customer activity and valid spending
                        </div>


                        <table class="mini-table">

                            <thead>
                                <tr>

                                    <th style="width: 55%;">
                                        CUSTOMER
                                    </th>

                                    <th style="width: 17%;" class="center">
                                        ORDERS
                                    </th>

                                    <th style="width: 28%;" class="right">
                                        SPENT
                                    </th>

                                </tr>
                            </thead>


                            <tbody>

                                @forelse(
                                                                    $pdfCustomers
                                                                    as $customer
                                                                )

                                                                <tr>

                                                                    <td>
                                                                        <div class="primary">
                                                                            {{
                                        $customer->name
                                        ??
                                        'Customer'
                                                                        }}
                                                                        </div>

                                                                        <div class="secondary">
                                                                            {{
                                        $customer->email
                                        ??
                                        '—'
                                                                        }}
                                                                        </div>
                                                                    </td>


                                                                    <td class="center">
                                                                        {{ number_format(
                                        $customer->order_count
                                        ??
                                        0
                                    ) }}
                                                                    </td>


                                                                    <td class="right">
                                                                        <span class="money">
                                                                            ${{ number_format(
                                        (float) 
                                        ($customer->total_spent ?? 0),
                                        2
                                    ) }}
                                                                        </span>
                                                                    </td>

                                                                </tr>

                                @empty

                                    <tr>
                                        <td colspan="3" class="center">
                                            No customer data.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>
                        </table>

                    </div>

                </td>


                <td class="col-right">

                    <div class="panel">

                        <div class="panel-title">
                            Financial Summary
                        </div>

                        <div class="panel-subtitle">
                            Paid non-cancelled sales
                        </div>


                        <table class="finance-table">

                            <tr>
                                <td>
                                    Gross Paid Revenue
                                </td>

                                <td class="right">
                                    ${{ number_format(
    $safeTotalRevenue,
    2
) }}
                                </td>
                            </tr>


                            <tr>
                                <td>
                                    Processing Fees
                                </td>

                                <td class="right">
                                    $0.00
                                </td>
                            </tr>


                            <tr>
                                <td>
                                    Adjustments
                                </td>

                                <td class="right">
                                    $0.00
                                </td>
                            </tr>


                            <tr class="net-row">
                                <td>
                                    NET REPORTED REVENUE
                                </td>

                                <td class="right">
                                    ${{ number_format(
    $safeTotalRevenue,
    2
) }}
                                </td>
                            </tr>

                        </table>

                    </div>

                </td>

            </tr>
        </table>


        {{-- SIGNATURES --}}
        <table class="signature-table">
            <tr>

                <td class="signature-cell">

                    <div class="signature-line"></div>

                    <div class="signature-label">
                        Prepared By
                    </div>

                    <div class="signature-script">
                        Lumière Admin
                    </div>

                    <div class="signature-name">
                        {{ $administratorName }}
                    </div>

                    <div class="signature-role">
                        Lumière System Administrator
                    </div>

                </td>


                <td class="signature-gap"></td>


                <td class="signature-cell">

                    <div class="signature-line"></div>

                    <div class="signature-label">
                        Approved By
                    </div>

                    <div class="signature-script">
                        {{ $ownerName }}
                    </div>

                    <div class="signature-name">
                        {{ $ownerName }}
                    </div>

                    <div class="signature-role">
                        Store Owner
                    </div>

                </td>

            </tr>
        </table>

    </div>

</body>

</html>