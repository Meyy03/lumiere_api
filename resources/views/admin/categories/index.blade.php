<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">


    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>

        Lumière | Categories

    </title>


    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">


    <link rel="stylesheet" href="{{ asset('css/admin/categories.css') }}">

</head>


<body>


    <div class="admin-layout">


        @include('admin.partials.sidebar')


        <main class="main-content">


            <header class="topbar">


                <div>


                    <h1>

                        Category Management

                    </h1>


                    <p>

                        Create, view, edit and manage Lumière categories.

                    </p>


                </div>


                <div class="topbar-actions">


                    <a href="{{ route(

    'admin.categories.create'

) }}" class="primary-button">

                        + Add Category

                    </a>


                </div>


            </header>


            <section class="dashboard-content">


                {{-- SUCCESS --}}


                @if(session('success'))


                    <div class="category-alert success">


                        <span>

                            ✓

                        </span>


                        <div>

                            {{ session('success') }}

                        </div>


                    </div>


                @endif


                {{-- ERROR --}}


                @if(session('error'))


                    <div class="category-alert error">


                        <span>

                            !

                        </span>


                        <div>

                            {{ session('error') }}

                        </div>


                    </div>


                @endif


                {{-- STATS --}}


                <div class="category-stats">


                    <div class="category-stat-card">


                        <div>


                            <span class="stat-label">

                                Total Categories

                            </span>


                            <h2>

                                {{

    number_format(

        $totalCategories

    )

                            }}

                            </h2>


                            <p>

                                Product categories in Lumière

                            </p>


                        </div>


                        <div class="category-stat-icon">

                            ▦

                        </div>


                    </div>


                    <div class="category-stat-card">


                        <div>


                            <span class="stat-label">

                                Total Products

                            </span>


                            <h2>

                                {{

    number_format(

        $totalProducts

    )

                            }}

                            </h2>


                            <p>

                                Products across all categories

                            </p>


                        </div>


                        <div class="category-stat-icon">

                            ♡

                        </div>


                    </div>


                </div>


                {{-- SEARCH --}}


                <div class="category-toolbar">


                    <form method="GET" action="{{ route(

    'admin.categories.index'

) }}" class="category-filter-form">


                        <div class="toolbar-search">


                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Search category name, ID or slug..." autocomplete="off">


                        </div>


                        <button type="submit" class="filter-button">

                            Search

                        </button>


                        <a href="{{ route(

    'admin.categories.index'

) }}" class="clear-button">

                            Clear

                        </a>


                    </form>


                </div>


                {{-- TABLE --}}


                <div class="category-table-card">


                    <div class="category-card-header">


                        <div>


                            <h2>

                                Categories

                            </h2>


                            <p>


                                {{

    number_format(

        $categories->total()

    )

                            }}


                                category(s) found


                            </p>


                        </div>


                    </div>


                    <div class="table-responsive">


                        <table class="category-table">


                            <thead>


                                <tr>


                                    <th>

                                        ID

                                    </th>


                                    <th>

                                        CATEGORY

                                    </th>


                                    <th>

                                        SLUG

                                    </th>


                                    <th>

                                        IMAGE

                                    </th>


                                    <th>

                                        PRODUCTS

                                    </th>


                                    <th>

                                        CREATED

                                    </th>


                                    <th>

                                        ACTIONS

                                    </th>


                                </tr>


                            </thead>


                            <tbody>


                                @forelse(

                                                                    $categories as $category

                                                                )


                                                                <tr>


                                                                    {{-- ID --}}


                                                                    <td>


                                                                        <span class="category-id">

                                                                            #{{ $category->id }}

                                                                        </span>


                                                                    </td>


                                                                    {{-- CATEGORY --}}

                                                                    <td>

                                                                        <div class="category-name-cell">

                                                                            <div class="category-initial" style="
                                                                                overflow: hidden;
                                                                                padding: 0;
                                                                                display: flex;
                                                                                align-items: center;
                                                                                justify-content: center;
                                                                            ">

                                                                                @if(!empty($category->icon))

                                                                                    <img src="{{ asset($category->icon) }}" alt="{{ $category->name }}"
                                                                                        style="
                                                                                            width: 26px;
                                                                                            height: 26px;
                                                                                            display: block;
                                                                                            object-fit: contain;
                                                                                        ">

                                                                                @elseif(!empty($category->image))

                                                                                                                            <img src="{{ route(
                                                                                        'category.image',
                                                                                        [
                                                                                            'filename' => basename(
                                                                                                $category->image
                                                                                            )
                                                                                        ]
                                                                                    ) }}" alt="{{ $category->name }}" style="
                                                                                                                                    width: 100%;
                                                                                                                                    height: 100%;
                                                                                                                                    display: block;
                                                                                                                                    object-fit: cover;
                                                                                                                                ">

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

                                                                            <div>

                                                                                <strong>
                                                                                    {{ $category->name }}
                                                                                </strong>

                                                                                <small>
                                                                                    Lumière category
                                                                                </small>

                                                                            </div>

                                                                        </div>

                                                                    </td>


                                                                    {{-- SLUG --}}


                                                                    <td>


                                                                        <span class="slug-badge">

                                                                            {{ $category->slug }}

                                                                        </span>


                                                                    </td>


                                                                    {{-- ICON --}}

                                                                    <td>

                                                                        @if(!empty($category->icon))

                                                                            <a href="{{ asset($category->icon) }}" target="_blank" rel="noopener noreferrer"
                                                                                class="action-button view">
                                                                                View Icon
                                                                            </a>

                                                                        @elseif(!empty($category->image))

                                                                                                            <a href="{{ route(
                                                                                'category.image',
                                                                                [
                                                                                    'filename' => basename(
                                                                                        $category->image
                                                                                    )
                                                                                ]
                                                                            ) }}" target="_blank" rel="noopener noreferrer" class="action-button view">
                                                                                                                View Image
                                                                                                            </a>

                                                                        @else

                                                                            <span class="no-value">
                                                                                No icon
                                                                            </span>

                                                                        @endif

                                                                    </td>


                                                                    {{-- PRODUCTS --}}


                                                                    <td>


                                                                        @if(

                                                                                                            $category->products_count > 0

                                                                                                        )


                                                                                                        <span class="product-count has-products">


                                                                                                            {{

                                                                            number_format(

                                                                                $category

                                                                                    ->products_count

                                                                            )

                                                                                                            }}


                                                                                                        </span>


                                                                        @else


                                                                            <span class="product-count empty">

                                                                                0

                                                                            </span>


                                                                        @endif


                                                                    </td>


                                                                    {{-- CREATED --}}


                                                                    <td>


                                                                        @if(

                                                                                                            $category->created_at

                                                                                                        )


                                                                                                        <span class="created-date">


                                                                                                            {{

                                                                            $category

                                                                                ->created_at

                                                                                ->format(

                                                                                    'd M Y'

                                                                                )

                                                                                                            }}


                                                                                                        </span>


                                                                        @else


                                                                            <span class="no-value">

                                                                                —

                                                                            </span>


                                                                        @endif


                                                                    </td>


                                                                    {{-- ACTIONS --}}


                                                                    <td>


                                                                        <div class="action-group">


                                                                            <a href="{{ route(

                                        'admin.categories.show',

                                        $category->id

                                    ) }}" class="action-button view">

                                                                                View

                                                                            </a>


                                                                            <a href="{{ route(

                                        'admin.categories.edit',

                                        $category->id

                                    ) }}" class="action-button edit">

                                                                                Edit

                                                                            </a>


                                                                            {{-- DELETE ONLY WHEN EMPTY --}}


                                                                            @if(

                                                                                                                        $category->products_count == 0

                                                                                                                    )


                                                                                                                    <form method="POST" action="{{ route(

                                                                                    'admin.categories.destroy',

                                                                                    $category->id

                                                                                ) }}" data-confirm data-confirm-type="delete"
                                                                                                                        data-confirm-title="Delete Category?"
                                                                                                                        data-confirm-message="Are you sure you want to delete this category? This action cannot be undone."
                                                                                                                        data-confirm-button="Yes, Delete">


                                                                                                                        @csrf


                                                                                                                        @method('DELETE')


                                                                                                                        <button type="submit" class="action-button delete">

                                                                                                                            Delete

                                                                                                                        </button>


                                                                                                                    </form>


                                                                            @else


                                                                                <button type="button" class="action-button protected"
                                                                                    title="This category contains products and cannot be deleted." disabled>

                                                                                    Protected

                                                                                </button>


                                                                            @endif


                                                                        </div>


                                                                    </td>


                                                                </tr>


                                @empty


                                    <tr>


                                        <td colspan="7" class="empty-table">


                                            <div class="empty-icon">

                                                ▦

                                            </div>


                                            <strong>

                                                No categories found

                                            </strong>


                                            <p>

                                                Try another search or

                                                create a new category.

                                            </p>


                                        </td>


                                    </tr>


                                @endforelse


                            </tbody>


                        </table>


                    </div>


                    {{-- PAGINATION --}}


                    @if(

                                        $categories->hasPages()

                                    )


                                    <div class="category-pagination">


                                        <div class="pagination-info">


                                            Showing


                                            <strong>

                                                {{

                        $categories

                            ->firstItem()

                                                }}

                                            </strong>


                                            to


                                            <strong>

                                                {{

                        $categories

                            ->lastItem()

                                                }}

                                            </strong>


                                            of


                                            <strong>

                                                {{

                        $categories

                            ->total()

                                                }}

                                            </strong>


                                            categories


                                        </div>


                                        <div class="pagination-buttons">


                                            @if(

                                                    $categories->onFirstPage()

                                                )


                                                <span class="page-button disabled">

                                                    ← Previous

                                                </span>


                                            @else


                                                                <a href="{{

                                                $categories

                                                    ->previousPageUrl()

                                                                        }}" class="page-button">

                                                                    ← Previous

                                                                </a>


                                            @endif


                                            <span class="current-page">


                                                {{

                        $categories

                            ->currentPage()

                                                }}


                                                /


                                                {{

                        $categories

                            ->lastPage()

                                                }}


                                            </span>


                                            @if(

                                                                    $categories->hasMorePages()

                                                                )


                                                                <a href="{{

                                                $categories

                                                    ->nextPageUrl()

                                                                        }}" class="page-button">

                                                                    Next →

                                                                </a>


                                            @else


                                                <span class="page-button disabled">

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