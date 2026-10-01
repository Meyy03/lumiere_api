<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lumière | Product Details</title>

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/products.css') }}">

</head>

<body>

    <div class="admin-layout">

        {{-- =====================================================
            SIDEBAR
        ====================================================== --}}

        @include('admin.partials.sidebar')


        {{-- =====================================================
            MAIN CONTENT
        ====================================================== --}}

        <main class="main-content">

            <header class="topbar">

                <div>

                    <h1>
                        Product Details
                    </h1>

                    <p>
                        Product #{{ $product->id }}
                    </p>

                </div>


                <div class="details-header-actions">

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="secondary-button"
                    >
                        ← Products
                    </a>

                    <a
                        href="{{ route('admin.products.edit', $product->id) }}"
                        class="primary-button"
                    >
                        Edit Product
                    </a>

                </div>

            </header>


            <section class="dashboard-content">

                {{-- =====================================================
                    SUCCESS MESSAGE
                ====================================================== --}}

                @if(session('success'))

                    <div class="product-alert success">

                        {{ session('success') }}

                    </div>

                @endif


                {{-- =====================================================
                    PRODUCT IMAGE URL
                ====================================================== --}}

                @php

                    $productImagePath = trim($product->image ?? '');

                    $productImageUrl = null;


                    if ($productImagePath !== '') {

                        /*
                        |--------------------------------------------------------------------------
                        | FULL URL
                        |--------------------------------------------------------------------------
                        |
                        | Example:
                        | http://127.0.0.1:8000/storage/products/example.png
                        |
                        */

                        if (
                            str_starts_with($productImagePath, 'http://') ||
                            str_starts_with($productImagePath, 'https://')
                        ) {

                            $productImageUrl = $productImagePath;

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | SEEDED PUBLIC ASSET
                        |--------------------------------------------------------------------------
                        |
                        | Example:
                        | assets/images/toner/toner_12.png
                        |
                        */

                        elseif (
                            str_starts_with($productImagePath, 'assets/')
                        ) {

                            $productImageUrl = asset($productImagePath);

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | STORAGE PATH
                        |--------------------------------------------------------------------------
                        |
                        | Example:
                        | storage/products/example.png
                        |
                        */

                        elseif (
                            str_starts_with($productImagePath, 'storage/')
                        ) {

                            $productImageUrl = asset($productImagePath);

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | ADMIN-UPLOADED PRODUCT IMAGE
                        |--------------------------------------------------------------------------
                        |
                        | Example:
                        | products/example.png
                        |
                        */

                        elseif (
                            str_starts_with($productImagePath, 'products/')
                        ) {

                            $productImageUrl = route(
                                'product.image',
                                [
                                    'filename' => basename($productImagePath),
                                ]
                            );

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | SAFE FALLBACK
                        |--------------------------------------------------------------------------
                        */

                        else {

                            $productImageUrl = asset($productImagePath);

                        }

                    }

                @endphp


                <div class="product-details-card">

                    {{-- =================================================
                        PRODUCT HEADER
                    ================================================== --}}

                    <div class="details-title">

                        <div class="details-product-avatar">

                            @if($productImageUrl)

                                <img
                                    src="{{ $productImageUrl }}"
                                    alt="{{ $product->name }}"
                                    class="details-header-product-image"
                                    style="
                                        width: 100%;
                                        height: 100%;
                                        display: block;
                                        object-fit: contain;
                                    "
                                >

                            @else

                                <div class="large-product-initial">

                                    {{
                                        strtoupper(
                                            substr(
                                                $product->name,
                                                0,
                                                1
                                            )
                                        )
                                    }}

                                </div>

                            @endif

                        </div>


                        <div>

                            <p class="details-id">

                                PRODUCT #{{ $product->id }}

                            </p>

                            <h2>

                                {{ $product->name }}

                            </h2>

                            <p>

                                {{ $product->category_name ?? 'Unknown Category' }}

                            </p>

                        </div>


                        <div class="details-status">

                            @if($product->is_active)

                                <span class="status-badge active">

                                    Active

                                </span>

                            @else

                                <span class="status-badge inactive">

                                    Inactive

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                        BASIC INFORMATION
                    ================================================== --}}

                    <div class="details-grid">

                        <div class="detail-item">

                            <span>
                                Price
                            </span>

                            <strong>

                                ${{ number_format($product->price, 2) }}

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Stock
                            </span>

                            <strong>

                                {{ number_format($product->stock) }}

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Default Size
                            </span>

                            <strong>

                                {{ $product->size }}

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Rating
                            </span>

                            <strong>

                                {{ number_format($product->rating, 2) }}/5

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Skin Type
                            </span>

                            <strong>

                                {{ $product->skin_type }}

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Category
                            </span>

                            <strong>

                                {{ $product->category_name ?? 'Unknown' }}

                            </strong>

                        </div>

                    </div>


                    {{-- =================================================
                        AVAILABLE SIZES
                    ================================================== --}}

                    <div class="details-section">

                        <h3>
                            Available Sizes
                        </h3>

                        <div class="size-list">

                            @forelse($product->available_sizes_array as $size)

                                <span>
                                    {{ $size }}
                                </span>

                            @empty

                                <p class="muted-text">

                                    No additional sizes.

                                </p>

                            @endforelse

                        </div>

                    </div>


                    {{-- =================================================
                        PRODUCT IMAGE
                    ================================================== --}}

                    <div class="details-section">

                        <h3>
                            Product Image
                        </h3>

                        @if($productImageUrl)

                            <div class="details-product-image-box">

                                <img
                                    src="{{ $productImageUrl }}"
                                    alt="{{ $product->name }}"
                                    class="details-product-image"
                                    style="
                                        width: 100%;
                                        max-width: 320px;
                                        height: 320px;
                                        display: block;
                                        object-fit: contain;
                                    "
                                >

                            </div>


                            <div
                                class="legacy-image-info"
                                style="margin-top: 14px;"
                            >

                                <strong>
                                    Image Path
                                </strong>

                                <span>

                                    {{ $productImagePath }}

                                </span>

                                @if(
                                    str_starts_with(
                                        $productImagePath,
                                        'assets/'
                                    )
                                )

                                    <small>

                                        Seeded product image stored in the
                                        Laravel public assets directory.

                                    </small>

                                @elseif(
                                    str_starts_with(
                                        $productImagePath,
                                        'products/'
                                    )
                                )

                                    <small>

                                        Product image uploaded through the
                                        Lumière Admin System.

                                    </small>

                                @else

                                    <small>

                                        Product image used by the Lumière
                                        application.

                                    </small>

                                @endif

                            </div>

                        @else

                            <p class="muted-text">

                                No product image available.

                            </p>

                        @endif

                    </div>


                    {{-- =================================================
                        DESCRIPTION
                    ================================================== --}}

                    <div class="details-section">

                        <h3>
                            Description
                        </h3>

                        <p>

                            {{
                                $product->description
                                ?: 'No description provided.'
                            }}

                        </p>

                    </div>


                    {{-- =================================================
                        PRODUCT DETAILS
                    ================================================== --}}

                    <div class="details-section">

                        <h3>
                            Product Details
                        </h3>

                        <p>

                            {{
                                $product->product_details
                                ?: 'No additional product details.'
                            }}

                        </p>

                    </div>


                    {{-- =================================================
                        INGREDIENTS
                    ================================================== --}}

                    <div class="details-section">

                        <h3>
                            Ingredients
                        </h3>

                        <p>

                            {{
                                $product->ingredients
                                ?: 'No ingredients provided.'
                            }}

                        </p>

                    </div>


                    {{-- =================================================
                        DELETE PROTECTION
                    ================================================== --}}

                    @if($usedInOrders)

                        <div class="delete-warning">

                            <strong>
                                Protected Product
                            </strong>

                            <p>

                                This product appears in an existing
                                customer order, so it cannot be
                                permanently deleted.

                                You may edit it or mark it inactive
                                instead.

                            </p>

                        </div>

                    @else

                        <div class="danger-zone">

                            <div>

                                <strong>
                                    Delete Product
                                </strong>

                                <p>

                                    Permanently remove this product from
                                    the Lumière store.

                                </p>

                            </div>


                            <form
                                method="POST"
                                action="{{
                                    route(
                                        'admin.products.destroy',
                                        $product->id
                                    )
                                }}"
                                onsubmit="
                                    return confirm(
                                        'Are you sure you want to permanently delete this product?'
                                    );
                                "
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="danger-button"
                                >

                                    Delete Product

                                </button>

                            </form>

                        </div>

                    @endif

                </div>


                {{-- =====================================================
                    FOOTER
                ====================================================== --}}

                <footer class="dashboard-footer">

                    © {{ date('Y') }}

                    Lumière Cosmetic & Skincare

                    E-Commerce Management System

                </footer>

            </section>

        </main>

    </div>

</body>

</html>