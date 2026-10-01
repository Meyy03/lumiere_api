<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lumière | Category Details</title>

    {{-- Shared Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    {{-- Category CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin/categories.css') }}">
</head>

<body>

<div class="admin-layout">

    {{-- =====================================================
        SHARED SIDEBAR
    ====================================================== --}}
    @include('admin.partials.sidebar')


    {{-- =====================================================
        MAIN CONTENT
    ====================================================== --}}
    <main class="main-content">

        {{-- =================================================
            TOP BAR
        ================================================== --}}
        <header class="topbar">

            <div>
                <h1>Category Details</h1>
                <p>Category #{{ $category->id }}</p>
            </div>

            <div class="category-details-actions">

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="secondary-button"
                >
                    ← Categories
                </a>

                <a
                    href="{{ route('admin.categories.edit', $category->id) }}"
                    class="primary-button"
                >
                    Edit Category
                </a>

            </div>

        </header>


        {{-- =================================================
            PAGE CONTENT
        ================================================== --}}
        <section class="dashboard-content">


            {{-- =================================================
                SUCCESS MESSAGE
            ================================================== --}}
            @if(session('success'))

                <div class="category-alert success">

                    <span>✓</span>

                    <div>
                        {{ session('success') }}
                    </div>

                </div>

            @endif


            {{-- =================================================
                ERROR MESSAGE
            ================================================== --}}
            @if(session('error'))

                <div class="category-alert error">

                    <span>!</span>

                    <div>
                        {{ session('error') }}
                    </div>

                </div>

            @endif


            {{-- =================================================
                CATEGORY VISUAL
            ================================================== --}}
            @php

                $categoryImagePath = trim($category->image ?? '');
                $categoryIconPath = trim($category->icon ?? '');

                $categoryVisualUrl = null;
                $categoryVisualPath = null;
                $categoryVisualType = null;


                /*
                |--------------------------------------------------------------------------
                | ADMIN / CATEGORY IMAGE
                |--------------------------------------------------------------------------
                */
                if ($categoryImagePath !== '') {

                    $categoryVisualPath = $categoryImagePath;
                    $categoryVisualType = 'image';


                    if (
                        str_starts_with($categoryImagePath, 'http://') ||
                        str_starts_with($categoryImagePath, 'https://')
                    ) {

                        $categoryVisualUrl = $categoryImagePath;

                    } elseif (
                        str_starts_with($categoryImagePath, 'assets/')
                    ) {

                        $categoryVisualUrl = asset($categoryImagePath);

                    } elseif (
                        str_starts_with($categoryImagePath, 'storage/')
                    ) {

                        $categoryVisualUrl = asset($categoryImagePath);

                    } else {

                        /*
                        | Example:
                        | categories/example.png
                        */

                        $categoryVisualUrl = asset(
                            'storage/' . $categoryImagePath
                        );

                    }

                }

                /*
                |--------------------------------------------------------------------------
                | SEEDED SVG ICON
                |--------------------------------------------------------------------------
                */
                elseif ($categoryIconPath !== '') {

                    $categoryVisualPath = $categoryIconPath;
                    $categoryVisualType = 'icon';


                    if (
                        str_starts_with($categoryIconPath, 'http://') ||
                        str_starts_with($categoryIconPath, 'https://')
                    ) {

                        $categoryVisualUrl = $categoryIconPath;

                    } elseif (
                        str_starts_with($categoryIconPath, 'storage/')
                    ) {

                        $categoryVisualUrl = asset($categoryIconPath);

                    } else {

                        /*
                        | Example:
                        | assets/images/svg/cleanser.svg
                        */

                        $categoryVisualUrl = asset($categoryIconPath);

                    }

                }

            @endphp


            {{-- =================================================
                CATEGORY DETAILS CARD
            ================================================== --}}
            <div class="category-details-card">


                {{-- =================================================
                    CATEGORY TITLE
                ================================================== --}}
                <div class="category-details-title">


                    {{-- CATEGORY IMAGE / ICON --}}
                    <div
                        class="category-details-initial"
                        style="
                            overflow: hidden;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        "
                    >

                        @if($categoryVisualUrl)

                            <img
                                src="{{ $categoryVisualUrl }}"
                                alt="{{ $category->name }}"
                                style="
                                    width: 100%;
                                    height: 100%;
                                    display: block;
                                    object-fit: contain;
                                    padding: 8px;
                                    box-sizing: border-box;
                                "
                            >

                        @else

                            {{
                                strtoupper(
                                    substr(
                                        $category->name,
                                        0,
                                        1
                                    )
                                )
                            }}

                        @endif

                    </div>


                    {{-- CATEGORY NAME --}}
                    <div class="category-title-info">

                        <p class="category-details-id">
                            CATEGORY #{{ $category->id }}
                        </p>

                        <h2>
                            {{ $category->name }}
                        </h2>

                        <p>
                            Lumière Product Category
                        </p>

                    </div>


                    {{-- CATEGORY STATUS --}}
                    <div class="category-details-status">

                        @if($productCount > 0)

                            <span class="category-protected-badge">
                                Protected
                            </span>

                        @else

                            <span class="category-empty-badge">
                                Empty Category
                            </span>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    CATEGORY INFORMATION
                ================================================== --}}
                <div class="category-info-grid">


                    {{-- NAME --}}
                    <div class="category-info-box">

                        <span>
                            Category Name
                        </span>

                        <strong>
                            {{ $category->name }}
                        </strong>

                    </div>


                    {{-- SLUG --}}
                    <div class="category-info-box">

                        <span>
                            Slug
                        </span>

                        <strong>
                            {{ $category->slug }}
                        </strong>

                    </div>


                    {{-- PRODUCT COUNT --}}
                    <div class="category-info-box">

                        <span>
                            Products
                        </span>

                        <strong>
                            {{ number_format($productCount) }}
                        </strong>

                    </div>


                    {{-- CREATED DATE --}}
                    <div class="category-info-box">

                        <span>
                            Created Date
                        </span>

                        <strong>

                            @if($category->created_at)

                                {{
                                    $category->created_at
                                        ->format('d M Y')
                                }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>


                    {{-- LAST UPDATED --}}
                    <div class="category-info-box">

                        <span>
                            Last Updated
                        </span>

                        <strong>

                            @if($category->updated_at)

                                {{
                                    $category->updated_at
                                        ->format('d M Y')
                                }}

                            @else

                                —

                            @endif

                        </strong>

                    </div>


                    {{-- DELETE STATUS --}}
                    <div class="category-info-box">

                        <span>
                            Delete Status
                        </span>

                        <strong>

                            @if($productCount > 0)

                                Protected

                            @else

                                Can Be Deleted

                            @endif

                        </strong>

                    </div>

                </div>


                {{-- =================================================
                    CATEGORY IMAGE / ICON
                ================================================== --}}
                <div class="category-details-section">

                    <h3>
                        Category Image
                    </h3>


                    @if($categoryVisualUrl)

                        <div
                            style="
                                display: flex;
                                align-items: center;
                                gap: 20px;
                                padding: 18px;
                                background: #fffafb;
                                border: 1px solid #eee3e6;
                                border-radius: 12px;
                            "
                        >

                            {{-- IMAGE / ICON --}}
                            <div
                                style="
                                    width: 120px;
                                    height: 120px;
                                    min-width: 120px;
                                    overflow: hidden;
                                    border-radius: 16px;
                                    border: 1px solid #efd9e0;
                                    background: #ffffff;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                "
                            >

                                <img
                                    src="{{ $categoryVisualUrl }}"
                                    alt="{{ $category->name }}"
                                    style="
                                        width: 100%;
                                        height: 100%;
                                        display: block;
                                        object-fit: contain;
                                        padding: 12px;
                                        box-sizing: border-box;
                                    "
                                >

                            </div>


                            {{-- IMAGE INFORMATION --}}
                            <div
                                style="
                                    display: flex;
                                    flex-direction: column;
                                    gap: 7px;
                                    min-width: 0;
                                "
                            >

                                <strong
                                    style="
                                        color: #3b3035;
                                        font-size: 13px;
                                    "
                                >
                                    {{ basename($categoryVisualPath) }}
                                </strong>


                                @if($categoryVisualType === 'icon')

                                    <span
                                        style="
                                            color: #d83d68;
                                            font-size: 10px;
                                            font-weight: 700;
                                        "
                                    >
                                        Seeded SVG Category Icon
                                    </span>

                                @else

                                    <span
                                        style="
                                            color: #31905a;
                                            font-size: 10px;
                                            font-weight: 700;
                                        "
                                    >
                                        Laravel Category Image
                                    </span>

                                @endif


                                <small
                                    style="
                                        color: #998c91;
                                        font-size: 10px;
                                        word-break: break-all;
                                    "
                                >
                                    {{ $categoryVisualPath }}
                                </small>


                                @if($categoryVisualType === 'icon')

                                    <small
                                        style="
                                            color: #a4949a;
                                            font-size: 10px;
                                        "
                                    >
                                        This seeded category uses its
                                        prepared Lumière SVG icon.
                                    </small>

                                @else

                                    <small
                                        style="
                                            color: #a4949a;
                                            font-size: 10px;
                                        "
                                    >
                                        This category image can be used
                                        by the Lumière mobile application
                                        through the REST API.
                                    </small>

                                @endif

                            </div>

                        </div>

                    @else

                        <div class="category-no-data">

                            No category image or icon has been assigned yet.

                        </div>

                    @endif

                </div>


                {{-- =================================================
                    PRODUCTS IN THIS CATEGORY
                ================================================== --}}
                <div class="category-details-section">

                    <div class="category-products-header">

                        <div>

                            <h3>
                                Products in this Category
                            </h3>

                            <p>
                                {{ number_format($productCount) }}
                                product(s) currently assigned
                            </p>

                        </div>

                    </div>


                    @if($products->count() > 0)

                        <div class="category-products-table-wrapper">

                            <table class="category-products-table">

                                <thead>

                                <tr>

                                    <th>
                                        ID
                                    </th>

                                    <th>
                                        PRODUCT
                                    </th>

                                    <th>
                                        SIZE
                                    </th>

                                    <th>
                                        PRICE
                                    </th>

                                    <th>
                                        STOCK
                                    </th>

                                    <th>
                                        STATUS
                                    </th>

                                    <th>
                                        ACTION
                                    </th>

                                </tr>

                                </thead>


                                <tbody>

                                @foreach($products as $product)

                                    <tr>

                                        {{-- PRODUCT ID --}}
                                        <td>

                                            <span class="category-id">
                                                #{{ $product->id }}
                                            </span>

                                        </td>


                                        {{-- PRODUCT NAME --}}
                                        <td>

                                            <strong class="category-product-name">
                                                {{ $product->name }}
                                            </strong>

                                        </td>


                                        {{-- SIZE --}}
                                        <td>
                                            {{ $product->size ?? '—' }}
                                        </td>


                                        {{-- PRICE --}}
                                        <td>

                                            <strong>
                                                ${{
                                                    number_format(
                                                        (float) $product->price,
                                                        2
                                                    )
                                                }}
                                            </strong>

                                        </td>


                                        {{-- STOCK --}}
                                        <td>

                                            <span
                                                class="
                                                    category-stock-badge
                                                    {{
                                                        $product->stock > 0
                                                            ? 'in-stock'
                                                            : 'out-stock'
                                                    }}
                                                "
                                            >
                                                {{
                                                    number_format(
                                                        $product->stock
                                                    )
                                                }}
                                            </span>

                                        </td>


                                        {{-- STATUS --}}
                                        <td>

                                            @if($product->is_active)

                                                <span class="category-active-badge">
                                                    Active
                                                </span>

                                            @else

                                                <span class="category-inactive-badge">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>


                                        {{-- ACTION --}}
                                        <td>

                                            <a
                                                href="{{
                                                    route(
                                                        'admin.products.show',
                                                        $product->id
                                                    )
                                                }}"
                                                class="action-button view"
                                            >
                                                View
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="category-empty-products">

                            <div class="category-empty-products-icon">
                                ♡
                            </div>

                            <strong>
                                No Products
                            </strong>

                            <p>
                                There are currently no products
                                assigned to this category.
                            </p>

                        </div>

                    @endif

                </div>


                {{-- =================================================
                    DELETE SECTION
                ================================================== --}}
                <div class="category-delete-section">

                    @if($productCount > 0)

                        {{-- PROTECTED CATEGORY --}}
                        <div class="category-protection-info">

                            <div class="protection-symbol">
                                !
                            </div>

                            <div>

                                <strong>
                                    Category Protected
                                </strong>

                                <p>
                                    This category cannot be deleted because
                                    it currently contains
                                    {{ number_format($productCount) }}
                                    product(s).
                                </p>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="delete-category-button disabled"
                            disabled
                        >
                            Delete Category
                        </button>

                    @else

                        {{-- SAFE TO DELETE --}}
                        <div class="category-delete-info">

                            <div>

                                <strong>
                                    Delete Category
                                </strong>

                                <p>
                                    This category contains no products and
                                    can be safely deleted.
                                </p>

                            </div>

                        </div>


                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.categories.destroy',
                                    $category->id
                                )
                            }}"
                            onsubmit="
                                return confirm(
                                    'Are you sure you want to delete this category?'
                                );
                            "
                        >

                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
                                class="delete-category-button"
                            >
                                Delete Category
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </section>

    </main>

</div>

</body>

</html>