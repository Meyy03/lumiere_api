<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Lumière | Reports
    </title>


    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/reports.css') }}">

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
            TOPBAR
            ================================================= --}}

            <header class="topbar report-topbar">


                {{-- LEFT SIDE --}}

                <div class="report-topbar-title">

                    <h1>
                        Reports & Analytics
                    </h1>

                    <p>
                        View and analyze Lumière sales performance.
                    </p>

                </div>



                {{-- RIGHT SIDE --}}

                <div class="report-topbar-actions">


                    {{-- =========================================
                    DATE RANGE
                    ========================================= --}}

                    <form method="GET" action="{{ route('admin.reports.index') }}" class="range-form">

                        <div class="range-select-wrapper">


                            {{-- CALENDAR ICON --}}

                            <svg class="range-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">

                                <rect x="3" y="5" width="18" height="16" rx="2"></rect>

                                <path d="M16 3v4"></path>
                                <path d="M8 3v4"></path>
                                <path d="M3 10h18"></path>

                            </svg>


                            <select name="range" class="range-select" onchange="this.form.submit()"
                                aria-label="Report date range">

                                <option value="7" {{ $selectedRange === '7' ? 'selected' : '' }}>
                                    Last 7 Days
                                </option>


                                <option value="30" {{ $selectedRange === '30' ? 'selected' : '' }}>
                                    Last 30 Days
                                </option>


                                <option value="90" {{ $selectedRange === '90' ? 'selected' : '' }}>
                                    Last 90 Days
                                </option>


                                <option value="all" {{ $selectedRange === 'all' ? 'selected' : '' }}>
                                    All Time
                                </option>

                            </select>


                            {{-- CHEVRON --}}

                            <svg class="range-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">

                                <path d="m6 9 6 6 6-6"></path>

                            </svg>


                        </div>

                    </form>



                    {{-- =========================================
                    EXPORT PDF
                    ========================================= --}}

                    <a href="{{ route(
    'admin.reports.pdf',
    [
        'range' => $selectedRange
    ]
) }}" class="export-report-button">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">

                            <path d="M12 3v12"></path>

                            <path d="m7 10 5 5 5-5"></path>

                            <path d="M5 21h14"></path>

                        </svg>


                        <span>
                            Export PDF
                        </span>

                    </a>



                    {{-- =========================================
                    REPORT DATE
                    PINK DASHBOARD-STYLE PILL
                    ========================================= --}}

                    <div class="report-date-pill">


                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">

                            <circle cx="12" cy="12" r="9"></circle>

                            <path d="M12 7v5l3 2"></path>

                        </svg>


                        <span>
                            {{ now()->format('d M Y') }}
                        </span>


                    </div>


                </div>


            </header>



            {{-- =================================================
            PAGE CONTENT
            ================================================= --}}

            <section class="dashboard-content reports-dashboard">


                {{-- =================================================
                KPI CARDS
                ================================================= --}}

                <div class="report-kpi-grid">


                    {{-- TOTAL REVENUE --}}

                    <article class="report-kpi-card">

                        <div class="kpi-icon sales">

                            <span>
                                $
                            </span>

                        </div>


                        <div>

                            <span class="kpi-label">
                                Total Revenue
                            </span>

                            <strong>

                                ${{
    number_format(
        (float) $totalRevenue,
        2
    )
                            }}

                            </strong>

                            <small>
                                Paid revenue
                            </small>

                        </div>

                    </article>



                    {{-- PAID ORDERS --}}

                    <article class="report-kpi-card">

                        <div class="kpi-icon orders">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">

                                <rect x="5" y="3" width="14" height="18" rx="2"></rect>

                                <path d="M9 7h6"></path>
                                <path d="M9 11h6"></path>
                                <path d="M9 15h4"></path>

                            </svg>

                        </div>


                        <div>

                            <span class="kpi-label">
                                Paid Orders
                            </span>

                            <strong>
                                {{ $paidOrders }}
                            </strong>

                            <small>

                                {{ $totalOrders }}
                                total orders

                            </small>

                        </div>

                    </article>



                    {{-- ITEMS SOLD --}}

                    <article class="report-kpi-card">

                        <div class="kpi-icon items">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">

                                <path d="m3 7 9-4 9 4-9 5-9-5Z"></path>

                                <path d="M3 7v10l9 4 9-4V7"></path>

                            </svg>

                        </div>


                        <div>

                            <span class="kpi-label">
                                Items Sold
                            </span>

                            <strong>
                                {{ $totalItemsSold }}
                            </strong>

                            <small>
                                Units in paid orders
                            </small>

                        </div>

                    </article>



                    {{-- AVERAGE ORDER VALUE --}}

                    <article class="report-kpi-card">

                        <div class="kpi-icon average">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">

                                <circle cx="9" cy="20" r="1"></circle>

                                <circle cx="18" cy="20" r="1"></circle>

                                <path d="M3 4h2l2 10h11l2-7H7"></path>

                            </svg>

                        </div>


                        <div>

                            <span class="kpi-label">
                                Avg. Order Value
                            </span>

                            <strong>

                                ${{
    number_format(
        (float) $averageOrderValue,
        2
    )
                            }}

                            </strong>

                            <small>
                                Average paid order
                            </small>

                        </div>

                    </article>


                </div>



                {{-- =================================================
                SALES OVERVIEW + PAYMENT METHODS
                ================================================= --}}

                <div class="report-main-grid">


                    {{-- =================================================
                    SALES OVERVIEW
                    ================================================= --}}

                    <section class="report-panel sales-overview-panel">


                        <div class="panel-heading">

                            <div>

                                <h3>
                                    Sales Overview
                                </h3>

                                <p>
                                    Paid revenue over time
                                </p>

                            </div>


                            <span class="period-chip">
                                {{ $rangeLabel }}
                            </span>

                        </div>



                        @php

                            $trendCount =
                                $salesTrend->count();


                            $trendMax =
                                max(
                                    1,
                                    (float) 
                                    (
                                        $salesTrend->max(
                                            'revenue'
                                        )
                                        ??
                                        0
                                    )
                                );


                            $trendPoints = [];

                            $areaPoints = [];


                            foreach (
                                $salesTrend
                                as $index => $trend
                            ) {

                                $x =
                                    $trendCount <= 1
                                    ? 500
                                    : 50
                                    +
                                    (
                                        $index
                                        /
                                        ($trendCount - 1)
                                    )
                                    * 900;


                                $y =
                                    215
                                    -
                                    (
                                        (
                                            (float) 
                                            $trend->revenue
                                            /
                                            $trendMax
                                        )
                                        * 165
                                    );


                                $trendPoints[] =
                                    round(
                                        $x,
                                        2
                                    )
                                    . ','
                                    .
                                    round(
                                        $y,
                                        2
                                    );
                            }


                            if ($trendCount === 1) {

                                $singleY =
                                    explode(
                                        ',',
                                        $trendPoints[0]
                                    )[1];


                                $trendPoints = [
                                    '50,' . $singleY,
                                    '950,' . $singleY,
                                ];
                            }


                            if (!empty($trendPoints)) {

                                $areaPoints[] =
                                    '50,215';


                                foreach (
                                    $trendPoints
                                    as $point
                                ) {

                                    $areaPoints[] =
                                        $point;
                                }


                                $areaPoints[] =
                                    '950,215';
                            }


                            $trendPolyline =
                                implode(
                                    ' ',
                                    $trendPoints
                                );


                            $trendArea =
                                implode(
                                    ' ',
                                    $areaPoints
                                );


                            $labelIndexes = [];


                            if ($trendCount > 0) {

                                $labelIndexes = [
                                    0,

                                    (int) 
                                    round(
                                        ($trendCount - 1)
                                        * 0.25
                                    ),

                                    (int) 
                                    round(
                                        ($trendCount - 1)
                                        * 0.50
                                    ),

                                    (int) 
                                    round(
                                        ($trendCount - 1)
                                        * 0.75
                                    ),

                                    $trendCount - 1,
                                ];


                                $labelIndexes =
                                    array_values(
                                        array_unique(
                                            $labelIndexes
                                        )
                                    );
                            }

                        @endphp



                        @if(
                                                $salesTrend->isNotEmpty()
                                            )


                                            <div class="sales-line-chart">


                                                <div class="chart-y-label top">

                                                    ${{
                            number_format(
                                $trendMax,
                                0
                            )
                                                    }}

                                                </div>


                                                <div class="chart-y-label middle">

                                                    ${{
                            number_format(
                                $trendMax / 2,
                                0
                            )
                                                    }}

                                                </div>


                                                <div class="chart-y-label bottom">
                                                    $0
                                                </div>



                                                <svg class="sales-svg" viewBox="0 0 1000 250" preserveAspectRatio="none">


                                                    <defs>

                                                        <linearGradient id="salesAreaGradient" x1="0" y1="0" x2="0" y2="1">

                                                            <stop offset="0%" stop-color="#df628b" stop-opacity="0.28"></stop>

                                                            <stop offset="100%" stop-color="#df628b" stop-opacity="0.02"></stop>

                                                        </linearGradient>

                                                    </defs>



                                                    {{-- GRID --}}

                                                    <line x1="50" y1="50" x2="950" y2="50" class="chart-grid-line"></line>


                                                    <line x1="50" y1="132" x2="950" y2="132" class="chart-grid-line"></line>


                                                    <line x1="50" y1="215" x2="950" y2="215" class="chart-grid-line"></line>



                                                    {{-- AREA --}}

                                                    <polygon points="{{ $trendArea }}" class="sales-area"></polygon>



                                                    {{-- LINE --}}

                                                    <polyline points="{{ $trendPolyline }}" class="sales-line"></polyline>



                                                    {{-- POINTS --}}

                                                    @foreach(
                                                            $salesTrend
                                                            as $index => $trend
                                                        )


                                                        @php

                                                            $x =
                                                                $trendCount <= 1
                                                                ? 500
                                                                : 50
                                                                +
                                                                (
                                                                    $index
                                                                    /
                                                                    ($trendCount - 1)
                                                                )
                                                                * 900;


                                                            $y =
                                                                215
                                                                -
                                                                (
                                                                    (
                                                                        (float) 
                                                                        $trend->revenue
                                                                        /
                                                                        $trendMax
                                                                    )
                                                                    * 165
                                                                );

                                                        @endphp


                                                        <circle cx="{{ $x }}" cy="{{ $y }}" r="5" class="sales-point"></circle>


                                                    @endforeach


                                                </svg>



                                                <div class="trend-label-row">


                                                    @foreach(
                                                                                $labelIndexes
                                                                                as $labelIndex
                                                                            )


                                                                            @php

                                                                                $labelTrend =
                                                                                    $salesTrend[
                                                                                        $labelIndex
                                                                                    ];

                                                                            @endphp


                                                                            <span>

                                                                                {{
                                                        \Carbon\Carbon::parse(
                                                            $labelTrend->sales_date
                                                        )->format(
                                                                'd M'
                                                            )
                                                                                    }}

                                                                            </span>


                                                    @endforeach


                                                </div>


                                            </div>


                        @else


                            <div class="report-empty-state">

                                <strong>
                                    No sales data
                                </strong>

                                <span>

                                    There are no paid orders
                                    in this period.

                                </span>

                            </div>


                        @endif


                    </section>



                    {{-- =================================================
                    PAYMENT METHODS
                    ================================================= --}}

                    <section class="report-panel payment-panel">


                        <div class="panel-heading">

                            <div>

                                <h3>
                                    Payment Methods
                                </h3>

                                <p>
                                    Paid revenue distribution
                                </p>

                            </div>

                        </div>



                        @php

                            $paymentColors = [
                                '#d94b78',
                                '#a454de',
                                '#f29b4b',
                                '#59b989',
                                '#7188d8',
                            ];


                            $paymentGradientParts = [];

                            $paymentCursor = 0;


                            foreach (
                                $paymentMethods
                                as $paymentIndex => $payment
                            ) {

                                $percent =
                                    $totalRevenue > 0
                                    ? (
                                        (
                                            (float) 
                                            $payment->revenue
                                            /
                                            $totalRevenue
                                        )
                                        * 100
                                    )
                                    : 0;


                                $start =
                                    $paymentCursor;


                                $end =
                                    min(
                                        100,
                                        $paymentCursor
                                        +
                                        $percent
                                    );


                                $color =
                                    $paymentColors[
                                        $paymentIndex
                                        %
                                        count(
                                            $paymentColors
                                        )
                                    ];


                                $paymentGradientParts[] =
                                    "{$color} {$start}% {$end}%";


                                $paymentCursor =
                                    $end;
                            }


                            if (
                                $paymentCursor < 100
                            ) {

                                $paymentGradientParts[] =
                                    "#f1e7eb {$paymentCursor}% 100%";
                            }


                            $paymentGradient =
                                implode(
                                    ', ',
                                    $paymentGradientParts
                                );

                        @endphp



                        <div class="payment-layout">


                            <div class="payment-donut" style="
                                background:
                                conic-gradient(
                                    {{ $paymentGradient }}
                                );
                            ">

                                <div class="payment-donut-hole">

                                    <strong>

                                        ${{
    number_format(
        (float) 
        $totalRevenue,
        2
    )
                                    }}

                                    </strong>

                                    <span>
                                        Total Revenue
                                    </span>

                                </div>

                            </div>



                            <div class="payment-legend">


                                @forelse(
                                                                $paymentMethods
                                                                as $index => $payment
                                                            )


                                                            @php

                                                                $paymentPercent =
                                                                    $totalRevenue > 0
                                                                    ? round(
                                                                        (
                                                                            (float) 
                                                                            $payment->revenue
                                                                            /
                                                                            $totalRevenue
                                                                        )
                                                                        * 100,
                                                                        1
                                                                    )
                                                                    : 0;


                                                                $color =
                                                                    $paymentColors[
                                                                        $index
                                                                        %
                                                                        count(
                                                                            $paymentColors
                                                                        )
                                                                    ];

                                                            @endphp


                                                            <div class="payment-legend-row">

                                                                <span class="payment-dot" style="
                                                                        background:
                                                                        {{ $color }};
                                                                    "></span>


                                                                <strong>

                                                                    {{
                                    strtoupper(
                                        (string) 
                                        $payment->payment_method
                                    )
                                                                    }}

                                                                </strong>


                                                                <span>
                                                                    {{ $paymentPercent }}%
                                                                </span>


                                                                <b>

                                                                    ${{
                                    number_format(
                                        (float) 
                                        $payment->revenue,
                                        2
                                    )
                                                                    }}

                                                                </b>

                                                            </div>


                                @empty


                                    <div class="report-empty-inline">
                                        No payment data.
                                    </div>


                                @endforelse


                            </div>


                        </div>


                    </section>


                </div>



                {{-- =================================================
                TOP PRODUCTS + CATEGORY SALES
                ================================================= --}}

                <div class="report-secondary-grid">


                    {{-- =================================================
                    TOP SELLING PRODUCTS
                    ================================================= --}}

                    <section class="report-panel">


                        <div class="panel-heading">

                            <div>

                                <h3>
                                    Top Selling Products
                                </h3>

                                <p>
                                    Ranked by paid quantity
                                </p>

                            </div>


                            <a href="{{ route(
    'admin.products.index'
) }}" class="panel-link">
                                View Products
                            </a>

                        </div>



                        <div class="report-table-wrap">


                            <table class="report-table">


                                <thead>

                                    <tr>

                                        <th class="rank-col">
                                            #
                                        </th>

                                        <th>
                                            Product
                                        </th>

                                        <th>
                                            Category
                                        </th>

                                        <th>
                                            Quantity
                                        </th>

                                        <th>
                                            Revenue
                                        </th>

                                    </tr>

                                </thead>



                                <tbody>


                                    @forelse(
                                                                        $topProducts
                                                                        as $index => $product
                                                                    )


                                                                    @php

                                                                        $productImage =
                                                                            trim(
                                                                                (string) 
                                                                                (
                                                                                    $product->product_image
                                                                                    ??
                                                                                    ''
                                                                                )
                                                                            );


                                                                        $productImageUrl =
                                                                            null;


                                                                        if (
                                                                            $productImage !== ''
                                                                        ) {

                                                                            if (
                                                                                str_starts_with(
                                                                                    $productImage,
                                                                                    'products/'
                                                                                )
                                                                                ||
                                                                                str_starts_with(
                                                                                    $productImage,
                                                                                    'storage/products/'
                                                                                )
                                                                                ||
                                                                                str_starts_with(
                                                                                    $productImage,
                                                                                    '/storage/products/'
                                                                                )
                                                                            ) {

                                                                                $productImageUrl =
                                                                                    route(
                                                                                        'product.image',
                                                                                        [
                                                                                            'filename' =>
                                                                                                basename(
                                                                                                    $productImage
                                                                                                ),
                                                                                        ]
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

                                                                            } elseif (
                                                                                str_starts_with(
                                                                                    $productImage,
                                                                                    'assets/'
                                                                                )
                                                                            ) {

                                                                                $productImageUrl =
                                                                                    asset(
                                                                                        $productImage
                                                                                    );
                                                                            }
                                                                        }

                                                                    @endphp


                                                                    <tr>


                                                                        <td class="product-rank">

                                                                            {{ $index + 1 }}

                                                                        </td>



                                                                        <td>

                                                                            <div class="table-product">


                                                                                <div class="table-product-image">


                                                                                    @if(
                                                                                            $productImageUrl
                                                                                        )


                                                                                        <img src="{{ $productImageUrl }}"
                                                                                            alt="{{ $product->product_name }}">


                                                                                    @else


                                                                                                                                <span>

                                                                                                                                    {{
                                                                                        strtoupper(
                                                                                            substr(
                                                                                                $product->product_name,
                                                                                                0,
                                                                                                1
                                                                                            )
                                                                                        )
                                                                                                                                    }}

                                                                                                                                </span>


                                                                                    @endif


                                                                                </div>



                                                                                <div>

                                                                                    <strong>

                                                                                        {{
                                        $product->product_name
                                                                                    }}

                                                                                    </strong>

                                                                                    <span>

                                                                                        Product
                                                                                        #{{ $product->product_id }}

                                                                                    </span>

                                                                                </div>


                                                                            </div>

                                                                        </td>



                                                                        <td>

                                                                            <span class="category-badge">

                                                                                {{
                                        $product->category_name
                                                                            }}

                                                                            </span>

                                                                        </td>



                                                                        <td>

                                                                            <strong>

                                                                                {{
                                        $product->quantity_sold
                                                                            }}

                                                                            </strong>

                                                                        </td>



                                                                        <td class="money-cell">

                                                                            ${{
                                        number_format(
                                            (float) 
                                            $product->sales_amount,
                                            2
                                        )
                                                                        }}

                                                                        </td>


                                                                    </tr>


                                    @empty


                                        <tr>

                                            <td colspan="5" class="table-empty">

                                                No product sales available.

                                            </td>

                                        </tr>


                                    @endforelse


                                </tbody>


                            </table>


                        </div>


                    </section>



                    {{-- =================================================
                    SALES BY CATEGORY
                    ================================================= --}}

                    <section class="report-panel">


                        <div class="panel-heading">

                            <div>

                                <h3>
                                    Sales by Category
                                </h3>

                                <p>
                                    Revenue and units by category
                                </p>

                            </div>

                        </div>



                        <div class="category-sales-list">


                            @forelse(
                                                        $categoryPerformance
                                                        as $index => $category
                                                    )


                                                    @php

                                                        $categoryPercent =
                                                            $totalCategoryUnits > 0
                                                            ? round(
                                                                (
                                                                    (int) 
                                                                    $category->units_sold
                                                                    /
                                                                    $totalCategoryUnits
                                                                )
                                                                * 100,
                                                                1
                                                            )
                                                            : 0;


                                                        $categoryColors = [
                                                            '#d94b78',
                                                            '#a454de',
                                                            '#ef9a4d',
                                                            '#56b88a',
                                                            '#7188d8',
                                                        ];


                                                        $categoryColor =
                                                            $categoryColors[
                                                                $index
                                                                %
                                                                count(
                                                                    $categoryColors
                                                                )
                                                            ];

                                                    @endphp


                                                    <div class="category-sales-item">


                                                        <div class="category-sales-heading">


                                                            <strong>

                                                                {{
                                $category->category_name
                                                                }}

                                                            </strong>


                                                            <div>

                                                                <span>
                                                                    {{ $categoryPercent }}%
                                                                </span>

                                                                <b>

                                                                    ${{
                                number_format(
                                    (float) 
                                    $category->revenue,
                                    2
                                )
                                                                    }}

                                                                </b>

                                                            </div>


                                                        </div>



                                                        <div class="category-progress">

                                                            <div class="category-progress-fill" data-progress="{{ $categoryPercent }}" style="
                                                                    --category-color:
                                                                    {{ $categoryColor }};
                                                                "></div>

                                                        </div>



                                                        <small>

                                                            {{
                                $category->units_sold
                                                            }}

                                                            unit(s)

                                                        </small>


                                                    </div>


                            @empty


                                <div class="report-empty-inline">
                                    No category sales data.
                                </div>


                            @endforelse


                        </div>


                    </section>


                </div>



                {{-- =================================================
                TRANSACTIONS
                ================================================= --}}

                <section class="report-panel transactions-panel">


                    <div class="panel-heading">

                        <div>

                            <h3>
                                Transactions
                            </h3>

                            <p>
                                Latest customer orders
                            </p>

                        </div>


                        <a href="{{ route(
    'admin.orders.index'
) }}" class="panel-link">
                            View All Orders
                        </a>

                    </div>



                    <div class="report-table-wrap">


                        <table class="report-table transaction-table">


                            <thead>

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        Order ID
                                    </th>

                                    <th>
                                        Date & Time
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Payment
                                    </th>

                                    <th>
                                        Items
                                    </th>

                                    <th>
                                        Total Amount
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>



                            <tbody>


                                @forelse(
                                                                    $recentOrders
                                                                    as $index => $order
                                                                )


                                                                @php

                                                                    $rawStatus =
                                                                        strtolower(
                                                                            (string) 
                                                                            $order->status
                                                                        );


                                                                    $displayStatus =
                                                                        in_array(
                                                                            $rawStatus,
                                                                            [
                                                                                'pending',
                                                                                'processing',
                                                                            ]
                                                                        )
                                                                        ? 'Processing'
                                                                        : ucfirst(
                                                                            $rawStatus
                                                                        );

                                                                @endphp


                                                                <tr>


                                                                    <td>
                                                                        {{ $index + 1 }}
                                                                    </td>



                                                                    <td>

                                                                        <strong class="order-number">

                                                                            {{
                                        $order->order_number
                                                                        }}

                                                                        </strong>

                                                                    </td>



                                                                    <td>

                                                                        @if(
                                                                                                            $order->ordered_at
                                                                                                        )


                                                                                                        <strong>

                                                                                                            {{
                                                                            \Carbon\Carbon::parse(
                                                                                $order->ordered_at
                                                                            )->format(
                                                                                    'd M Y'
                                                                                )
                                                                                                            }}

                                                                                                        </strong>


                                                                                                        <span class="table-subtext">

                                                                                                            {{
                                                                            \Carbon\Carbon::parse(
                                                                                $order->ordered_at
                                                                            )->format(
                                                                                    'h:i A'
                                                                                )
                                                                                                            }}

                                                                                                        </span>


                                                                        @else


                                                                            —


                                                                        @endif

                                                                    </td>



                                                                    <td>

                                                                        <strong>

                                                                            {{
                                        $order->customer_name
                                                                        }}

                                                                        </strong>

                                                                        <span class="table-subtext">

                                                                            {{
                                        $order->customer_email
                                        ??
                                        '—'
                                                                        }}

                                                                        </span>

                                                                    </td>



                                                                    <td>

                                                                        <span class="payment-badge">

                                                                            {{
                                        strtoupper(
                                            (string) 
                                            $order->payment_method
                                        )
                                                                        }}

                                                                        </span>

                                                                    </td>



                                                                    <td>

                                                                        {{
                                        $order->items_count
                                                                    }}

                                                                    </td>



                                                                    <td class="money-cell">

                                                                        ${{
                                        number_format(
                                            (float) 
                                            $order->total,
                                            2
                                        )
                                                                    }}

                                                                    </td>



                                                                    <td>

                                                                        <span class="
                                                                            transaction-status
                                                                            {{
                                        strtolower(
                                            $displayStatus
                                        )
                                                                            }}
                                                                        ">

                                                                            {{
                                        $displayStatus
                                                                        }}

                                                                        </span>

                                                                    </td>



                                                                    <td>

                                                                        <a href="{{ route(
                                        'admin.orders.show',
                                        $order->id
                                    ) }}" class="transaction-view">
                                                                            View
                                                                        </a>

                                                                    </td>


                                                                </tr>


                                @empty


                                    <tr>

                                        <td colspan="9" class="table-empty">

                                            No transactions available.

                                        </td>

                                    </tr>


                                @endforelse


                            </tbody>


                        </table>


                    </div>


                </section>


            </section>


        </main>


    </div>


    <script src="{{ asset('js/admin/reports.js') }}"></script>


</body>

</html>