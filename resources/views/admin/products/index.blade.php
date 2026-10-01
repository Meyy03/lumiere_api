<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Lumière | Products
    </title>

    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">

    <link rel="stylesheet" href="{{ asset('css/admin/products.css') }}">
</head>

<body>

    <div class="admin-layout">

        @include('admin.partials.sidebar')

        <main class="main-content">

            <header class="topbar">

                <div>
                    <h1>
                        Product Management
                    </h1>

                    <p>
                        Create, view, edit and manage Lumière products.
                    </p>
                </div>

                <div class="topbar-right">

                    <a href="{{ route('admin.products.create') }}" class="primary-button">
                        + Add Product
                    </a>

                </div>

            </header>

            <section class="dashboard-content">

                {{-- SUCCESS MESSAGE --}}
                @if (session('success'))
                    <div class="product-alert success">
                        {{ session('success') }}
                    </div>
                @endif


                {{-- ERROR MESSAGE --}}
                @if (session('error'))
                    <div class="product-alert error">
                        {{ session('error') }}
                    </div>
                @endif


                {{-- SEARCH AND FILTER --}}
                <div class="product-toolbar">

                    <form method="GET" action="{{ route('admin.products.index') }}" class="product-filter-form">

                        {{-- SEARCH --}}
                        <div class="toolbar-search">

                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Search product name, ID or size..." autocomplete="off">

                        </div>


                        {{-- CATEGORY FILTER --}}
                        <div class="toolbar-category">

                            <select name="category">

                                <option value="">
                                    All Categories
                                </option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ (string) $categoryId === (string) $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- AUDIENCE FILTER --}}
                        <div class="toolbar-category">

                            <select name="audience">

                                <option value="">
                                    All Audiences
                                </option>

                                <option value="female" {{ ($audience ?? '') === 'female' ? 'selected' : '' }}>
                                    Women
                                </option>

                                <option value="male" {{ ($audience ?? '') === 'male' ? 'selected' : '' }}>
                                    Men
                                </option>

                                <option value="all" {{ ($audience ?? '') === 'all' ? 'selected' : '' }}>
                                    All / Unisex
                                </option>

                            </select>

                        </div>


                        <button type="submit" class="filter-button">
                            Search
                        </button>


                        <a href="{{ route('admin.products.index') }}" class="clear-button">
                            Clear
                        </a>

                    </form>

                </div>


                {{-- PRODUCT TABLE --}}
                <div class="product-table-card">

                    <div class="table-header">

                        <div>

                            <h2>
                                Products
                            </h2>

                            <p>
                                {{ number_format($products->total()) }}
                                product(s) found
                            </p>

                        </div>

                    </div>


                    <div class="table-responsive">

                        <table class="product-table">

                            <thead>

                                <tr>

                                    <th>
                                        ID
                                    </th>

                                    <th>
                                        Product
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Audience
                                    </th>

                                    <th>
                                        Size
                                    </th>

                                    <th>
                                        Price
                                    </th>

                                    <th>
                                        Stock
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="action-heading">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($products as $product)
                                    <tr>

                                        {{-- ID --}}
                                        <td class="product-id">

                                            #{{ $product->id }}

                                        </td>


                                        {{-- PRODUCT --}}
                                        <td>

                                            <div class="product-name-cell">

                                                <div class="product-initial"
                                                    style="
        overflow: hidden;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    ">
                                                    @php
                                                        $productImagePath = trim($product->image ?? '');
                                                        $productImageUrl = null;

                                                        if ($productImagePath !== '') {
                                                            /*
            |--------------------------------------------------------------------------
            | FULL URL
            |--------------------------------------------------------------------------
            | Example:
            | http://127.0.0.1:8000/storage/products/abc.png
            */
                                                            if (
                                                                str_starts_with($productImagePath, 'http://') ||
                                                                str_starts_with($productImagePath, 'https://')
                                                            ) {
                                                                $productImageUrl = $productImagePath;
                                                            }
                                                            /*
            |--------------------------------------------------------------------------
            | SEEDED / PUBLIC ASSET IMAGE
            |--------------------------------------------------------------------------
            | Example:
            | assets/images/cleanser/cleanser_1.png
            */ elseif (
                                                                str_starts_with($productImagePath, 'assets/')
                                                            ) {
                                                                $productImageUrl = asset($productImagePath);
                                                            }
                                                            /*
            |--------------------------------------------------------------------------
            | STORAGE IMAGE
            |--------------------------------------------------------------------------
            | Example:
            | storage/products/abc.png
            */ elseif (
                                                                str_starts_with($productImagePath, 'storage/')
                                                            ) {
                                                                $productImageUrl = asset($productImagePath);
                                                            }
                                                            /*
            |--------------------------------------------------------------------------
            | ADMIN-UPLOADED PRODUCT IMAGE
            |--------------------------------------------------------------------------
            | Example:
            | products/abc.png
            */ elseif (
                                                                str_starts_with($productImagePath, 'products/')
                                                            ) {
                                                                $productImageUrl = route('product.image', [
                                                                    'filename' => basename($productImagePath),
                                                                ]);
                                                            }
                                                            /*
            |--------------------------------------------------------------------------
            | FALLBACK PATH
            |--------------------------------------------------------------------------
            */ else {
                                                                $productImageUrl = asset($productImagePath);
                                                            }
                                                        }
                                                    @endphp

                                                    @if ($productImageUrl)
                                                        <img src="{{ $productImageUrl }}" alt="{{ $product->name }}"
                                                            style="
                width: 100%;
                height: 100%;
                display: block;
                object-fit: contain;
            ">
                                                    @else
                                                        <span>
                                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                                        </span>
                                                    @endif
                                                </div>


                                                <div>

                                                    <strong>
                                                        {{ $product->name }}
                                                    </strong>

                                                    <small>

                                                        Rating:

                                                        {{ number_format($product->rating, 1) }}/5

                                                    </small>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- CATEGORY --}}
                                        <td>

                                            {{ $product->category_name ?? 'Unknown' }}

                                        </td>


                                        {{-- TARGET AUDIENCE --}}
                                        <td>

                                            @if ($product->target_audience === 'female')
                                                <span
                                                    style="
                                                                                    display: inline-flex;
                                                                                    padding: 6px 10px;
                                                                                    border-radius: 999px;
                                                                                    background: #fff0f6;
                                                                                    color: #c2255c;
                                                                                    font-size: 12px;
                                                                                    font-weight: 700;
                                                                                    white-space: nowrap;
                                                                                ">
                                                    Women
                                                </span>
                                            @elseif($product->target_audience === 'male')
                                                <span
                                                    style="
                                                                                    display: inline-flex;
                                                                                    padding: 6px 10px;
                                                                                    border-radius: 999px;
                                                                                    background: #eef5ff;
                                                                                    color: #2457a6;
                                                                                    font-size: 12px;
                                                                                    font-weight: 700;
                                                                                    white-space: nowrap;
                                                                                ">
                                                    Men
                                                </span>
                                            @else
                                                <span
                                                    style="
                                                                                    display: inline-flex;
                                                                                    padding: 6px 10px;
                                                                                    border-radius: 999px;
                                                                                    background: #f3f4f6;
                                                                                    color: #4b5563;
                                                                                    font-size: 12px;
                                                                                    font-weight: 700;
                                                                                    white-space: nowrap;
                                                                                ">
                                                    All / Unisex
                                                </span>
                                            @endif

                                        </td>


                                        {{-- SIZE --}}
                                        <td>

                                            {{ $product->size }}

                                        </td>


                                        {{-- PRICE --}}
                                        <td class="price-cell">

                                            ${{ number_format($product->price, 2) }}

                                        </td>


                                        {{-- STOCK --}}
                                        <td>

                                            @if ($product->stock <= 0)
                                                <span class="stock-badge out">

                                                    Out of Stock

                                                </span>
                                            @elseif($product->stock <= 10)
                                                <span class="stock-badge low">

                                                    {{ $product->stock }}
                                                    Low

                                                </span>
                                            @else
                                                <span class="stock-badge good">

                                                    {{ $product->stock }}

                                                </span>
                                            @endif

                                        </td>


                                        {{-- STATUS --}}
                                        <td>

                                            @if ($product->is_active)
                                                <span class="status-badge active">

                                                    Active

                                                </span>
                                            @else
                                                <span class="status-badge inactive">

                                                    Inactive

                                                </span>
                                            @endif

                                        </td>


                                        {{-- ACTIONS --}}
                                        <td>

                                            <div class="table-actions">

                                                {{-- VIEW --}}
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                    class="action-button view">
                                                    View
                                                </a>


                                                {{-- EDIT --}}
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                    class="action-button edit">
                                                    Edit
                                                </a>


                                                {{-- DELETE --}}
                                                <form method="POST"
                                                    action="{{ route('admin.products.destroy', $product->id) }}"
                                                    data-confirm data-confirm-type="delete"
                                                    data-confirm-title="Delete Product?"
                                                    data-confirm-message="Are you sure you want to delete this product? This action cannot be undone."
                                                    data-confirm-button="Yes, Delete">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit" class="action-button delete">
                                                        Delete
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="9" class="empty-state">
                                            No products found.
                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- PAGINATION --}}
                    @if ($products->hasPages())

                        <div class="pagination-wrapper">


                            {{-- PREVIOUS --}}
                            @if ($products->onFirstPage())
                                <span class="pagination-button disabled">

                                    Previous

                                </span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" class="pagination-button">
                                    Previous
                                </a>
                            @endif


                            {{-- CURRENT PAGE --}}
                            <span class="pagination-info">

                                Page

                                {{ $products->currentPage() }}

                                of

                                {{ $products->lastPage() }}

                            </span>


                            {{-- NEXT --}}
                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="pagination-button">
                                    Next
                                </a>
                            @else
                                <span class="pagination-button disabled">

                                    Next

                                </span>
                            @endif

                        </div>

                    @endif

                </div>


                {{-- FOOTER --}}
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
